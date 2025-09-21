#!/usr/bin/env python3
"""
Backend API Test for Hohmann Bau CMS System
Tests the PHP-based API endpoints directly
"""

import requests
import json
import sys
from datetime import datetime

class HohmannBackendTest:
    def __init__(self, base_url="http://localhost/Hohmannbau"):
        self.base_url = base_url
        self.api_url = f"{base_url}/api/index.php"
        self.admin_url = f"{base_url}/admin"
        
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
        
    def run_test(self, name, method, url, expected_status=200, data=None):
        """Run a single test"""
        self.tests_run += 1
        self.log(f"Testing {name}...")
        
        try:
            if method == 'GET':
                response = self.session.get(url)
            elif method == 'POST':
                response = self.session.post(url, data=data)
            
            success = response.status_code == expected_status
            
            if success:
                self.tests_passed += 1
                self.log(f"✅ {name} - Status: {response.status_code}", "SUCCESS")
                
                # Try to parse JSON if possible
                try:
                    json_data = response.json()
                    if isinstance(json_data, list):
                        self.log(f"   Returned {len(json_data)} items")
                    elif isinstance(json_data, dict):
                        self.log(f"   Returned data with keys: {list(json_data.keys())}")
                except:
                    self.log(f"   Response length: {len(response.text)} chars")
                    
                return True, response
            else:
                self.log(f"❌ {name} - Expected {expected_status}, got {response.status_code}", "ERROR")
                self.log(f"   Response: {response.text[:200]}...")
                return False, response
                
        except Exception as e:
            self.log(f"❌ {name} - Exception: {str(e)}", "ERROR")
            return False, None
    
    def test_api_endpoints(self):
        """Test all API endpoints"""
        self.log("🔌 Testing API Endpoints")
        
        endpoints = [
            ("Services API", "GET", f"{self.api_url}/services"),
            ("Team API", "GET", f"{self.api_url}/team"),
            ("Projects API", "GET", f"{self.api_url}/projects"),
            ("Features API", "GET", f"{self.api_url}/features"),
            ("Contact Info API", "GET", f"{self.api_url}/contact-info"),
            ("Health API", "GET", f"{self.api_url}/health"),
        ]
        
        for name, method, url in endpoints:
            self.run_test(name, method, url)
    
    def test_content_api(self):
        """Test content API with different pages"""
        self.log("📄 Testing Content API")
        
        pages = ["home", "services", "team", "projects", "contact"]
        
        for page in pages:
            self.run_test(f"Content API - {page}", "GET", f"{self.api_url}/content?page={page}")
    
    def test_frontend_pages(self):
        """Test frontend PHP pages"""
        self.log("🌐 Testing Frontend Pages")
        
        pages = [
            ("Homepage", f"{self.base_url}/index.php"),
            ("Services Page", f"{self.base_url}/leistungen.php"),
            ("Team Page", f"{self.base_url}/team.php"),
            ("Projects Page", f"{self.base_url}/projekte.php"),
            ("Contact Page", f"{self.base_url}/kontakt.php"),
        ]
        
        for name, url in pages:
            success, response = self.run_test(name, "GET", url)
            if success and response:
                # Check for basic content
                content = response.text.lower()
                if "hohmann bau" in content and "error" not in content:
                    self.log(f"   ✅ {name} contains expected content")
                else:
                    self.log(f"   ⚠️ {name} may have content issues")
    
    def test_admin_login_page(self):
        """Test admin login page accessibility"""
        self.log("🔐 Testing Admin Login Page")
        
        success, response = self.run_test("Admin Login Page", "GET", f"{self.admin_url}/login.php")
        if success and response:
            content = response.text.lower()
            if "login" in content and "username" in content and "password" in content:
                self.log("   ✅ Login page contains expected form elements")
                return True
            else:
                self.log("   ❌ Login page missing form elements")
                return False
        return False
    
    def test_admin_login_functionality(self):
        """Test admin login functionality"""
        self.log("🔑 Testing Admin Login Functionality")
        
        # First get the login page to establish session
        self.session.get(f"{self.admin_url}/login.php")
        
        # Attempt login
        login_data = {
            'username': 'admin',
            'password': 'admin123'
        }
        
        success, response = self.run_test("Admin Login Submit", "POST", f"{self.admin_url}/login.php", data=login_data)
        
        if success:
            # Try to access admin panel
            admin_success, admin_response = self.run_test("Admin Panel Access", "GET", f"{self.admin_url}/universal_admin.php")
            
            if admin_success and admin_response:
                content = admin_response.text.lower()
                if "universal admin panel" in content or "dashboard" in content:
                    self.log("   ✅ Successfully logged into admin panel")
                    return True
                else:
                    self.log("   ❌ Admin panel not accessible after login")
                    return False
        
        return False
    
    def test_database_connection(self):
        """Test if database connection is working by checking API responses"""
        self.log("🗄️ Testing Database Connection")
        
        # Test services endpoint - should return data if DB is connected
        success, response = self.run_test("Database Connection Test", "GET", f"{self.api_url}/services")
        
        if success and response:
            try:
                data = response.json()
                if isinstance(data, list):
                    self.log(f"   ✅ Database connection working - found {len(data)} services")
                    return True
                else:
                    self.log("   ⚠️ Database connection unclear - unexpected response format")
                    return False
            except:
                self.log("   ❌ Database connection failed - invalid JSON response")
                return False
        
        return False
    
    def run_all_tests(self):
        """Run all backend tests"""
        self.log("🎯 HOHMANN BAU BACKEND TEST SUITE", "SUCCESS")
        self.log("=" * 60)
        
        # Run all test categories
        self.test_database_connection()
        print()
        
        self.test_api_endpoints()
        print()
        
        self.test_content_api()
        print()
        
        self.test_frontend_pages()
        print()
        
        self.test_admin_login_page()
        print()
        
        self.test_admin_login_functionality()
        print()
        
        # Final results
        self.log("=" * 60)
        self.log(f"📊 BACKEND TEST RESULTS: {self.tests_passed}/{self.tests_run} tests passed")
        
        success_rate = self.tests_passed / self.tests_run if self.tests_run > 0 else 0
        
        if success_rate >= 0.8:
            self.log("🎉 EXCELLENT! Backend is working well", "SUCCESS")
            return True
        elif success_rate >= 0.6:
            self.log("✅ GOOD! Backend is mostly functional", "SUCCESS")
            return True
        else:
            self.log("❌ ISSUES! Backend has significant problems", "ERROR")
            return False

def main():
    """Main test execution"""
    print("🏗️ HOHMANN BAU BACKEND TEST SUITE")
    print("Testing PHP-based CMS Backend")
    print("=" * 60)
    
    tester = HohmannBackendTest()
    success = tester.run_all_tests()
    
    return 0 if success else 1

if __name__ == "__main__":
    sys.exit(main())