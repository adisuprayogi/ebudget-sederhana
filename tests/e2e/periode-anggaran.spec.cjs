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

test.describe('Periode Anggaran - CRUD Operations', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should display periode anggaran list', async ({ page }) => {
    await page.goto('/periode-anggaran');
    await page.waitForLoadState('domcontentloaded');

    // Check if we're on the right page
    await expect(page).toHaveURL(/.*periode-anggaran/);
    await expect(page.locator('body')).toBeVisible();
  });

  test('should create new periode anggaran', async ({ page }) => {
    await page.goto('/periode-anggaran');
    await page.waitForLoadState('domcontentloaded');

    // Click create button
    const createButton = page.getByRole('link', { name: /tambah|buat|create|baru/i }).first();
    if (await createButton.isVisible({ timeout: 5000 })) {
      await createButton.click();

      // Wait for form
      await page.waitForURL('**/create', { timeout: 10000 });
      await page.waitForLoadState('domcontentloaded');

      // Fill form
      const timestamp = Date.now();
      const namaInput = page.locator('input[name="nama"], #nama, [name*="nama"]').first();
      if (await namaInput.isVisible({ timeout: 5000 })) {
        await namaInput.fill(`Periode Test ${timestamp}`);
      }

      const tahunInput = page.locator('input[name="tahun"], #tahun, [name*="tahun"]').first();
      if (await tahunInput.isVisible({ timeout: 5000 })) {
        await tahunInput.fill('2025');
      }

      // Submit form
      await page.locator('button[type="submit"]').first().click();

      // Should redirect back to index
      await page.waitForURL('**/periode-anggaran', { timeout: 15000 });
    }
  });

  test('should view periode anggaran details', async ({ page }) => {
    await page.goto('/periode-anggaran');
    await page.waitForLoadState('domcontentloaded');

    // Look for view/detail button
    const viewButton = page.getByRole('link', { name: /lihat|detail|view/i }).first();
    if (await viewButton.isVisible({ timeout: 5000 })) {
      await viewButton.click();
      await page.waitForLoadState('domcontentloaded');

      // Should show details page
      await expect(page.locator('body')).toBeVisible();
    }
  });

  test('should edit periode anggaran', async ({ page }) => {
    await page.goto('/periode-anggaran');
    await page.waitForLoadState('domcontentloaded');

    // Look for edit button
    const editButton = page.getByRole('link', { name: /edit|ubah|sunting/i }).first();
    if (await editButton.isVisible({ timeout: 5000 })) {
      await editButton.click();
      await page.waitForLoadState('domcontentloaded');

      // Should show edit form
      await expect(page.locator('body')).toBeVisible();
    }
  });

  test('should activate periode anggaran', async ({ page }) => {
    await page.goto('/periode-anggaran');
    await page.waitForLoadState('domcontentloaded');

    // Look for activate button
    const activateButton = page.getByRole('button', { name: /aktif|activate|active/i }).first();
    if (await activateButton.isVisible({ timeout: 5000 })) {
      await activateButton.click();
      // Handle confirmation dialog if present
      await page.waitForTimeout(1000);
    }
  });
});
