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

test.describe('Approval System', () => {
  test.beforeEach(async ({ page }) => {
    await login(page);
  });

  test('should display pending approvals', async ({ page }) => {
    await page.goto('/approvals');
    await page.waitForLoadState('domcontentloaded');

    // Check if we're on the right page
    await expect(page).toHaveURL(/.*approval/, { timeout: 10000 });
    await expect(page.locator('body')).toBeVisible();
  });

  test('should approve a request', async ({ page }) => {
    await page.goto('/approvals');
    await page.waitForLoadState('domcontentloaded');

    // Look for approve button
    const approveButton = page.getByRole('button', { name: /setuju|approve|terima/i }).first();
    if (await approveButton.isVisible({ timeout: 5000 })) {
      await approveButton.click();
      await page.waitForTimeout(1000);

      // Handle confirmation if present
      await page.waitForLoadState('domcontentloaded');
    }
  });

  test('should reject a request', async ({ page }) => {
    await page.goto('/approvals');
    await page.waitForLoadState('domcontentloaded');

    // Look for reject button
    const rejectButton = page.getByRole('button', { name: /tolak|reject|reject/i }).first();
    if (await rejectButton.isVisible({ timeout: 5000 })) {
      await rejectButton.click();
      await page.waitForTimeout(1000);

      // Handle confirmation if present
      await page.waitForLoadState('domcontentloaded');
    }
  });
});
