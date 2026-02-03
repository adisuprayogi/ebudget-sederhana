const { test } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const roles = [
  { id: 'superadmin', name: 'Superadmin', file: 'MANUAL-SUPERADMIN.md' },
  { id: 'direktur_utama', name: 'Direktur Utama', file: 'MANUAL-DIREKTUR-UTAMA.md' },
  { id: 'direktur_keuangan', name: 'Direktur Keuangan', file: 'MANUAL-DIREKTUR-KEUANGAN.md' },
  { id: 'kepala_divisi', name: 'Kepala Divisi', file: 'MANUAL-KEPALA-DIVISI.md' },
  { id: 'staff_divisi', name: 'Staff Divisi', file: 'MANUAL-STAFF-DIVISI.md' },
  { id: 'staff_keuangan', name: 'Staff Keuangan', file: 'MANUAL-STAFF-KEUANGAN.md' },
];

function markdownToHtml(content, screenshotsDir, roleId) {
  const screenshots = fs.existsSync(screenshotsDir)
    ? fs.readdirSync(screenshotsDir).filter(f => f.endsWith('.png'))
    : [];

  return `
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      line-height: 1.6;
      color: #1a1a1a;
      max-width: 900px;
      margin: 0 auto;
      padding: 40px 20px;
      background: white;
    }

    h1 {
      font-size: 32px;
      font-weight: 700;
      color: #2563eb;
      margin-top: 40px;
      margin-bottom: 20px;
      page-break-after: avoid;
      border-bottom: 3px solid #2563eb;
      padding-bottom: 10px;
    }

    h1:first-child {
      margin-top: 0;
      text-align: center;
      color: #1e40af;
    }

    h2 {
      font-size: 24px;
      font-weight: 600;
      color: #1e40af;
      margin-top: 30px;
      margin-bottom: 15px;
      page-break-after: avoid;
    }

    h3 {
      font-size: 20px;
      font-weight: 600;
      color: #374151;
      margin-top: 25px;
      margin-bottom: 12px;
      page-break-after: avoid;
    }

    h4 {
      font-size: 16px;
      font-weight: 600;
      color: #4b5563;
      margin-top: 20px;
      margin-bottom: 10px;
    }

    p {
      margin-bottom: 12px;
    }

    a {
      color: #2563eb;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    code {
      background: #f3f4f6;
      padding: 2px 6px;
      border-radius: 4px;
      font-family: 'Monaco', 'Courier New', monospace;
      font-size: 14px;
    }

    pre {
      background: #1f2937;
      color: #f3f4f6;
      padding: 15px;
      border-radius: 8px;
      overflow-x: auto;
      margin: 15px 0;
      page-break-inside: avoid;
    }

    pre code {
      background: none;
      padding: 0;
      color: inherit;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      page-break-inside: avoid;
    }

    th, td {
      border: 1px solid #d1d5db;
      padding: 10px 12px;
      text-align: left;
    }

    th {
      background: #f3f4f6;
      font-weight: 600;
      color: #1f2937;
    }

    ul, ol {
      margin-left: 25px;
      margin-bottom: 15px;
    }

    li {
      margin-bottom: 6px;
    }

    blockquote {
      border-left: 4px solid #2563eb;
      padding-left: 15px;
      margin: 15px 0;
      color: #6b7280;
      font-style: italic;
    }

    hr {
      border: none;
      border-top: 2px solid #e5e7eb;
      margin: 30px 0;
    }

    .screenshot {
      text-align: center;
      margin: 25px 0;
      page-break-inside: avoid;
    }

    .screenshot img {
      max-width: 100%;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .screenshot-caption {
      font-size: 14px;
      color: #6b7280;
      margin-top: 8px;
      font-style: italic;
    }

    .status-badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
      margin: 0 4px;
    }

    .status-draft { background: #e5e7eb; color: #374151; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-approved { background: #d1fae5; color: #065f46; }
    .status-rejected { background: #fee2e2; color: #991b1b; }

    .info-box {
      background: #eff6ff;
      border-left: 4px solid #3b82f6;
      padding: 15px;
      margin: 15px 0;
      border-radius: 0 8px 8px 0;
    }

    .warning-box {
      background: #fef3c7;
      border-left: 4px solid #f59e0b;
      padding: 15px;
      margin: 15px 0;
      border-radius: 0 8px 8px 0;
    }

    .success-box {
      background: #d1fae5;
      border-left: 4px solid #10b981;
      padding: 15px;
      margin: 15px 0;
      border-radius: 0 8px 8px 0;
    }

    .page-break {
      page-break-after: always;
    }

    .toc {
      background: #f9fafb;
      padding: 20px;
      border-radius: 8px;
      margin: 20px 0;
      page-break-after: avoid;
    }

    .toc h2 {
      margin-top: 0;
      color: #1e40af;
      border-bottom: 1px solid #e5e7eb;
      padding-bottom: 10px;
    }

    .toc ul {
      list-style: none;
      margin-left: 0;
    }

    .toc li a {
      display: block;
      padding: 6px 0;
      color: #374151;
    }

    .toc li a:hover {
      color: #2563eb;
    }

    .checklist {
      list-style: none;
      margin-left: 0;
    }

    .checklist li {
      padding-left: 25px;
      position: relative;
    }

    .checklist li:before {
      content: '[ ]';
      position: absolute;
      left: 0;
      font-family: monospace;
      color: #6b7280;
    }
  </style>
</head>
<body>
  ${content
    .replace(/^### (.*$)/gim, '<h3>$1</h3>')
    .replace(/^## (.*$)/gim, '<h2>$1</h2>')
    .replace(/^# (.*$)/gim, '<h1>$1</h1>')
    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
    .replace(/\*(.*?)\*/g, '<em>$1</em>')
    .replace(/`([^`]+)`/g, '<code>$1</code>')
    .replace(/!\[([^\]]*)\]\(\.\.\/\.\.\/screenshots\/roles\/([^\)]+)\)/g, (match, alt, filename) => {
      const screenshotPath = path.join(screenshotsDir, filename);
      if (fs.existsSync(screenshotPath)) {
        const base64Image = fs.readFileSync(screenshotPath, 'base64');
        return `<div class="screenshot">
          <img src="data:image/png;base64,${base64Image}" alt="${alt}">
          <p class="screenshot-caption">${alt}</p>
        </div>`;
      }
      return '';
    })
    .replace(/^\[ \]/gm, '<input type="checkbox" disabled>')
    .replace(/^\[x\]/gm, '<input type="checkbox" checked disabled>')
    .replace(/^---$/gm, '<hr>')
    .replace(/^- (.*)$/gm, '<li>$1</li>')
    .replace(/(<li>.*<\/li>\n?)+/g, '<ul>$&</ul>')
    .replace(/^\d+\. (.*)$/gm, '<li>$1</li>')
    .replace(/\n\n/g, '</p><p>')
    .replace(/\n/g, '<br>')
  }

  <div class="page-break"></div>

  <div style="text-align: center; margin-top: 50px; color: #6b7280; font-size: 14px;">
    <p>---</p>
    <p><strong>eBudget Sederhana</strong><br>
    Sistem Manajemen Anggaran Terintegrasi</p>
    <p style="margin-top: 20px;">
      Dokumen ini dibuat pada Februari 2026<br>
      Versi: 1.0.0
    </p>
  </div>
