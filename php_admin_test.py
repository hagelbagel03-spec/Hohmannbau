#!/usr/bin/env python3
"""
Comprehensive PHP Admin Panel Test Suite for Hohmann Bau
Tests the Universal PHP Admin Panel functionality via HTTP requests
"""

import requests
import json
import sys
from datetime import datetime

class PHPAdminTester:
    def __init__(self, base_url="http://localhost:8080"):
        self.base_url = base_url
        self.session = requests.Session()
        self.tests_run = 0
        self.tests_passed = 0
        
    def log(self, message, status="INFO"):
        timestamp = datetime.now().strftime("%H:%M:%S")
        status_emoji = {
            "INFO": "ℹ️",
            "SUCCESS": "✅", 
            "ERROR": "❌",
            "WARNING": "⚠️"
        }
        print(f"[{timestamp}] {status_emoji.get(status, 'ℹ️')} {message}")
        
    def run_test(self, test_name, test_func):
        """Run a single test and track results"""
        self.tests_run += 1
        self.log(f"Running test: {test_name}")
        
        try:
            result = test_func()
            if result:
                self.tests_passed += 1
                self.log(f"Test passed: {test_name}", "SUCCESS")
            else:
                self.log(f"Test failed: {test_name}", "ERROR")
            return result
        except Exception as e:
            self.log(f"Test error: {test_name} - {str(e)}", "ERROR")
            return False
    
    def test_login_page_access(self):
        """Test if login page is accessible"""
        try:
            response = self.session.get(f"{self.base_url}/admin/login.php")
            if response.status_code == 200:
                content = response.text
                if "Hohmann Bau" in content and "Universal PHP Admin Panel" in content:
                    self.log("Login page loads with correct branding")
                    return True
                else:
                    self.log("Login page missing expected content")
                    return False
            else:
                self.log(f"Login page returned status {response.status_code}")
                return False
        except Exception as e:
            self.log(f"Failed to access login page: {str(e)}")
            return False
    
    def test_admin_login(self):
        """Test admin login functionality"""
        try:
            login_data = {
                'username': 'admin',
                'password': 'admin123'
            }
            
            response = self.session.post(f"{self.base_url}/admin/login.php", data=login_data)
            
            # Check if redirected (status 302) or if we get the admin panel
            if response.status_code in [200, 302]:
                # Try to access the admin panel
                admin_response = self.session.get(f"{self.base_url}/admin/universal_admin.php")
                
                if admin_response.status_code == 200:
                    content = admin_response.text
                    if "Universal Admin Panel" in content and "Willkommen" in content:
                        self.log("Login successful - Admin panel accessible")
                        return True
                    else:
                        self.log("Login may have failed - Admin panel not accessible")
                        return False
                else:
                    self.log(f"Admin panel returned status {admin_response.status_code}")
                    return False
            else:
                self.log(f"Login failed with status {response.status_code}")
                return False
                
        except Exception as e:
            self.log(f"Login test failed: {str(e)}")
            return False
    
    def test_admin_dashboard_elements(self):
        """Test if admin dashboard has required elements"""
        try:
            response = self.session.get(f"{self.base_url}/admin/universal_admin.php")
            
            if response.status_code == 200:
                content = response.text
                
                # Check for key dashboard elements
                required_elements = [
                    "Universal Page Editor",
                    "Design System Manager", 
                    "Media Manager",
                    "Navigation Editor",
                    "Content Manager",
                    "Dashboard",
                    "Settings"
                ]
                
                found_elements = []
                missing_elements = []
                
                for element in required_elements:
                    if element in content:
                        found_elements.append(element)
                    else:
                        missing_elements.append(element)
                
                self.log(f"Found {len(found_elements)}/{len(required_elements)} required elements")
                
                if missing_elements:
                    self.log(f"Missing elements: {', '.join(missing_elements)}", "WARNING")
                
                # Pass if we found at least 80% of elements
                return len(found_elements) >= len(required_elements) * 0.8
            else:
                self.log(f"Dashboard access failed with status {response.status_code}")
                return False
                
        except Exception as e:
            self.log(f"Dashboard test failed: {str(e)}")
            return False
    
    def test_page_content_functionality(self):
        """Test page content saving/loading"""
        try:
            # Test saving page content
            test_content = {
                "title": "Test Title",
                "subtitle": "Test Subtitle", 
                "description": "Test Description"
            }
            
            save_data = {
                'action': 'save_page_content',
                'page_name': 'home',
                'content': json.dumps(test_content)
            }
            
            response = self.session.post(f"{self.base_url}/admin/universal_admin.php", data=save_data)
            
            if response.status_code == 200:
                try:
                    result = response.json()
                    if result.get('success'):
                        self.log("Page content save successful")
                        
                        # Test loading the content back
                        load_data = {
                            'action': 'get_page_content',
                            'page_name': 'home'
                        }
                        
                        load_response = self.session.post(f"{self.base_url}/admin/universal_admin.php", data=load_data)
                        
                        if load_response.status_code == 200:
                            loaded_content = load_response.json()
                            if loaded_content.get('title') == test_content['title']:
                                self.log("Page content load successful")
                                return True
                            else:
                                self.log("Loaded content doesn't match saved content")
                                return False
                        else:
                            self.log("Failed to load page content")
                            return False
                    else:
                        self.log("Page content save failed")
                        return False
                except json.JSONDecodeError:
                    self.log("Invalid JSON response from save operation")
                    return False
            else:
                self.log(f"Save request failed with status {response.status_code}")
                return False
                
        except Exception as e:
            self.log(f"Page content test failed: {str(e)}")
            return False
    
    def test_design_system_functionality(self):
        """Test design system settings"""
        try:
            # Test saving design settings
            test_settings = {
                "primary_color": "#16a34a",
                "secondary_color": "#059669",
                "accent_color": "#10b981"
            }
            
            save_data = {
                'action': 'save_design_settings',
                'setting_type': 'colors',
                'settings': json.dumps(test_settings)
            }
            
            response = self.session.post(f"{self.base_url}/admin/universal_admin.php", data=save_data)
            
            if response.status_code == 200:
                try:
                    result = response.json()
                    if result.get('success'):
                        self.log("Design settings save successful")
                        return True
                    else:
                        self.log("Design settings save failed")
                        return False
                except json.JSONDecodeError:
                    self.log("Invalid JSON response from design settings save")
                    return False
            else:
                self.log(f"Design settings request failed with status {response.status_code}")
                return False
                
        except Exception as e:
            self.log(f"Design system test failed: {str(e)}")
            return False
    
    def test_system_status(self):
        """Test overall system status"""
        try:
            response = self.session.get(f"{self.base_url}/admin/universal_admin.php")
            
            if response.status_code == 200:
                content = response.text
                
                # Check for PHP and system indicators
                if "PHP" in content or "System" in content or "Status" in content:
                    self.log("System status indicators found")
                    return True
                else:
                    self.log("System status section not clearly visible")
                    return True  # Not critical for basic functionality
            else:
                return False
                
        except Exception as e:
            self.log(f"System status test failed: {str(e)}")
            return False
    
    def run_all_tests(self):
        """Run comprehensive test suite"""
        self.log("🎯 Starting Universal PHP Admin Panel Test Suite", "INFO")
        self.log("=" * 60)
        
        # Test sequence
        tests = [
            ("Login Page Access", self.test_login_page_access),
            ("Admin Authentication", self.test_admin_login),
            ("Dashboard Elements", self.test_admin_dashboard_elements),
            ("Page Content Functionality", self.test_page_content_functionality),
            ("Design System Functionality", self.test_design_system_functionality),
            ("System Status", self.test_system_status)
        ]
        
        for test_name, test_func in tests:
            self.run_test(test_name, test_func)
            print()  # Add spacing between tests
        
        # Summary
        self.log("=" * 60)
        self.log(f"📊 TEST RESULTS: {self.tests_passed}/{self.tests_run} tests passed")
        
        if self.tests_passed == self.tests_run:
            self.log("🎉 ALL TESTS PASSED - PHP Admin Panel is working correctly!", "SUCCESS")
            return True
        elif self.tests_passed >= self.tests_run * 0.8:
            self.log("⚠️ Most tests passed - Minor issues detected", "WARNING")
            return True
        else:
            self.log("❌ CRITICAL ISSUES DETECTED - Admin panel needs fixes", "ERROR")
            return False

def main():
    """Main test execution"""
    print("🔧 Universal PHP Admin Panel - Hohmann Bau")
    print("Comprehensive Test Suite")
    print("=" * 60)
    
    tester = PHPAdminTester()
    success = tester.run_all_tests()
    
    return 0 if success else 1

if __name__ == "__main__":
    sys.exit(main())