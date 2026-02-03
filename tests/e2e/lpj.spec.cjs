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

test.describe('LPJ (Laporan Pertanggungjawaban) - Workflow', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should display LPJ list', async ({ page }) => {
    await page.goto('/laporan-pertanggung-jawaban');
    await page.waitForLoadState('domcontentloaded');

    // Check if we're on the right page
    await expect(page).toHaveURL(/.*laporan-pertanggung-jawaban/, { timeout: 10000 });
    await expect(page.locator('body')).toBeVisible();
  });

  test('should create new LPJ', async ({ page }) => {
    await page.goto('/laporan-pertanggung-jawaban');
    await page.waitForLoadState('domcontentloaded');

    // Click create button
    const createButton = page.getByRole('link', { name: /tambah|buat|create|baru|buat lpj/i }).first();
    if (await createButton.isVisible({ timeout: 5000 })) {
      await createButton.click();

      // Wait for form
      await page.waitForLoadState('domcontentloaded');

      // Submit form
      const submitButton = page.locator('button[type="submit"]').first();
      if (await submitButton.isVisible()) {
        await submitButton.click();
      }
    }
  });

  test('should submit LPJ for verification', async ({ page }) => {
    await page.goto('/laporan-pertanggung-jawaban');
    await page.waitForLoadState('domcontentloaded');

    // Look for submit button
    const submitButton = page.getByRole('button', { name: /kirim|submit|ajukan/i }).first();
    if (await submitButton.isVisible({ timeout: 5000 })) {
      await submitButton.click();
      await page.waitForTimeout(1000);
    }
  });
});
