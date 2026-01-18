#!/bin/bash

# ============================================
# SCRIPT PREPARASI DEPLOY KE CYBERPANEL
# ============================================
# Script ini akan mempersiapkan file-file
# yang akan diupload ke CyberPanel

echo "========================================="
echo "  E-Budget Deploy Preparation Script"
echo "========================================="
echo ""

# Warna untuk output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Fungsi untuk print warning
print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Fungsi untuk print success
print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

# Fungsi untuk print error
print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Cek apakah composer tersedia
echo "Step 1: Checking dependencies..."
if ! command -v composer &> /dev/null; then
    print_error "Composer tidak ditemukan. Silakan install composer terlebih dahulu."
    exit 1
fi
print_success "Composer found"

# Cek apakah php tersedia
if ! command -v php &> /dev/null; then
    print_error "PHP tidak ditemukan. Silakan install PHP terlebih dahulu."
    exit 1
fi
print_success "PHP found"

echo ""
echo "Step 2: Installing production dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

if [ $? -eq 0 ]; then
    print_success "Dependencies installed"
else
    print_error "Failed to install dependencies"
    exit 1
fi

echo ""
echo "Step 3: Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_success "Configuration cached"

echo ""
echo "Step 4: Creating deployment zip..."
ZIP_NAME="ebudget-deploy-$(date +%Y%m%d-%H%M%S).zip"

# Buat file zip untuk upload
# Exclude: node_modules, .git, vendor (sudah terinstall), tests
zip -r "$ZIP_NAME" \
    app/ \
    bootstrap/ \
    config/ \
    database/ \
    public/ \
    resources/ \
    routes/ \
    storage/ \
    vendor/ \
    .env.production \
    composer.json \
    composer.lock \
    artisan \
    -x "node_modules/*" \
    -x ".git/*" \
    -x "tests/*" \
    -x ".env.local" \
    -x ".env.backup" \
    -x "*.log"

if [ $? -eq 0 ]; then
    print_success "Deployment zip created: $ZIP_NAME"
    print_success "Ukuran file: $(du -h "$ZIP_NAME" | cut -f1)"
else
    print_error "Failed to create deployment zip"
    exit 1
fi

echo ""
echo "========================================="
echo "  DEPLOYMENT PREPARATION COMPLETE"
echo "========================================="
echo ""
echo "File yang sudah siap untuk diupload:"
echo "  📦 $ZIP_NAME"
echo ""
echo "Langkah selanjutnya:"
echo "  1. Upload file $ZIP_NAME ke CyberPanel File Manager"
echo "  2. Extract file di folder website"
echo "  3. Rename .env.production ke .env"
echo "  4. Edit konfigurasi database di .env"
echo "  5. Jalankan 'php artisan key:generate' di Terminal CyberPanel"
echo "  6. Jalankan 'php artisan migrate --force' di Terminal CyberPanel"
echo ""
echo "Lihat panduan lengkap di: DEPLOY-CYBERPANEL.md"
echo ""