</body>
</html>
  `;
}

for (const role of roles) {
  test(`Generate PDF Manual - ${role.name}`, async ({ page }) => {
    const manualPath = path.join(__dirname, `../../docs/roles/${role.file}`);
    let content = fs.readFileSync(manualPath, 'utf8');

    const screenshotsDir = path.join(__dirname, '../../screenshots/roles');

    const htmlContent = markdownToHtml(content, screenshotsDir, role.id);

    await page.setViewportSize({ width: 900, height: 1200 });
    await page.setContent(htmlContent, { waitUntil: 'networkidle' });

    await page.pdf({
      path: `docs/roles/MANUAL-${role.id.toUpperCase().replace('_', '-')}.pdf`,
      format: 'A4',
      printBackground: true,
      margin: {
        top: '20mm',
        right: '15mm',
        bottom: '20mm',
        left: '15mm',
      },
      displayHeaderFooter: true,
      headerTemplate: `
        <div style="font-size: 10px; color: #6b7280; padding: 10px 15px; width: 100%;">
          <span style="float: left;">eBudget Sederhana - Manual ${role.name}</span>
          <span style="float: right;">Halaman <span class="pageNumber"></span> dari <span class="totalPages"></span></span>
        </div>
      `,
      footerTemplate: `
        <div style="font-size: 9px; color: #9ca3af; padding: 10px 15px; text-align: center; width: 100%;">
          © 2026 eBudget Sederhana - Semua Hak Dilindungi
        </div>
      `,
    });

    console.log(`✅ PDF generated: docs/roles/MANUAL-${role.id.toUpperCase().replace('_', '-')}.pdf`);
  });
}
