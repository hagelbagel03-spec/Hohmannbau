#!/usr/bin/env python3
"""
Comprehensive PHP Admin Panel Test
Tests the Hohmann Bau Universal Admin Panel functionality
"""

import requests
import json
import sys
from datetime import datetime

class PHPAdminTester:
    def __init__(self, base_url="http://localhost/Hohmannbau/admin"):
        self.base_url = base_url
        self.session = requests.Session()
        self.tests_run = 0
        self.tests_passed = 0
        self.failed_tests = []

    def run_test(self, name, test_func):
        """Run a single test"""
        self.tests_run += 1
        print(f"\n🔍 Testing {name}...")
        
        try:
            success = test_func()
            if success:
                self.tests_passed += 1
                print(f"✅ {name} - PASSED")
            else:
                print(f"❌ {name} - FAILED")
                self.failed_tests.append(name)
            return success
        except Exception as e:
            print(f"❌ {name} - ERROR: {str(e)}")
            self.failed_tests.append(f"{name} (Error: {str(e)})")
            return False

    def test_login_functionality(self):
        """Test admin login"""
        # Test login page access
        response = self.session.get(f"{self.base_url}/login.php")
        if response.status_code != 200:
            print(f"   Login page not accessible: {response.status_code}")
            return False
        
        if "Admin Login" not in response.text:
            print("   Login page content not found")
            return False
        
        # Test login with correct credentials
        login_data = {
            "username": "admin",
            "password": "admin123"
        }
        
        response = self.session.post(f"{self.base_url}/login.php", data=login_data)
        
        # Check if redirected to admin panel
        if response.status_code == 200 and "universal_admin.php" in response.url:
            print("   ✅ Login successful with correct credentials")
            return True
        else:
            print(f"   ❌ Login failed or unexpected redirect: {response.status_code}, URL: {response.url}")
            return False

    def test_admin_panel_access(self):
        """Test admin panel access after login"""
        response = self.session.get(f"{self.base_url}/universal_admin.php")
        
        if response.status_code != 200:
            print(f"   Admin panel not accessible: {response.status_code}")
            return False
        
        if "Universal Dashboard" not in response.text:
            print("   Admin panel dashboard not found")
            return False
        
        if "Universal PHP Admin Panel" not in response.text:
            print("   Admin panel header not found")
            return False
        
        print("   ✅ Admin panel accessible and content loaded")
        return True

    def test_page_editor_ajax(self):
        """Test Page Editor AJAX functionality"""
        # Test getting page content
        ajax_data = {
            "action": "get_page_content",
            "page_name": "home"
        }
        
        response = self.session.post(f"{self.base_url}/universal_admin.php", data=ajax_data)
        
        if response.status_code != 200:
            print(f"   AJAX request failed: {response.status_code}")
            return False
        
        try:
            data = response.json()
            print(f"   ✅ Page content loaded: {len(data)} fields")
        except json.JSONDecodeError:
            print(f"   ❌ Invalid JSON response: {response.text[:100]}")
            return False
        
        # Test saving page content
        save_data = {
            "action": "save_page_content",
            "page_name": "home",
            "content": json.dumps({
                "hero_title": "Test Title from Automated Test",
                "hero_subtitle": "Test Subtitle from Automated Test"
            })
        }
        
        response = self.session.post(f"{self.base_url}/universal_admin.php", data=save_data)
        
        if response.status_code != 200:
            print(f"   Save request failed: {response.status_code}")
            return False
        
        try:
            data = response.json()
            if data.get("success"):
                print("   ✅ Page content saved successfully")
                return True
            else:
                print(f"   ❌ Save failed: {data.get('error', 'Unknown error')}")
                return False
        except json.JSONDecodeError:
            print(f"   ❌ Invalid JSON response: {response.text[:100]}")
            return False

    def test_design_system_ajax(self):
        """Test Design System AJAX functionality"""
        # Test getting design settings
        ajax_data = {
            "action": "get_design_settings",
            "setting_type": "theme"
        }
        
        response = self.session.post(f"{self.base_url}/universal_admin.php", data=ajax_data)
        
        if response.status_code != 200:
            print(f"   AJAX request failed: {response.status_code}")
            return False
        
        try:
            data = response.json()
            print(f"   ✅ Design settings loaded: {len(data)} settings")
        except json.JSONDecodeError:
            print(f"   ❌ Invalid JSON response: {response.text[:100]}")
            return False
        
        # Test saving design settings
        save_data = {
            "action": "save_design_settings",
            "setting_type": "theme",
            "settings": json.dumps({
                "primary_color": "#10b981",
                "secondary_color": "#059669",
                "accent_color": "#3b82f6"
            })
        }
        
        response = self.session.post(f"{self.base_url}/universal_admin.php", data=save_data)
        
        if response.status_code != 200:
            print(f"   Save request failed: {response.status_code}")
            return False
        
        try:
            data = response.json()
            if data.get("success"):
                print("   ✅ Design settings saved successfully")
                return True
            else:
                print(f"   ❌ Save failed: {data.get('error', 'Unknown error')}")
                return False
        except json.JSONDecodeError:
            print(f"   ❌ Invalid JSON response: {response.text[:100]}")
            return False

    def test_all_page_types(self):
        """Test all page types in the page editor"""
        pages = ["home", "services", "projects", "team", "contact", "career", "footer", "navigation"]
        
        for page in pages:
            ajax_data = {
                "action": "get_page_content",
                "page_name": page
            }
            
            response = self.session.post(f"{self.base_url}/universal_admin.php", data=ajax_data)
            
            if response.status_code != 200:
                print(f"   ❌ {page} page failed: {response.status_code}")
                return False
            
            try:
                data = response.json()
                print(f"   ✅ {page} page loaded successfully")
            except json.JSONDecodeError:
                print(f"   ❌ {page} page returned invalid JSON")
                return False
        
        return True

    def test_error_handling(self):
        """Test error handling for invalid requests"""
        # Test invalid action
        ajax_data = {
            "action": "invalid_action"
        }
        
        response = self.session.post(f"{self.base_url}/universal_admin.php", data=ajax_data)
        
        if response.status_code != 200:
            print(f"   Unexpected status code: {response.status_code}")
            return False
        
        try:
            data = response.json()
            if not data.get("success") and "error" in data:
                print("   ✅ Error handling works correctly")
                return True
            else:
                print(f"   ❌ Expected error response, got: {data}")
                return False
        except json.JSONDecodeError:
            print(f"   ❌ Invalid JSON response: {response.text[:100]}")
            return False

    def run_all_tests(self):
        """Run all tests"""
        print("🏗️  Starting Comprehensive PHP Admin Panel Tests")
        print("=" * 60)
        
        # Test login functionality
        self.run_test("Admin Login", self.test_login_functionality)
        
        # Test admin panel access
        self.run_test("Admin Panel Access", self.test_admin_panel_access)
        
        # Test Page Editor AJAX
        self.run_test("Page Editor AJAX", self.test_page_editor_ajax)
        
        # Test Design System AJAX
        self.run_test("Design System AJAX", self.test_design_system_ajax)
        
        # Test all page types
        self.run_test("All Page Types", self.test_all_page_types)
        
        # Test error handling
        self.run_test("Error Handling", self.test_error_handling)
        
        # Print final results
        print("\n" + "=" * 60)
        print(f"📊 Final Results: {self.tests_passed}/{self.tests_run} tests passed")
        
        if self.failed_tests:
            print("\n❌ Failed Tests:")
            for test in self.failed_tests:
                print(f"   - {test}")
        
        if self.tests_passed == self.tests_run:
            print("🎉 All PHP Admin Panel tests passed!")
            return 0
        else:
            print(f"⚠️  {len(self.failed_tests)} tests failed")
            return 1

def main():
    tester = PHPAdminTester()
    return tester.run_all_tests()

if __name__ == "__main__":
    sys.exit(main())