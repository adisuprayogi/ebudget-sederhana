const { test, expect } = require('@playwright/test');

// Users for each role
const users = {
  superadmin: { email: 'superadmin@example.com', name: 'Superadmin' },
  direktur_utama: { email: 'direktur.utama@example.com', name: 'Direktur Utama' },
  direktur_keuangan: { email: 'direktur@example.com', name: 'Direktur Keuangan' },
  kepala_divisi: { email: 'kepala.it@example.com', name: 'Kepala Divisi' },
  staff_divisi: { email: 'staff1.it@example.com', name: 'Staff Divisi' },
  staff_keuangan: { email: 'staff.keuangan@example.com', name: 'Staff Keuangan' },
};

// Helper function to login
async function login(page, email, context) {
  // Clear all cookies and storage
  await context.clearCookies();
  await page.goto('/login');
  await page.waitForLoadState('domcontentloaded', { timeout: 10000 });
  await page.waitForTimeout(500);

  const emailInput = page.locator('input[name="email"]').first();
  const passwordInput = page.locator('input[name="password"]').first();
  const submitButton = page.locator('button[type="submit"]').first();

  await expect(emailInput).toBeVisible({ timeout: 10000 });
  await emailInput.fill(email);
  await passwordInput.fill('password');
  await submitButton.click();

  await page.waitForURL('**/dashboard', { timeout: 15000 });
  await page.waitForLoadState('domcontentloaded');
  await page.waitForTimeout(1500);
}

test.describe('Screenshots for Role-Based Manuals', () => {
  test.use({ timeout: 300000 }); // 5 minutes total timeout

  test('capture screenshots for each role', async ({ page, context }) => {
    await page.setViewportSize({ width: 1400, height: 900 });

    // 1. SUPERADMIN
    await login(page, users.superadmin.email, context);
    await page.screenshot({ path: 'screenshots/roles/01-superadmin-dashboard.png', fullPage: true });

    // 2. DIREKTUR UTAMA (separate test)
  });

  test('capture direktur utama screenshots', async ({ page, context }) => {
    await page.setViewportSize({ width: 1400, height: 900 });
    await login(page, users.direktur_utama.email, context);
    await page.screenshot({ path: 'screenshots/roles/02-direktur-utama-dashboard.png', fullPage: true });
    await page.goto('/approvals');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/roles/02-direktur-utama-approvals.png', fullPage: true });
  });

  test('capture direktur keuangan screenshots', async ({ page, context }) => {
    await page.setViewportSize({ width: 1400, height: 900 });
    await login(page, users.direktur_keuangan.email, context);
    await page.screenshot({ path: 'screenshots/roles/03-direktur-keuangan-dashboard.png', fullPage: true });
    await page.goto('/periode-anggaran');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/roles/03-direktur-keuangan-periode-anggaran.png', fullPage: true });
    await page.goto('/penetapan-pagu');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/roles/03-direktur-keuangan-penetapan-pagu.png', fullPage: true });
  });

  test('capture kepala divisi screenshots', async ({ page, context }) => {
    await page.setViewportSize({ width: 1400, height: 900 });
    await login(page, users.kepala_divisi.email, context);
    await page.screenshot({ path: 'screenshots/roles/04-kepala-divisi-dashboard.png', fullPage: true });
    await page.goto('/program-kerja');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/roles/04-kepala-divisi-program-kerja.png', fullPage: true });
    await page.goto('/pengajuan-dana');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/roles/04-kepala-divisi-pengajuan-dana.png', fullPage: true });
    await page.goto('/lpj');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/roles/04-kepala-divisi-lpj.png', fullPage: true });
  });

  test('capture staff divisi screenshots', async ({ page, context }) => {
    await page.setViewportSize({ width: 1400, height: 900 });
    await login(page, users.staff_divisi.email, context);
    await page.screenshot({ path: 'screenshots/roles/05-staff-divisi-dashboard.png', fullPage: true });
    await page.goto('/pengajuan-dana');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/roles/05-staff-divisi-pengajuan-dana.png', fullPage: true });
    await page.goto('/lpj');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/roles/05-staff-divisi-lpj.png', fullPage: true });
  });

  test('capture staff keuangan screenshots', async ({ page, context }) => {
    await page.setViewportSize({ width: 1400, height: 900 });
    await login(page, users.staff_keuangan.email, context);
    await page.screenshot({ path: 'screenshots/roles/06-staff-keuangan-dashboard.png', fullPage: true });
    await page.goto('/pencairan-dana');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/roles/06-staff-keuangan-pencairan-dana.png', fullPage: true });
    await page.goto('/pencatatan-penerimaan');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/roles/06-staff-keuangan-pencatatan-penerimaan.png', fullPage: true });
  });
});
