// @ts-check
import { test, expect } from '@playwright/test';

const url = 'http://localhost:5173';

test('has title', async ({ page }) => {
  await page.goto(`${url}/`);

  // Expect a title "to contain" a substring.
  await expect(page).toHaveTitle(/Sportverseny - Főoldal/);
});

test('Login with Admin', async ({ page }) => {
  // Nyisd meg a bejelentkezési oldalt
  await page.goto(`${url}/login`);

  // Töltsd ki az űrlapot
  await page.fill('input#email', 'medgyescsaba@gmail.com');
  await page.fill('input#password', 'MedgyesCsaba2025');
  // await page.fill('input[name="email"]', 'test@example.com');
  // await page.fill('input[name="password"]', '123');

  // Kattints a bejelentkezés gombra
  // await page.click('button[type="submit"]');
  await page.click('button:has-text("Bejelentkezés")');

  // Ellenőrizd, hogy sikeres bejelentkezés után átirányították a felhasználót
  await expect(page).toHaveURL(`${url}/`);

});

// test('get started link', async ({ page }) => {
//   await page.goto('https://playwright.dev/');

//   // Click the get started link.
//   await page.getByRole('link', { name: 'Get started' }).click();

//   // Expects page to have a heading with the name of Installation.
//   await expect(page.getByRole('heading', { name: 'Installation' })).toBeVisible();
// });

// Happy hacking! 🎭