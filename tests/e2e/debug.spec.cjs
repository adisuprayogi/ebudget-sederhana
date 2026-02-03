const { test, expect } = require('@playwright/test');

test('Debug: Check login page HTML', async ({ page }) => {
  await page.goto('/login');
  await page.waitForLoadState('domcontentloaded');

  // Get page content
  const content = await page.content();
  console.log('Page HTML length:', content.length);

  // Check if there's any form
  const forms = await page.locator('form').count();
  console.log('Number of forms:', forms);

  // Check title
  const title = await page.title();
  console.log('Page title:', title);

  // Check URL
  const url = page.url();
  console.log('Current URL:', url);

  // Get body text
  const bodyText = await page.locator('body').textContent();
  console.log('Body text preview:', bodyText?.substring(0, 200));

  // Check for any JavaScript errors
  page.on('pageerror', (error) => {
    console.log('JavaScript error:', error.message);
  });

  // Wait a bit for any dynamic content
  await page.waitForTimeout(2000);

  // Check again after delay
  const inputsAfterDelay = await page.locator('input').count();
  console.log('Number of input elements after delay:', inputsAfterDelay);

  // Check for Alpine.js components
  const hasAlpine = await page.evaluate(() => {
    return typeof window.Alpine !== 'undefined';
  });
  console.log('Has Alpine.js:', hasAlpine);
});
