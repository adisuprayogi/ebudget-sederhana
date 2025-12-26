# Panduan Instalasi E-Budget dengan Laravel dan Alpine.js

## Prerequisites

- PHP 8.1+
- MySQL/MariaDB 10.3+
- Composer
- Node.js 18+
- NPM/Yarn

## Langkah 1: Setup Laravel Project

```bash
# Buat project baru
composer create-project laravel/laravel ebudget-sederhana
cd ebudget-sederhana

# Copy .env
cp .env.example .env
# Generate application key
php artisan key:generate
```

## Langkah 2: Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebudget
DB_USERNAME=root
DB_PASSWORD=

# Untuk MariaDB
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebudget_maria
DB_USERNAME=root
DB_PASSWORD=
```

## Langkah 3: Install Dependencies

### Install Composer Dependencies

```bash
# Install Laravel dependencies
composer install

# Install packages for Excel export
composer require maatwebsite/excel

# Install Laravel Debugbar (untuk development)
composer require barryvdh/laravel-debugbar --dev
```

### Install Node.js Dependencies

```bash
# Install Alpine.js dan dependencies
npm install

# Install Alpine.js core
npm install alpinejs

# Install Alpine.js plugins
npm install @alpinejs/persist
npm install @alpinejs/mask
npm install @alpinejs/anchor
npm install @alpinejs/collapse
npm install @alpinejs/focus

# Install TailwindCSS
npm install -D tailwindcss postcss autoprefixer

# Install Tailwind plugins
npm install -D @tailwindcss/forms
npm install -D @tailwindcss/typography

# Install additional utilities
npm install date-fns
npm install @heroicons/vue
```

## Langkah 4: Konfigurasi Vite

Edit file `vite.config.js`:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
```

## Langkah 5: Konfigurasi TailwindCSS

```bash
# Inisialisasi TailwindCSS
npx tailwindcss init -p
```

Edit file `tailwind.config.js`:

```javascript
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
```

## Langkah 6: Setup Alpine.js

Buat file `resources/js/app.js`:

```javascript
import Alpine from 'alpinejs'

// Import Alpine plugins
import persist from '@alpinejs/persist'
import mask from '@alpinejs/mask'
import anchor from '@alpinejs/anchor'
import collapse from '@alpinejs/collapse'
import focus from '@alpinejs/focus'

// Import components
import './components/modal'
import './components/dropdown'
import './components/datatable'
import './components/form-pengajuan'

// Register plugins
Alpine.plugin(persist)
Alpine.plugin(mask)
Alpine.plugin(anchor)
Alpine.plugin(collapse)
Alpine.plugin(focus)

// Global data
Alpine.data('app', () => ({
    user: null,

    init() {
        this.getUser()
    },

    getUser() {
        // Fetch user data if needed
    },

    formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(value)
    },

    formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID')
    }
}))

// Start Alpine
window.Alpine = Alpine
Alpine.start()
```

## Langkah 7: Setup CSS

Edit file `resources/css/app.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom styles */
@layer base {
    html {
        font-family: 'Inter', sans-serif;
    }
}

@layer components {
    .btn {
        @apply px-4 py-2 rounded-md font-medium transition-colors duration-200;
    }

    .btn-primary {
        @apply bg-blue-600 text-white hover:bg-blue-700;
    }

    .btn-secondary {
        @apply bg-gray-200 text-gray-900 hover:bg-gray-300;
    }
}
```

## Langkah 8: Setup Database

```bash
# Buat database di MySQL/MariaDB
mysql -u root -p
CREATE DATABASE ebudget;
EXIT;

# Jalankan migration
php artisan migrate

# Seed data awal
php artisan db:seed
```

## Langkah 9: Konfigurasi Autoloader

Tambahkan ke `composer.json`:

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        },
        "files": [
            "app/Helpers/helpers.php"
        ]
    }
}
```

Jalankan:

```bash
composer dump-autoload
```

## Langkah 10: Setup Storage

```bash
# Buat symbolic link
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## Langkah 11: Development

```bash
# Terminal 1: Start Laravel
php artisan serve

# Terminal 2: Build assets
npm run dev
```

## Langkah 12: Build for Production

```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build
```

## Troubleshooting

### 1. Alpine.js tidak terdeteksi

Pastikan Alpine.js di-load sebelum Alpine components:

```html
<body x-data="app">
    <!-- Content here -->

    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
```

### 2. TailwindCSS tidak apply

Pastikan Vite configuration benar dan jalankan:

```bash
npm run build
```

### 3. Database connection failed

Cek konfigurasi `.env` dan pastikan:

- Database sudah dibuat
- Credentials benar
- MySQL/MariaDB service running

### 4. Permission issues (Linux)

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## Development Tips

### 1. Hot Module Replacement

Untuk development dengan HMR:

```bash
npm run dev
```

### 2. Debug Mode

Aktifkan debug mode di `.env`:

```env
APP_DEBUG=true
```

### 3. Laravel Telescope (Optional)

Untuk debugging:

```bash
composer require laravel/telescope
php artisan telescope:install
php artisan migrate
```

### 4. Laravel Debugbar

Sudah terinstall di development:

```php
// config/app.php
'providers' => [
    Barryvdh\Debugbar\ServiceProvider::class,
],
```

## Production Deployment Checklist

1. Set `APP_ENV=production` dan `APP_DEBUG=false` di `.env`
2. Optimize composer: `composer install --optimize-autoloader --no-dev`
3. Cache Laravel: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
4. Build assets: `npm run build`
5. Set correct permissions
6. Configure web server (Nginx/Apache)
7. Setup SSL certificate
8. Configure cron jobs
9. Setup monitoring and backups