const { test, expect } = require('@playwright/test');

test.describe('Dashboard', () => {
  test('should display dashboard after login', async ({ page }) => {
    // Login
    await page.goto('/login');
    await page.waitForLoadState('domcontentloaded');

    const emailInput = page.locator('input[name="email"]').first();
    const passwordInput = page.locator('input[name="password"]').first();
    const submitButton = page.locator('button[type="submit"]').first();

    await expect(emailInput).toBeVisible({ timeout: 10000 });
    await emailInput.fill('superadmin@example.com');
    await passwordInput.fill('password');
    await submitButton.click();

    // Should redirect to dashboard
    await page.waitForURL('**/dashboard', { timeout: 15000 });
    await expect(page).toHaveURL(/.*dashboard/);
  });
});
