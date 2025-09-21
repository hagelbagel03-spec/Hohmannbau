#!/usr/bin/env python3
"""
Comprehensive test for Hohmann Bau PHP Admin Panel
Tests all button functionality after JavaScript bug fix
"""

import requests
import json
import sys
from datetime import datetime
import time

class PHPAdminPanelTester:
    def __init__(self, base_url="http://localhost/Hohmannbau"):
        self.base_url = base_url
        self.admin_url = f"{base_url}/admin"
        self.session = requests.Session()
        self.tests_run = 0
        self.tests_passed = 0
        self.logged_in = False
        self.test_results = []

    def run_test(self, name, method, url, expected_status, data=None, headers=None, expect_json=False):
        """Run a single test and record results"""
        self.tests_run += 1
        print(f"\n🔍 Testing {name}...")
        print(f"   URL: {url}")
        
        try:
            test_headers = headers or {}
            
            if method == 'GET':
                response = self.session.get(url, headers=test_headers)
            elif method == 'POST':
                if 'Content-Type' not in test_headers:
                    test_headers['Content-Type'] = 'application/x-www-form-urlencoded'
                response = self.session.post(url, data=data, headers=test_headers)

            success = response.status_code == expected_status
            
            result = {
                'name': name,
                'method': method,
                'url': url,
                'expected_status': expected_status,
                'actual_status': response.status_code,
                'success': success,
                'response_preview': response.text[:200] if response.text else '',
                'error': None
            }
            
            if success:
                self.tests_passed += 1
                print(f"✅ Passed - Status: {response.status_code}")
                
                if expect_json:
                    try:
                        json_response = response.json()
                        result['json_response'] = json_response
                        print(f"   JSON Response: {json_response}")
                    except:
                        print("   Warning: Expected JSON but got non-JSON response")
                        
            else:
                print(f"❌ Failed - Expected {expected_status}, got {response.status_code}")
                print(f"   Response preview: {response.text[:200]}")
                result['error'] = f"Status code mismatch: expected {expected_status}, got {response.status_code}"

            self.test_results.append(result)
            return success, response

        except Exception as e:
            print(f"❌ Failed - Error: {str(e)}")
            result = {
                'name': name,
                'method': method,
                'url': url,
                'expected_status': expected_status,
                'actual_status': 'ERROR',
                'success': False,
                'response_preview': '',
                'error': str(e)
            }
            self.test_results.append(result)
            return False, None

    def test_login(self, username="admin", password="admin123"):
        """Test login functionality"""
        print("\n🔐 TESTING LOGIN FUNCTIONALITY")
        
        # First get the login page
        success, response = self.run_test(
            "Access Login Page",
            "GET",
            f"{self.admin_url}/login.php",
            200
        )
        
        if not success:
            return False
            
        # Test login
        login_data = {
            'username': username,
            'password': password
        }
        
        success, response = self.run_test(
            "Submit Login Form",
            "POST",
            f"{self.admin_url}/login.php",
            302  # Expecting redirect after successful login
        )
        
        if success:
            self.logged_in = True
            print("✅ Login successful - redirected as expected")
        else:
            # Try with 200 status in case login doesn't redirect
            success, response = self.run_test(
                "Submit Login Form (Alternative)",
                "POST",
                f"{self.admin_url}/login.php",
                200,
                data=login_data
            )
            
            if success and response and "universal_admin" in response.text:
                self.logged_in = True
                print("✅ Login successful - admin panel content detected")
        
        return self.logged_in

    def test_admin_panel_access(self):
        """Test access to admin panel"""
        print("\n🏠 TESTING ADMIN PANEL ACCESS")
        
        success, response = self.run_test(
            "Access Admin Panel",
            "GET",
            f"{self.admin_url}/universal_admin.php",
            200
        )
        
        if success and response:
            # Check for key admin panel elements
            content = response.text.lower()
            
            # Check for navigation buttons mentioned in the request
            nav_buttons = [
                'dashboard', 'page editor', 'design system', 'media manager',
                'navigation editor', 'services manager', 'team manager',
                'projekte manager', 'nachrichten manager', 'feedback verwaltung',
                'bewerbungen', 'system einstellungen'
            ]
            
            found_buttons = []
            for button in nav_buttons:
                if button.lower() in content:
                    found_buttons.append(button)
            
            print(f"✅ Found navigation elements: {found_buttons}")
            
            # Check for action buttons
            action_elements = ['bearbeiten', 'löschen', 'hinzufügen', 'speichern', 'neu laden']
            found_actions = []
            for action in action_elements:
                if action.lower() in content:
                    found_actions.append(action)
            
            print(f"✅ Found action elements: {found_actions}")
            
            # Check for JavaScript functions
            js_functions = ['showsection', 'onclick', 'function']
            found_js = []
            for js in js_functions:
                if js.lower() in content:
                    found_js.append(js)
            
            print(f"✅ Found JavaScript elements: {found_js}")
            
        return success

    def test_ajax_endpoints(self):
        """Test AJAX endpoints for admin functionality"""
        print("\n🔄 TESTING AJAX ENDPOINTS")
        
        # Test various AJAX actions
        ajax_tests = [
            {
                'name': 'Get Page Content',
                'action': 'get_page_content',
                'data': {'action': 'get_page_content', 'page_name': 'home'}
            },
            {
                'name': 'Get Design Settings',
                'action': 'get_design_settings',
                'data': {'action': 'get_design_settings', 'setting_type': 'colors'}
            },
            {
                'name': 'Save Page Content (Test)',
                'action': 'save_page_content',
                'data': {
                    'action': 'save_page_content',
                    'page_name': 'test_page',
                    'content': json.dumps({'title': 'Test Title', 'content': 'Test Content'})
                }
            }
        ]
        
        for test in ajax_tests:
            success, response = self.run_test(
                test['name'],
                "POST",
                f"{self.admin_url}/universal_admin.php",
                200,
                data=test['data'],
                headers={'Content-Type': 'application/x-www-form-urlencoded'},
                expect_json=True
            )
            
            if success and response:
                try:
                    json_response = response.json()
                    if 'success' in json_response:
                        print(f"   AJAX Response: {json_response}")
                    else:
                        print(f"   Unexpected AJAX format: {json_response}")
                except:
                    print(f"   Non-JSON AJAX response: {response.text[:100]}")

    def test_service_management(self):
        """Test service management functionality"""
        print("\n🔧 TESTING SERVICE MANAGEMENT")
        
        # Test service operations
        service_tests = [
            {
                'name': 'Save Test Service',
                'data': {
                    'action': 'save_service',
                    'service_id': 'test_service_' + str(int(time.time())),
                    'title': 'Test Service',
                    'description': 'Test Description',
                    'features': json.dumps(['Feature 1', 'Feature 2']),
                    'icon': '🔧',
                    'image': 'test.jpg',
                    'is_active': '1'
                }
            }
        ]
        
        for test in service_tests:
            success, response = self.run_test(
                test['name'],
                "POST",
                f"{self.admin_url}/universal_admin.php",
                200,
                data=test['data'],
                headers={'Content-Type': 'application/x-www-form-urlencoded'},
                expect_json=True
            )

    def test_team_management(self):
        """Test team management functionality"""
        print("\n👥 TESTING TEAM MANAGEMENT")
        
        team_tests = [
            {
                'name': 'Save Test Team Member',
                'data': {
                    'action': 'save_team_member',
                    'member_id': 'test_member_' + str(int(time.time())),
                    'name': 'Test Member',
                    'position': 'Test Position',
                    'bio': 'Test Bio',
                    'image_url': 'test_member.jpg'
                }
            }
        ]
        
        for test in team_tests:
            success, response = self.run_test(
                test['name'],
                "POST",
                f"{self.admin_url}/universal_admin.php",
                200,
                data=test['data'],
                headers={'Content-Type': 'application/x-www-form-urlencoded'},
                expect_json=True
            )

    def print_summary(self):
        """Print test summary"""
        print(f"\n" + "="*60)
        print(f"📊 TEST SUMMARY")
        print(f"="*60)
        print(f"Total Tests: {self.tests_run}")
        print(f"Passed: {self.tests_passed}")
        print(f"Failed: {self.tests_run - self.tests_passed}")
        print(f"Success Rate: {(self.tests_passed/self.tests_run)*100:.1f}%")
        
        # Print failed tests
        failed_tests = [test for test in self.test_results if not test['success']]
        if failed_tests:
            print(f"\n❌ FAILED TESTS:")
            for test in failed_tests:
                print(f"   - {test['name']}: {test['error'] or 'Status code mismatch'}")
        
        # Print successful tests
        successful_tests = [test for test in self.test_results if test['success']]
        if successful_tests:
            print(f"\n✅ SUCCESSFUL TESTS:")
            for test in successful_tests:
                print(f"   - {test['name']}")

def main():
    print("🚀 HOHMANN BAU PHP ADMIN PANEL - BUTTON FUNCTIONALITY TEST")
    print("Testing after JavaScript bug fix (duplicate showSection functions)")
    print("="*60)
    
    tester = PHPAdminPanelTester()
    
    # Test login
    if not tester.test_login():
        print("❌ Login failed, cannot proceed with admin panel tests")
        tester.print_summary()
        return 1
    
    # Test admin panel access
    if not tester.test_admin_panel_access():
        print("❌ Admin panel access failed")
        tester.print_summary()
        return 1
    
    # Test AJAX endpoints
    tester.test_ajax_endpoints()
    
    # Test service management
    tester.test_service_management()
    
    # Test team management
    tester.test_team_management()
    
    # Print summary
    tester.print_summary()
    
    # Return appropriate exit code
    return 0 if tester.tests_passed == tester.tests_run else 1

if __name__ == "__main__":
    sys.exit(main())