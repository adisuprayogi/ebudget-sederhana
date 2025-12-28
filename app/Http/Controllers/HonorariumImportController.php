<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HonorariumImportController extends Controller
{
    public function importPreview(Request $request)
    {
        // Log incoming request for debugging
        \Log::info('Import preview request', [
            'has_file' => $request->hasFile('file'),
            'file' => $request->file('file') ? [
                'original_name' => $request->file('file')->getClientOriginalName(),
                'extension' => $request->file('file')->getClientOriginalExtension(),
                'mime_type' => $request->file('file')->getMimeType(),
                'size' => $request->file('file')->getSize(),
            ] : null,
            'users_count' => count(json_decode($request->input('users', '[]'), true)),
        ]);

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:5120', // Max 5MB - removed mimes check
        ]);

        if ($validator->fails()) {
            \Log::error('Import validation failed', ['errors' => $validator->errors()->toArray()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $validator->errors()->all()),
            ], 400);
        }

        // Validate file extension manually
        $file = $request->file('file');
        $allowedExtensions = ['xlsx', 'xls', 'csv'];
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
            \Log::error('Invalid file extension', ['extension' => $file->getClientOriginalExtension()]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid file format. Please upload .xlsx, .xls, or .csv file.',
            ], 400);
        }

        try {
            $file = $request->file('file');
            $users = json_decode($request->input('users', '[]'), true);

            // Create user lookup maps (by name and email)
            $userMapByName = [];
            $userMapByEmail = [];
            foreach ($users as $user) {
                $userMapByName[strtolower(trim($user['name']))] = $user['id'];
                if (!empty($user['email'])) {
                    $userMapByEmail[strtolower(trim($user['email']))] = $user['id'];
                }
            }

            $data = [];

            if (in_array($file->getClientOriginalExtension(), ['csv', 'xls'])) {
                // Handle CSV and tab-separated files
                $handle = fopen($file->getPathname(), 'r');
                if ($handle) {
                    // Detect delimiter
                    $firstLine = fgets($handle);
                    $delimiter = $this->detectDelimiter($firstLine);
                    rewind($handle);

                    $headers = fgetcsv($handle, 1000, $delimiter);
                    $rowNumber = 1;

                    while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                        $rowNumber++;

                        if (empty(array_filter($row))) {
                            continue; // Skip empty rows
                        }

                        $rowData = $this->parseRow($row, $headers, $userMapByName, $userMapByEmail);
                        if ($rowData) {
                            $data[] = $rowData;
                        }
                    }

                    fclose($handle);
                }
            } else {
                // Handle Excel (xlsx)
                $spreadsheet = IOFactory::load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();

                \Log::info('Excel rows loaded', ['total_rows' => count($rows)]);

                if (!empty($rows)) {
                    // Find the header row (contains 'Tipe', 'Nama Karyawan', etc.)
                    $headerRowIndex = null;
                    foreach ($rows as $index => $row) {
                        $rowString = implode('', array_map('strval', $row));
                        if (stripos($rowString, 'Tipe') !== false && stripos($rowString, 'Nama Karyawan') !== false) {
                            $headerRowIndex = $index;
                            break;
                        }
                    }

                    if ($headerRowIndex === null) {
                        \Log::error('Header row not found', ['rows' => array_slice($rows, 0, 5)]);
                        return response()->json([
                            'success' => false,
                            'message' => 'Header tidak ditemukan. Pastikan file memiliki kolom: Tipe, Nama Karyawan, Nama Penerima, Jumlah Honor, Nomor Rekening, Deskripsi',
                        ], 400);
                    }

                    $headers = $rows[$headerRowIndex];
                    \Log::info('Headers found', ['headers' => $headers, 'row_index' => $headerRowIndex]);

                    // Process data rows (after header)
                    $dataRows = array_slice($rows, $headerRowIndex + 1);
                    foreach ($dataRows as $rowNumber => $row) {
                        if (empty(array_filter($row))) {
                            continue; // Skip empty rows
                        }

                        $rowData = $this->parseRow($row, $headers, $userMapByName, $userMapByEmail);
                        if ($rowData) {
                            $data[] = $rowData;
                        }
                    }

                    \Log::info('Data processed', ['valid_rows' => count($data)]);
                }
            }

            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid data found in file. Please check the format.',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Successfully imported ' . count($data) . ' recipients.',
            ]);

        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process file: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function parseRow($row, $headers, $userMapByName, $userMapByEmail)
    {
        // Handle case where row and headers don't match
        if (count($headers) !== count($row)) {
            // Pad row with empty strings
            $row = array_pad($row, count($headers), '');
        }

        $rowData = array_combine($headers, $row);

        // Clean data - remove BOM and trim
        $rowData = array_map(function($value) {
            return preg_replace('/[\x00-\x1F\x7F]/u', '', trim($value ?? ''));
        }, $rowData);

        $tipe = strtolower($rowData['Tipe'] ?? '');
        $namaKaryawan = $rowData['Nama Karyawan'] ?? '';
        $namaPenerima = $rowData['Nama Penerima'] ?? '';
        $jumlahHonor = preg_replace('/[^0-9]/', '', $rowData['Jumlah Honor'] ?? '0');
        $nomorRekening = $rowData['Nomor Rekening'] ?? '';
        $deskripsi = $rowData['Deskripsi'] ?? '';

        // Validate required fields
        if (empty($tipe) || empty($jumlahHonor) || empty($nomorRekening)) {
            return null; // Skip invalid rows
        }

        // Validate tipe
        if (!in_array($tipe, ['karyawan', 'non_karyawan'])) {
            return null;
        }

        // Process based on tipe
        $penerimaManfaatType = $tipe;
        $penerimaManfaatId = null;
        $penerimaManfaatName = null;

        if ($tipe === 'karyawan') {
            // Find user by name or email
            if (!empty($namaKaryawan)) {
                $input = strtolower(trim($namaKaryawan));

                // Check if input is an email
                if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                    // Search by email
                    $penerimaManfaatId = $userMapByEmail[$input] ?? null;
                } else {
                    // Search by name
                    $penerimaManfaatId = $userMapByName[$input] ?? null;
                }
            }
        } else {
            // Non-karyawan
            $penerimaManfaatName = $namaPenerima;
        }

        return [
            'penerima_manfaat_type' => $penerimaManfaatType,
            'penerima_manfaat_id' => $penerimaManfaatId,
            'penerima_manfaat_name' => $penerimaManfaatName,
            'jumlah_honor' => floatval($jumlahHonor),
            'nomor_rekening' => $nomorRekening,
            'deskripsi' => $deskripsi,
        ];
    }

    private function detectDelimiter($line)
    {
        // Count occurrences of common delimiters
        $tabCount = substr_count($line, "\t");
        $commaCount = substr_count($line, ",");
        $semicolonCount = substr_count($line, ";");

        // Return the delimiter with most occurrences
        if ($tabCount > $commaCount && $tabCount > $semicolonCount) {
            return "\t";
        } elseif ($commaCount > $semicolonCount) {
            return ",";
        } elseif ($semicolonCount > 0) {
            return ";";
        }

        // Default to tab for our template
        return "\t";
    }
}
