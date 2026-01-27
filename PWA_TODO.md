# Todo List: Membuat eBudget Menjadi PWA (Progressive Web App)

## 1. Persiapan & Planning
- [ ] Cek struktur aplikasi dan aset yang ada
- [ ] Siapkan icon aplikasi (logo) dalam berbagai ukuran:
  - 192x192px (maskable icon)
  - 512x512px (regular icon)
- [ ] Tentukan nama aplikasi: "EBudget Tazkia"
- [ ] Tentukan warna tema aplikasi (biru/indigo)

## 2. Manifest File
- [ ] Buat file `public/manifest.json` berisi:
  - Nama aplikasi
  - Deskripsi singkat
  - Icon path
  - Start URL
  - Display mode (standalone)
  - Theme color
  - Background color
  - Orientation (portrait)

## 3. Service Worker Setup
- [ ] Buat file `public/service-worker.js`
- [ ] Implementasi caching strategy:
  - Cache first untuk asset statis (CSS, JS, gambar)
  - Network first untuk API calls
  - Offline fallback page
- [ ] Implementasi auto-update untuk cache
- [ ] Register service worker di aplikasi

## 4. Update HTML/Meta Tags
- [ ] Tambahkan link ke `manifest.json` di `<head>`
- [ ] Tambahkan meta tags PWA:
  - `theme-color`
  - `mobile-web-app-capable`
  - `apple-mobile-web-app-status-bar-style`
  - `apple-mobile-web-app-title`
  - `apple-touch-icon` links

## 5. Layout/App Wrapper Update
- [ ] Tambahkan script register service worker
- [ ] Tambahkan UI "Install App" untuk prompt instalasi (opsional)
- [ ] Handle update available event

## 6. Icon & Image Assets
- [ ] Siapkan file icon di `public/icons/`:
  - `icon-192x192.png` (maskable)
  - `icon-512x512.png` (regular)
  - `favicon.ico`
- [ ] Siapkan splash screen image (opsional)

## 7. Testing & Validation
- [ ] Test di Chrome DevTools (Lighthouse → PWA)
- [ ] Test instalasi di Android (Chrome)
- [ ] Test instalasi di iOS (Safari)
- [ ] Test offline functionality
- [ ] Test update mechanism

## 8. Deploy Considerations
- [ ] Pastikan HTTPS aktif (PWA butuh HTTPS)
- [ ] Service worker path harus benar di production
