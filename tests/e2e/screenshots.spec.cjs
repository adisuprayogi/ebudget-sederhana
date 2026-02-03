const { test } = require('@playwright/test');

// Helper function to login with staff_keuangan role
async function loginStaffKeuangan(page) {
  await page.goto('/login');
  await page.waitForLoadState('domcontentloaded');

  const emailInput = page.locator('input[name="email"]').first();
  const passwordInput = page.locator('input[name="password"]').first();
  const submitButton = page.locator('button[type="submit"]').first();

  await emailInput.fill('staff.keuangan@example.com');
  await passwordInput.fill('password');
  await submitButton.click();

  await page.waitForURL('**/dashboard', { timeout: 15000 });
  await page.waitForLoadState('domcontentloaded');
  await page.waitForTimeout(1000);
}

test.describe('Screenshots for Manual Book', () => {
  test('capture all screenshots', async ({ page }) => {
    // Set viewport for consistent screenshots
    await page.setViewportSize({ width: 1400, height: 900 });

    // 1. Login Page
    await page.goto('/login');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/manual/01-login-page.png', fullPage: true });

    // Login with staff keuangan (has access to more pages)
    await loginStaffKeuangan(page);

    // 2. Dashboard
    await page.screenshot({ path: 'screenshots/manual/02-dashboard.png', fullPage: true });

    // 3. Periode Anggaran - Index (staff keuangan has access)
    await page.goto('/periode-anggaran');
    await page.waitForLoadState('domcontentloaded');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'screenshots/manual/03-periode-anggaran-list.png', fullPage: true });

    // 4. Periode Anggaran - Create
    const createButton = page.getByRole('link', { name: /tambah|buat|create/i }).first();
    if (await createButton.isVisible({ timeout: 3000 })) {
      await createButton.click();
      await page.waitForLoadState('domcontentloaded');
      await page.waitForTimeout(1000);
      await page.screenshot({ path: 'screenshots/manual/04-periode-anggaran-create.png', fullPage: true });
    }

    // 5. Program Kerja - Index
    await page.goto('/program-kerja');
    await page.waitForLoadState('domcontentloaded');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'screenshots/manual/05-program-kerja-list.png', fullPage: true });

    // 6. Pengajuan Dana - Index
    await page.goto('/pengajuan-dana');
    await page.waitForLoadState('domcontentloaded');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'screenshots/manual/06-pengajuan-dana-list.png', fullPage: true });

    // 7. Pengajuan Dana - Create
    const createButton2 = page.getByRole('link', { name: /tambah|buat|create|ajukan/i }).first();
    if (await createButton2.isVisible({ timeout: 3000 })) {
      await createButton2.click();
      await page.waitForLoadState('domcontentloaded');
      await page.waitForTimeout(1000);
      await page.screenshot({ path: 'screenshots/manual/07-pengajuan-dana-create.png', fullPage: true });
    }

    // 8. Pencairan Dana - Index
    await page.goto('/pencairan-dana');
    await page.waitForLoadState('domcontentloaded');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'screenshots/manual/08-pencairan-dana-list.png', fullPage: true });

    // 9. LPJ - Index (using /lpj not /laporan-pertanggung-jawaban)
    await page.goto('/lpj');
    await page.waitForLoadState('domcontentloaded');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'screenshots/manual/09-lpj-list.png', fullPage: true });

    // 10. Refund - Index
    await page.goto('/refund');
    await page.waitForLoadState('domcontentloaded');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'screenshots/manual/10-refund-list.png', fullPage: true });

    // 11. Approvals - Index
    await page.goto('/approvals');
    await page.waitForLoadState('domcontentloaded');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'screenshots/manual/11-approvals-list.png', fullPage: true });

    // 12. Profile
    await page.goto('/profile');
    await page.waitForLoadState('domcontentloaded');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'screenshots/manual/12-profile.png', fullPage: true });

    // 13. Menu/Sidebar
    await page.goto('/dashboard');
    await page.waitForLoadState('domcontentloaded');
    await page.screenshot({ path: 'screenshots/manual/13-menu-navigation.png', fullPage: true });
  });
});
