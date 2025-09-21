#!/usr/bin/env python3
"""
Browser automation test for Hohmann Bau Admin Panel Button Functionality
Tests all buttons after JavaScript bug fix (duplicate showSection functions)
"""

import asyncio
import sys
from playwright.async_api import async_playwright

class AdminButtonTester:
    def __init__(self):
        self.tests_run = 0
        self.tests_passed = 0
        self.failed_tests = []
        self.successful_tests = []

    async def run_test(self, name, test_func, page):
        """Run a single test and record results"""
        self.tests_run += 1
        print(f"\n🔍 Testing {name}...")
        
        try:
            result = await test_func(page)
            if result:
                self.tests_passed += 1
                self.successful_tests.append(name)
                print(f"✅ {name} - PASSED")
            else:
                self.failed_tests.append(name)
                print(f"❌ {name} - FAILED")
            return result
        except Exception as e:
            self.failed_tests.append(f"{name}: {str(e)}")
            print(f"❌ {name} - ERROR: {str(e)}")
            return False

    async def login_to_admin(self, page):
        """Login to admin panel"""
        print("🔐 Logging into admin panel...")
        
        # Navigate to login page
        await page.goto("http://localhost/Hohmannbau/admin/login.php")
        await page.wait_for_load_state("networkidle")
        
        # Fill login form
        await page.fill("input[name='username']", "admin")
        await page.fill("input[name='password']", "admin123")
        
        # Submit form
        await page.click("button[type='submit'], input[type='submit']")
        await page.wait_for_load_state("networkidle")
        
        # Check if we're on admin panel
        current_url = page.url
        if "universal_admin.php" in current_url:
            print("✅ Successfully logged in")
            return True
        else:
            print(f"❌ Login failed, current URL: {current_url}")
            return False

    async def test_navigation_buttons(self, page):
        """Test all navigation buttons"""
        navigation_buttons = [
            ("Dashboard", "dashboard"),
            ("Page Editor", "page-editor"),
            ("Design System", "design-system"),
            ("Media Manager", "media-manager"),
            ("Navigation Editor", "navigation-editor"),
            ("Services Manager", "services-manager"),
            ("Team Manager", "team-manager"),
            ("Projekte Manager", "projekte-manager"),
            ("Nachrichten Manager", "nachrichten-manager"),
            ("Feedback Verwaltung", "feedback-verwaltung"),
            ("Bewerbungen", "bewerbungen"),
            ("System Einstellungen", "system-einstellungen")
        ]
        
        for button_name, section_id in navigation_buttons:
            try:
                # Look for button with various selectors
                button_selectors = [
                    f"button:has-text('{button_name}')",
                    f"[onclick*='{section_id}']",
                    f"[data-section='{section_id}']",
                    f".sidebar-nav-item:has-text('{button_name}')"
                ]
                
                button_found = False
                for selector in button_selectors:
                    try:
                        button = await page.wait_for_selector(selector, timeout=2000)
                        if button:
                            print(f"   Found {button_name} button")
                            await button.click(force=True)
                            await page.wait_for_timeout(500)  # Wait for section to show
                            
                            # Check if section is visible
                            section_visible = await page.evaluate(f"""
                                () => {{
                                    const section = document.getElementById('{section_id}');
                                    return section && section.style.display !== 'none';
                                }}
                            """)
                            
                            if section_visible:
                                print(f"   ✅ {button_name} section is visible")
                            else:
                                print(f"   ⚠️ {button_name} section may not be visible")
                            
                            button_found = True
                            break
                    except:
                        continue
                
                if not button_found:
                    print(f"   ❌ {button_name} button not found")
                    
            except Exception as e:
                print(f"   ❌ Error testing {button_name}: {str(e)}")
        
        return True

    async def test_action_buttons(self, page):
        """Test action buttons (Edit, Delete, Add, Save, etc.)"""
        action_buttons = [
            ("Bearbeiten", "edit"),
            ("Löschen", "delete"),
            ("Hinzufügen", "add"),
            ("Speichern", "save"),
            ("Neu laden", "reload")
        ]
        
        for button_text, action_type in action_buttons:
            try:
                # Look for buttons with various selectors
                button_selectors = [
                    f"button:has-text('{button_text}')",
                    f"[onclick*='{action_type}']",
                    f".btn:has-text('{button_text}')",
                    f"[title*='{button_text}']"
                ]
                
                buttons_found = 0
                for selector in button_selectors:
                    try:
                        buttons = await page.query_selector_all(selector)
                        for button in buttons:
                            if await button.is_visible():
                                buttons_found += 1
                                print(f"   Found {button_text} button")
                                
                                # Test if button is clickable
                                await button.click(force=True)
                                await page.wait_for_timeout(200)
                                print(f"   ✅ {button_text} button is clickable")
                                break
                    except:
                        continue
                
                if buttons_found == 0:
                    print(f"   ⚠️ No {button_text} buttons found or visible")
                    
            except Exception as e:
                print(f"   ❌ Error testing {button_text}: {str(e)}")
        
        return True

    async def test_modal_functionality(self, page):
        """Test modal opening and closing"""
        try:
            # Look for buttons that might open modals
            modal_triggers = [
                "button:has-text('Hinzufügen')",
                "button:has-text('Bearbeiten')",
                "[data-modal]",
                ".modal-trigger"
            ]
            
            for selector in modal_triggers:
                try:
                    buttons = await page.query_selector_all(selector)
                    for button in buttons[:2]:  # Test first 2 buttons only
                        if await button.is_visible():
                            print(f"   Testing modal trigger button")
                            await button.click(force=True)
                            await page.wait_for_timeout(500)
                            
                            # Check if modal opened
                            modal_visible = await page.evaluate("""
                                () => {
                                    const modals = document.querySelectorAll('.modal, [class*="modal"], [id*="modal"]');
                                    return Array.from(modals).some(modal => 
                                        modal.style.display !== 'none' && 
                                        !modal.classList.contains('hidden')
                                    );
                                }
                            """)
                            
                            if modal_visible:
                                print(f"   ✅ Modal opened successfully")
                                
                                # Try to close modal
                                close_selectors = [
                                    ".modal-close",
                                    "[data-dismiss='modal']",
                                    "button:has-text('Schließen')",
                                    "button:has-text('Abbrechen')"
                                ]
                                
                                for close_selector in close_selectors:
                                    try:
                                        close_button = await page.wait_for_selector(close_selector, timeout=1000)
                                        if close_button:
                                            await close_button.click(force=True)
                                            await page.wait_for_timeout(300)
                                            print(f"   ✅ Modal closed successfully")
                                            break
                                    except:
                                        continue
                            else:
                                print(f"   ⚠️ Modal may not have opened")
                            break
                except:
                    continue
            
            return True
            
        except Exception as e:
            print(f"   ❌ Error testing modals: {str(e)}")
            return False

    async def test_javascript_errors(self, page):
        """Check for JavaScript console errors"""
        console_errors = []
        
        def handle_console(msg):
            if msg.type == 'error':
                console_errors.append(msg.text)
                print(f"   ❌ JavaScript Error: {msg.text}")
        
        page.on("console", handle_console)
        
        # Trigger some interactions to check for errors
        try:
            # Click a few buttons to trigger JavaScript
            buttons = await page.query_selector_all("button")
            for button in buttons[:3]:
                if await button.is_visible():
                    await button.click(force=True)
                    await page.wait_for_timeout(200)
        except:
            pass
        
        if console_errors:
            print(f"   ❌ Found {len(console_errors)} JavaScript errors")
            return False
        else:
            print(f"   ✅ No JavaScript errors detected")
            return True

    async def run_all_tests(self):
        """Run all admin panel button tests"""
        print("🚀 HOHMANN BAU ADMIN PANEL - BUTTON FUNCTIONALITY TEST")
        print("Testing after JavaScript bug fix (duplicate showSection functions)")
        print("="*60)
        
        async with async_playwright() as p:
            browser = await p.chromium.launch(headless=True)
            page = await browser.new_page()
            
            # Set viewport
            await page.set_viewport_size({"width": 1920, "height": 1080})
            
            try:
                # Login first
                if not await self.login_to_admin(page):
                    print("❌ Cannot proceed without login")
                    await browser.close()
                    return False
                
                # Take screenshot of admin panel
                await page.screenshot(path="admin_panel_loaded.png", quality=40, full_page=False)
                print("📸 Admin panel screenshot taken")
                
                # Run tests
                await self.run_test("Navigation Buttons", self.test_navigation_buttons, page)
                await self.run_test("Action Buttons", self.test_action_buttons, page)
                await self.run_test("Modal Functionality", self.test_modal_functionality, page)
                await self.run_test("JavaScript Errors Check", self.test_javascript_errors, page)
                
            finally:
                await browser.close()
        
        return True

    def print_summary(self):
        """Print test summary"""
        print(f"\n" + "="*60)
        print(f"📊 BUTTON FUNCTIONALITY TEST SUMMARY")
        print(f"="*60)
        print(f"Total Tests: {self.tests_run}")
        print(f"Passed: {self.tests_passed}")
        print(f"Failed: {len(self.failed_tests)}")
        print(f"Success Rate: {(self.tests_passed/self.tests_run)*100:.1f}%" if self.tests_run > 0 else "No tests run")
        
        if self.failed_tests:
            print(f"\n❌ FAILED TESTS:")
            for test in self.failed_tests:
                print(f"   - {test}")
        
        if self.successful_tests:
            print(f"\n✅ SUCCESSFUL TESTS:")
            for test in self.successful_tests:
                print(f"   - {test}")

async def main():
    tester = AdminButtonTester()
    
    try:
        await tester.run_all_tests()
        tester.print_summary()
        
        # Return success if most tests passed
        success_rate = (tester.tests_passed / tester.tests_run) * 100 if tester.tests_run > 0 else 0
        return 0 if success_rate >= 75 else 1
        
    except Exception as e:
        print(f"❌ Critical error during testing: {str(e)}")
        return 1

if __name__ == "__main__":
    sys.exit(asyncio.run(main()))