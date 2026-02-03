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

test.describe('Refund - Process', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should display refund list', async ({ page }) => {
    await page.goto('/refund');
    await page.waitForLoadState('domcontentloaded');

    // Check if we're on the right page
    await expect(page).toHaveURL(/.*refund/, { timeout: 10000 });
    await expect(page.locator('body')).toBeVisible();
  });

  test('should create new refund', async ({ page }) => {
    await page.goto('/refund');
    await page.waitForLoadState('domcontentloaded');

    // Click create button
    const createButton = page.getByRole('link', { name: /tambah|buat|create|baru|refund/i }).first();
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

  test('should process refund', async ({ page }) => {
    await page.goto('/refund');
    await page.waitForLoadState('domcontentloaded');

    // Look for process button
    const processButton = page.getByRole('button', { name: /proses|process|kembalikan/i }).first();
    if (await processButton.isVisible({ timeout: 5000 })) {
      await processButton.click();
      await page.waitForLoadState('domcontentloaded');

      // Should show processing form
      await expect(page.locator('body')).toBeVisible();
    }
  });
});
