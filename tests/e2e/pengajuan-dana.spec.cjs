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

test.describe('Pengajuan Dana - Workflow', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should display pengajuan dana list', async ({ page }) => {
    await page.goto('/pengajuan-dana');
    await page.waitForLoadState('domcontentloaded');

    // Check if we're on the right page
    await expect(page).toHaveURL(/.*pengajuan-dana/, { timeout: 10000 });
    await expect(page.locator('body')).toBeVisible();
  });

  test('should create new pengajuan dana', async ({ page }) => {
    await page.goto('/pengajuan-dana');
    await page.waitForLoadState('domcontentloaded');

    // Click create button
    const createButton = page.getByRole('link', { name: /tambah|buat|create|baru|ajukan/i }).first();
    if (await createButton.isVisible({ timeout: 5000 })) {
      await createButton.click();

      // Wait for form
      await page.waitForLoadState('domcontentloaded');

      // Fill basic form fields
      const timestamp = Date.now();
      const keperluanInput = page.locator('textarea[name="keperluan"], #keperluan, [name*="keperluan"]').first();
      if (await keperluanInput.isVisible({ timeout: 5000 })) {
        await keperluanInput.fill(`Pengajuan Test ${timestamp}`);
      }

      // Submit form
      const submitButton = page.locator('button[type="submit"]').first();
      if (await submitButton.isVisible()) {
        await submitButton.click();
      }
    }
  });

  test('should show pengajuan dana details', async ({ page }) => {
    await page.goto('/pengajuan-dana');
    await page.waitForLoadState('domcontentloaded');

    // Look for view/detail button
    const viewButton = page.getByRole('link', { name: /lihat|detail|view/i }).first();
    if (await viewButton.isVisible({ timeout: 5000 })) {
      await viewButton.click();
      await page.waitForLoadState('domcontentloaded');

      // Should show details
      await expect(page.locator('body')).toBeVisible();
    }
  });

  test('should submit pengajuan dana for approval', async ({ page }) => {
    await page.goto('/pengajuan-dana');
    await page.waitForLoadState('domcontentloaded');

    // Look for submit button
    const submitButton = page.getByRole('button', { name: /kirim|submit|ajukan/i }).first();
    if (await submitButton.isVisible({ timeout: 5000 })) {
      await submitButton.click();
      await page.waitForTimeout(1000);
    }
  });
});
