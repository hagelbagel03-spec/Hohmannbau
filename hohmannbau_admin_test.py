#!/usr/bin/env python3
"""
Comprehensive test for Hohmannbau Admin Interface
Testing the repaired modal dialogs vs browser prompts
"""

import asyncio
import sys
from playwright.async_api import async_playwright

class HohmannbauAdminTester:
    def __init__(self):
        self.base_url = "http://localhost/Hohmannbau/admin/universal_admin.php"
        self.login_url = "http://localhost/Hohmannbau/admin/login.php"
        self.username = "admin"
        self.password = "admin123"
        self.test_results = []
        self.console_errors = []

    async def run_tests(self):
        """Run all admin interface tests"""
        async with async_playwright() as p:
            browser = await p.chromium.launch(headless=False)
            context = await browser.new_context()
            page = await context.new_page()
            
            # Capture console messages
            page.on("console", lambda msg: self.console_errors.append(f"CONSOLE {msg.type}: {msg.text}"))
            page.on("pageerror", lambda error: self.console_errors.append(f"PAGE ERROR: {error}"))
            
            try:
                # Login first
                await self.login(page)
                
                # Test Career Manager (main focus from review request)
                await self.test_career_manager(page)
                
                # Test other sections mentioned in review
                await self.test_projects_manager(page)
                await self.test_team_manager(page)
                await self.test_content_manager(page)
                await self.test_offers_manager(page)
                
                # Check for JavaScript errors
                await self.check_console_errors()
                
                # Print results
                self.print_results()
                
            except Exception as e:
                print(f"❌ Test failed with error: {e}")
                return False
            finally:
                await browser.close()
                
        return len([r for r in self.test_results if not r['passed']]) == 0

    async def login(self, page):
        """Login to admin interface"""
        print("🔐 Logging into admin interface...")
        
        try:
            await page.goto(self.login_url)
            await page.wait_for_selector("input[name='username']", timeout=5000)
            
            await page.fill("input[name='username']", self.username)
            await page.fill("input[name='password']", self.password)
            await page.click("button[type='submit']")
            
            # Wait for redirect to admin panel
            await page.wait_for_url("**/universal_admin.php", timeout=10000)
            print("✅ Login successful")
            
        except Exception as e:
            print(f"❌ Login failed: {e}")
            raise

    async def test_career_manager(self, page):
        """Test Career Manager - main focus from review request"""
        print("\n💼 Testing Career Manager...")
        
        try:
            # Navigate to Career Manager
            await page.click("text=💼 Karriere")
            await page.wait_for_selector("#career-manager-section", timeout=5000)
            
            # Test "Neue Stellenausschreibung" button
            print("  Testing 'Neue Stellenausschreibung' button...")
            
            # Set up dialog handler to catch any prompts
            dialog_caught = False
            def handle_dialog(dialog):
                nonlocal dialog_caught
                dialog_caught = True
                print(f"  ❌ PROMPT DETECTED: {dialog.message}")
                dialog.dismiss()
            
            page.on("dialog", handle_dialog)
            
            # Click the button
            await page.click("text=Neue Stellenausschreibung")
            await page.wait_for_timeout(1000)
            
            # Check if modal opened instead of prompt
            modal_visible = await page.is_visible("#jobEditModal")
            
            if dialog_caught:
                self.test_results.append({
                    'test': 'Career Manager - New Job Button',
                    'passed': False,
                    'message': 'Still using browser prompt instead of modal'
                })
            elif modal_visible:
                self.test_results.append({
                    'test': 'Career Manager - New Job Button',
                    'passed': True,
                    'message': 'Modal opens correctly'
                })
                # Close modal
                await page.click("button:has-text('Abbrechen')")
            else:
                self.test_results.append({
                    'test': 'Career Manager - New Job Button',
                    'passed': False,
                    'message': 'Neither modal nor prompt detected'
                })
            
            # Test "Status ändern" functionality
            print("  Testing 'Status ändern' functionality...")
            
            dialog_caught = False
            # Look for status change buttons
            status_buttons = await page.query_selector_all("button:has-text('Status ändern')")
            if status_buttons:
                await status_buttons[0].click()
                await page.wait_for_timeout(1000)
                
                # Check for status change modal
                status_modal_visible = await page.is_visible("#statusChangeModal")
                
                if dialog_caught:
                    self.test_results.append({
                        'test': 'Career Manager - Status Change',
                        'passed': False,
                        'message': 'Still using browser prompt for status change'
                    })
                elif status_modal_visible:
                    self.test_results.append({
                        'test': 'Career Manager - Status Change',
                        'passed': True,
                        'message': 'Status change modal opens correctly'
                    })
                else:
                    self.test_results.append({
                        'test': 'Career Manager - Status Change',
                        'passed': False,
                        'message': 'Status change uses confirm dialog instead of modal'
                    })
            else:
                self.test_results.append({
                    'test': 'Career Manager - Status Change',
                    'passed': False,
                    'message': 'No status change buttons found'
                })
                
        except Exception as e:
            self.test_results.append({
                'test': 'Career Manager',
                'passed': False,
                'message': f'Error: {e}'
            })

    async def test_projects_manager(self, page):
        """Test Projects Manager"""
        print("\n🏗️ Testing Projects Manager...")
        
        try:
            await page.click("text=🏗️ Projekte")
            await page.wait_for_selector("#projects-section", timeout=5000)
            
            dialog_caught = False
            def handle_dialog(dialog):
                nonlocal dialog_caught
                dialog_caught = True
                print(f"  ❌ PROMPT DETECTED: {dialog.message}")
                dialog.dismiss()
            
            page.on("dialog", handle_dialog)
            
            # Test "Neues Projekt hinzufügen" button
            await page.click("text=Neues Projekt hinzufügen")
            await page.wait_for_timeout(1000)
            
            if dialog_caught:
                self.test_results.append({
                    'test': 'Projects Manager - Add New Project',
                    'passed': False,
                    'message': 'Still using browser prompts'
                })
            else:
                # Check if modal opened
                modal_visible = await page.is_visible("#projectEditModal")
                self.test_results.append({
                    'test': 'Projects Manager - Add New Project',
                    'passed': modal_visible,
                    'message': 'Modal opens correctly' if modal_visible else 'No modal detected'
                })
                
        except Exception as e:
            self.test_results.append({
                'test': 'Projects Manager',
                'passed': False,
                'message': f'Error: {e}'
            })

    async def test_team_manager(self, page):
        """Test Team Manager"""
        print("\n👥 Testing Team Manager...")
        
        try:
            await page.click("text=👥 Unser Team")
            await page.wait_for_selector("#team-manager-section", timeout=5000)
            
            dialog_caught = False
            def handle_dialog(dialog):
                nonlocal dialog_caught
                dialog_caught = True
                print(f"  ❌ PROMPT DETECTED: {dialog.message}")
                dialog.dismiss()
            
            page.on("dialog", handle_dialog)
            
            # Test "Neues Team-Mitglied" button
            team_buttons = await page.query_selector_all("button:has-text('Team-Mitglied')")
            if team_buttons:
                await team_buttons[0].click()
                await page.wait_for_timeout(1000)
                
                if dialog_caught:
                    self.test_results.append({
                        'test': 'Team Manager - Add New Member',
                        'passed': False,
                        'message': 'Still using browser prompts'
                    })
                else:
                    modal_visible = await page.is_visible("#teamEditModal")
                    self.test_results.append({
                        'test': 'Team Manager - Add New Member',
                        'passed': modal_visible,
                        'message': 'Modal opens correctly' if modal_visible else 'No modal detected'
                    })
            else:
                self.test_results.append({
                    'test': 'Team Manager - Add New Member',
                    'passed': False,
                    'message': 'No team member buttons found'
                })
                
        except Exception as e:
            self.test_results.append({
                'test': 'Team Manager',
                'passed': False,
                'message': f'Error: {e}'
            })

    async def test_content_manager(self, page):
        """Test Content Manager"""
        print("\n📝 Testing Content Manager...")
        
        try:
            await page.click("text=📝 Content Manager")
            await page.wait_for_selector("#content-manager-section", timeout=5000)
            
            dialog_caught = False
            def handle_dialog(dialog):
                nonlocal dialog_caught
                dialog_caught = True
                print(f"  ❌ PROMPT DETECTED: {dialog.message}")
                dialog.dismiss()
            
            page.on("dialog", handle_dialog)
            
            # Test "Content bearbeiten" buttons
            edit_buttons = await page.query_selector_all("button:has-text('Bearbeiten')")
            if edit_buttons:
                await edit_buttons[0].click()
                await page.wait_for_timeout(1000)
                
                if dialog_caught:
                    self.test_results.append({
                        'test': 'Content Manager - Edit Content',
                        'passed': False,
                        'message': 'Still using browser prompts'
                    })
                else:
                    modal_visible = await page.is_visible("#contentEditModal")
                    self.test_results.append({
                        'test': 'Content Manager - Edit Content',
                        'passed': modal_visible,
                        'message': 'Modal opens correctly' if modal_visible else 'No modal detected'
                    })
                    
        except Exception as e:
            self.test_results.append({
                'test': 'Content Manager',
                'passed': False,
                'message': f'Error: {e}'
            })

    async def test_offers_manager(self, page):
        """Test Offers Manager"""
        print("\n📋 Testing Offers Manager...")
        
        try:
            await page.click("text=📋 Angebote")
            await page.wait_for_selector("#offers-manager-section", timeout=5000)
            
            # Test "Kostenvoranschlag wird erstellt" functionality
            quote_buttons = await page.query_selector_all("button:has-text('Kostenvoranschlag')")
            if quote_buttons:
                await quote_buttons[0].click()
                await page.wait_for_timeout(1000)
                
                modal_visible = await page.is_visible("#quoteModal")
                self.test_results.append({
                    'test': 'Offers Manager - Quote Generation',
                    'passed': modal_visible,
                    'message': 'Quote modal opens correctly' if modal_visible else 'No quote modal detected'
                })
            else:
                self.test_results.append({
                    'test': 'Offers Manager - Quote Generation',
                    'passed': False,
                    'message': 'No quote buttons found'
                })
                
        except Exception as e:
            self.test_results.append({
                'test': 'Offers Manager',
                'passed': False,
                'message': f'Error: {e}'
            })

    async def check_console_errors(self):
        """Check for JavaScript errors"""
        print("\n🔍 Checking for JavaScript errors...")
        
        error_count = len([e for e in self.console_errors if 'ERROR' in e])
        warning_count = len([e for e in self.console_errors if 'WARN' in e])
        
        self.test_results.append({
            'test': 'JavaScript Console Errors',
            'passed': error_count == 0,
            'message': f'Found {error_count} errors, {warning_count} warnings'
        })
        
        if self.console_errors:
            print("  Console messages:")
            for error in self.console_errors[-10:]:  # Show last 10
                print(f"    {error}")

    def print_results(self):
        """Print test results summary"""
        print("\n" + "="*60)
        print("🧪 HOHMANNBAU ADMIN TEST RESULTS")
        print("="*60)
        
        passed = 0
        failed = 0
        
        for result in self.test_results:
            status = "✅ PASS" if result['passed'] else "❌ FAIL"
            print(f"{status} {result['test']}: {result['message']}")
            
            if result['passed']:
                passed += 1
            else:
                failed += 1
        
        print("\n" + "-"*60)
        print(f"📊 SUMMARY: {passed} passed, {failed} failed")
        
        if failed > 0:
            print("\n🚨 CRITICAL ISSUES FOUND:")
            print("The admin interface still has browser prompts that need to be converted to modals!")
            print("Main agent needs to complete the modal conversion.")
        else:
            print("\n🎉 ALL TESTS PASSED!")
            print("The admin interface is working correctly with proper modals.")

async def main():
    tester = HohmannbauAdminTester()
    success = await tester.run_tests()
    return 0 if success else 1

if __name__ == "__main__":
    sys.exit(asyncio.run(main()))