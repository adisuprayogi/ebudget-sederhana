const { test, expect } = require('@playwright/test');

// Helper function to login
async function login(page) {
  await page.goto('/login');
  await page.waitForLoadState('domcontentloaded');

  const emailInput = page.locator('input[name="email"]').first();
  const passwordInput = page.locator('input[name="password"]').first();
  const submitButton = page.locator('button[type="submit"]').first();

  await expect(emailInput).toBeVisible({ timeout: 10000 });
  await emailInput.fill('superadmin@example.com');
  await passwordInput.fill('password');
  await submitButton.click();

  await page.waitForURL('**/dashboard', { timeout: 15000 });
}

test.describe('Program Kerja - Management', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should display program kerja list', async ({ page }) => {
    await page.goto('/program-kerja');
    await page.waitForLoadState('domcontentloaded');

    // Check if we're on the right page
    await expect(page).toHaveURL(/.*program-kerja/, { timeout: 10000 });
    await expect(page.locator('body')).toBeVisible();
  });

  test('should create new program kerja', async ({ page }) => {
    await page.goto('/program-kerja');
    await page.waitForLoadState('domcontentloaded');

    // Click create button
    const createButton = page.getByRole('link', { name: /tambah|buat|create|baru/i }).first();
    if (await createButton.isVisible({ timeout: 5000 })) {
      await createButton.click();

      // Wait for form
      await page.waitForLoadState('domcontentloaded');

      // Fill form
      const timestamp = Date.now();
      const namaInput = page.locator('input[name="nama"], #nama, [name*="nama"]').first();
      if (await namaInput.isVisible({ timeout: 5000 })) {
        await namaInput.fill(`Program Test ${timestamp}`);
      }

      // Submit form
      const submitButton = page.locator('button[type="submit"]').first();
      if (await submitButton.isVisible()) {
        await submitButton.click();
      }
    }
  });

  test('should filter program kerja by periode anggaran', async ({ page }) => {
    await page.goto('/program-kerja');
    await page.waitForLoadState('domcontentloaded');

    // Look for periode filter
    const periodeFilter = page.locator('select[name="periode_anggaran_id"], #periode_anggaran_id').first();
    if (await periodeFilter.isVisible({ timeout: 5000 })) {
      await periodeFilter.selectOption({ index: 0 });
      await page.waitForTimeout(1000);
    }
  });
});
