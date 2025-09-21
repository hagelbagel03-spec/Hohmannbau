#!/usr/bin/env python3
"""
FINALER VOLLSTÄNDIGER TEST - Hohmann Bau KOMPLETTES CMS SYSTEM
Tests all functionality as requested in the German review request:

1. ADMIN PANEL → DATABASE PERSISTIERUNG
2. DATABASE → FRONTEND ANZEIGE  
3. LIVE CONTENT MANAGEMENT (CMS-Test)
4. API ENDPUNKT TESTS
5. KOMPLETTES CMS WORKFLOW

Testing URLs:
- Admin Panel: http://localhost/Hohmannbau/admin/universal_admin.php
- Frontend: http://localhost/Hohmannbau/index.php, leistungen.php, team.php, projekte.php
- API: /api/index.php/services, /api/index.php/team, etc.
"""

import requests
import json
import sys
import time
from datetime import datetime

class HohmannCMSTest:
    def __init__(self, base_url="http://localhost"):
        self.base_url = base_url
        self.hohmann_path = "/Hohmannbau"
        self.admin_url = f"{base_url}{self.hohmann_path}/admin"
        self.frontend_url = f"{base_url}{self.hohmann_path}"
        self.api_url = f"{base_url}{self.hohmann_path}/api/index.php"
        
        self.session = requests.Session()
        self.tests_run = 0
        self.tests_passed = 0
        self.critical_failures = []
        self.success_results = []
        
    def log(self, message, status="INFO"):
        timestamp = datetime.now().strftime("%H:%M:%S")
        status_emoji = {
            "INFO": "ℹ️",
            "SUCCESS": "✅", 
            "ERROR": "❌",
            "WARNING": "⚠️",
            "CRITICAL": "🚨"
        }
        print(f"[{timestamp}] {status_emoji.get(status, 'ℹ️')} {message}")
        
    def mark_result(self, test_name, passed=True, details=""):
        """Mark test result"""
        status = "✅" if passed else "❌"
        result = f"{status} {test_name}"
        if details:
            result += f" - {details}"
            
        if passed:
            self.success_results.append(result)
            self.tests_passed += 1
        else:
            self.critical_failures.append(result)
        
        self.tests_run += 1
        
    def test_admin_login(self):
        """Test 1: Login with admin/admin123"""
        self.log("🔐 Testing Admin Login (admin/admin123)")
        
        try:
            # First get login page
            login_page = self.session.get(f"{self.admin_url}/login.php")
            if login_page.status_code != 200:
                self.mark_result("Admin Login Page Access", False, f"Status: {login_page.status_code}")
                return False
            
            # Attempt login
            login_data = {
                'username': 'admin',
                'password': 'admin123'
            }
            
            login_response = self.session.post(f"{self.admin_url}/login.php", data=login_data)
            
            # Check if we can access admin panel
            admin_panel = self.session.get(f"{self.admin_url}/universal_admin.php")
            
            if admin_panel.status_code == 200 and "Universal Admin Panel" in admin_panel.text:
                self.mark_result("Admin Login (admin/admin123)", True, "Successfully logged in")
                return True
            else:
                self.mark_result("Admin Login (admin/admin123)", False, f"Login failed or panel inaccessible")
                return False
                
        except Exception as e:
            self.mark_result("Admin Login (admin/admin123)", False, f"Exception: {str(e)}")
            return False
    
    def test_services_manager_database_persistence(self):
        """Test 2: Services Manager → Database Persistence"""
        self.log("🔧 Testing Services Manager → Database Persistence")
        
        try:
            # Test editing a service
            service_data = {
                'action': 'save_service',
                'service_id': 'hochbau_test',
                'title': 'Hochbau GEÄNDERT',
                'description': 'Test description for automated testing',
                'features': '["Test feature 1", "Test feature 2"]',
                'icon': '🏗️',
                'image': 'test.jpg',
                'is_active': 1
            }
            
            response = self.session.post(f"{self.admin_url}/universal_admin.php", data=service_data)
            
            if response.status_code == 200:
                try:
                    result = response.json()
                    if result.get('success'):
                        self.mark_result("Services Manager → Database Save", True, "Service saved successfully")
                        return True
                    else:
                        self.mark_result("Services Manager → Database Save", False, f"Save failed: {result.get('error', 'Unknown error')}")
                        return False
                except json.JSONDecodeError:
                    self.mark_result("Services Manager → Database Save", False, "Invalid JSON response")
                    return False
            else:
                self.mark_result("Services Manager → Database Save", False, f"HTTP {response.status_code}")
                return False
                
        except Exception as e:
            self.mark_result("Services Manager → Database Save", False, f"Exception: {str(e)}")
            return False
    
    def test_team_manager_add_member(self):
        """Test 3: Team Manager → Add New Team Member"""
        self.log("👥 Testing Team Manager → Add New Team Member")
        
        try:
            # Add new team member
            member_data = {
                'action': 'save_team_member',
                'member_id': f'test_member_{int(time.time())}',
                'name': 'Test Mitarbeiter',
                'position': 'Test Position',
                'role': 'Test Position',
                'bio': 'This is a test team member created by automated testing.',
                'image_url': 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400'
            }
            
            response = self.session.post(f"{self.admin_url}/universal_admin.php", data=member_data)
            
            if response.status_code == 200:
                try:
                    result = response.json()
                    if result.get('success'):
                        self.mark_result("Team Manager → Add New Member", True, "Team member added successfully")
                        return True
                    else:
                        self.mark_result("Team Manager → Add New Member", False, f"Add failed: {result.get('error', 'Unknown error')}")
                        return False
                except json.JSONDecodeError:
                    self.mark_result("Team Manager → Add New Member", False, "Invalid JSON response")
                    return False
            else:
                self.mark_result("Team Manager → Add New Member", False, f"HTTP {response.status_code}")
                return False
                
        except Exception as e:
            self.mark_result("Team Manager → Add New Member", False, f"Exception: {str(e)}")
            return False
    
    def test_api_endpoints(self):
        """Test 4: Direct API Endpoint Tests"""
        self.log("🔌 Testing API Endpoints")
        
        endpoints_to_test = [
            ('/services', 'Services API'),
            ('/team', 'Team API'),
            ('/projects', 'Projects API'),
            ('/content/home', 'Content API - Home'),
            ('/features', 'Features API')
        ]
        
        api_results = []
        
        for endpoint, name in endpoints_to_test:
            try:
                response = self.session.get(f"{self.api_url}{endpoint}")
                
                if response.status_code == 200:
                    try:
                        data = response.json()
                        api_results.append(f"✅ {name}: Working (returned {len(data) if isinstance(data, list) else 'data'})")
                    except json.JSONDecodeError:
                        api_results.append(f"❌ {name}: Invalid JSON response")
                else:
                    api_results.append(f"❌ {name}: HTTP {response.status_code}")
                    
            except Exception as e:
                api_results.append(f"❌ {name}: Exception - {str(e)}")
        
        # Count successful API calls
        successful_apis = sum(1 for result in api_results if result.startswith("✅"))
        total_apis = len(endpoints_to_test)
        
        if successful_apis >= total_apis * 0.8:  # 80% success rate
            self.mark_result("API Endpoints", True, f"{successful_apis}/{total_apis} endpoints working")
        else:
            self.mark_result("API Endpoints", False, f"Only {successful_apis}/{total_apis} endpoints working")
        
        # Log individual results
        for result in api_results:
            print(f"    {result}")
        
        return successful_apis >= total_apis * 0.8
    
    def test_frontend_pages_load(self):
        """Test 5: Frontend Pages Load Correctly"""
        self.log("🌐 Testing Frontend Pages Load")
        
        pages_to_test = [
            ('/index.php', 'Homepage'),
            ('/leistungen.php', 'Services Page'),
            ('/team.php', 'Team Page'),
            ('/projekte.php', 'Projects Page')
        ]
        
        page_results = []
        
        for page_path, page_name in pages_to_test:
            try:
                response = self.session.get(f"{self.frontend_url}{page_path}")
                
                if response.status_code == 200:
                    # Check for basic content indicators
                    content = response.text.lower()
                    if "hohmann bau" in content and not "error" in content and not "fatal" in content:
                        page_results.append(f"✅ {page_name}: Loading correctly")
                    else:
                        page_results.append(f"⚠️ {page_name}: Loads but may have issues")
                else:
                    page_results.append(f"❌ {page_name}: HTTP {response.status_code}")
                    
            except Exception as e:
                page_results.append(f"❌ {page_name}: Exception - {str(e)}")
        
        # Count successful page loads
        successful_pages = sum(1 for result in page_results if result.startswith("✅"))
        total_pages = len(pages_to_test)
        
        if successful_pages >= total_pages * 0.75:  # 75% success rate
            self.mark_result("Frontend Pages Load", True, f"{successful_pages}/{total_pages} pages loading")
        else:
            self.mark_result("Frontend Pages Load", False, f"Only {successful_pages}/{total_pages} pages loading")
        
        # Log individual results
        for result in page_results:
            print(f"    {result}")
        
        return successful_pages >= total_pages * 0.75
    
    def test_database_to_frontend_integration(self):
        """Test 6: Database → Frontend Display Integration"""
        self.log("🔄 Testing Database → Frontend Display Integration")
        
        try:
            # Check if services from database appear on frontend
            services_response = self.session.get(f"{self.api_url}/services")
            leistungen_page = self.session.get(f"{self.frontend_url}/leistungen.php")
            
            services_working = False
            team_working = False
            
            # Test Services Integration
            if services_response.status_code == 200 and leistungen_page.status_code == 200:
                try:
                    services_data = services_response.json()
                    if services_data and len(services_data) > 0:
                        # Check if any service title appears on the page
                        page_content = leistungen_page.text.lower()
                        for service in services_data[:3]:  # Check first 3 services
                            if service.get('title', '').lower() in page_content:
                                services_working = True
                                break
                except:
                    pass
            
            # Test Team Integration
            team_response = self.session.get(f"{self.api_url}/team")
            team_page = self.session.get(f"{self.frontend_url}/team.php")
            
            if team_response.status_code == 200 and team_page.status_code == 200:
                try:
                    team_data = team_response.json()
                    if team_data and len(team_data) > 0:
                        # Check if any team member name appears on the page
                        page_content = team_page.text.lower()
                        for member in team_data[:3]:  # Check first 3 members
                            if member.get('name', '').lower() in page_content:
                                team_working = True
                                break
                except:
                    pass
            
            # Evaluate results
            if services_working and team_working:
                self.mark_result("Database → Frontend Integration", True, "Services and Team data displaying correctly")
                return True
            elif services_working or team_working:
                self.mark_result("Database → Frontend Integration", True, "Partial integration working")
                return True
            else:
                self.mark_result("Database → Frontend Integration", False, "No database data visible on frontend")
                return False
                
        except Exception as e:
            self.mark_result("Database → Frontend Integration", False, f"Exception: {str(e)}")
            return False
    
    def test_live_cms_functionality(self):
        """Test 7: Live CMS Functionality (Admin Changes → Frontend)"""
        self.log("🎯 Testing Live CMS Functionality")
        
        try:
            # Create a unique test string
            test_string = f"TEST_CMS_{int(time.time())}"
            
            # Update homepage content via admin
            content_data = {
                'action': 'save_page_content',
                'page_name': 'home',
                'content': json.dumps({
                    'hero_title': f'Bauen mit Vertrauen {test_string}',
                    'hero_subtitle': 'Test subtitle for CMS functionality'
                })
            }
            
            admin_response = self.session.post(f"{self.admin_url}/universal_admin.php", data=content_data)
            
            if admin_response.status_code == 200:
                try:
                    result = admin_response.json()
                    if result.get('success'):
                        # Wait a moment for changes to propagate
                        time.sleep(2)
                        
                        # Check if changes appear on frontend
                        frontend_response = self.session.get(f"{self.frontend_url}/index.php")
                        
                        if frontend_response.status_code == 200:
                            if test_string in frontend_response.text:
                                self.mark_result("Live CMS Functionality", True, "Admin changes visible on frontend")
                                return True
                            else:
                                self.mark_result("Live CMS Functionality", False, "Admin changes not visible on frontend")
                                return False
                        else:
                            self.mark_result("Live CMS Functionality", False, "Frontend page not accessible")
                            return False
                    else:
                        self.mark_result("Live CMS Functionality", False, "Admin save failed")
                        return False
                except json.JSONDecodeError:
                    self.mark_result("Live CMS Functionality", False, "Invalid admin response")
                    return False
            else:
                self.mark_result("Live CMS Functionality", False, f"Admin request failed: HTTP {admin_response.status_code}")
                return False
                
        except Exception as e:
            self.mark_result("Live CMS Functionality", False, f"Exception: {str(e)}")
            return False
    
    def test_admin_panel_sections(self):
        """Test 8: Admin Panel Sections Load"""
        self.log("🎛️ Testing Admin Panel Sections")
        
        try:
            admin_response = self.session.get(f"{self.admin_url}/universal_admin.php")
            
            if admin_response.status_code == 200:
                content = admin_response.text
                
                # Check for key admin sections
                sections = [
                    "Universal Page Editor",
                    "Design System",
                    "Media Manager", 
                    "Services Manager",
                    "Team Manager",
                    "Dashboard"
                ]
                
                found_sections = []
                for section in sections:
                    if section in content:
                        found_sections.append(section)
                
                if len(found_sections) >= len(sections) * 0.8:  # 80% of sections found
                    self.mark_result("Admin Panel Sections", True, f"{len(found_sections)}/{len(sections)} sections found")
                    return True
                else:
                    self.mark_result("Admin Panel Sections", False, f"Only {len(found_sections)}/{len(sections)} sections found")
                    return False
            else:
                self.mark_result("Admin Panel Sections", False, f"Admin panel not accessible: HTTP {admin_response.status_code}")
                return False
                
        except Exception as e:
            self.mark_result("Admin Panel Sections", False, f"Exception: {str(e)}")
            return False
    
    def run_comprehensive_test(self):
        """Run all comprehensive tests"""
        self.log("🎯 FINALER VOLLSTÄNDIGER TEST - Hohmann Bau KOMPLETTES CMS SYSTEM", "CRITICAL")
        self.log("Testing complete CMS workflow as requested in German review")
        self.log("=" * 80)
        
        # Run all tests in sequence
        tests = [
            ("Admin Login (admin/admin123)", self.test_admin_login),
            ("Admin Panel Sections Load", self.test_admin_panel_sections),
            ("Services Manager → Database", self.test_services_manager_database_persistence),
            ("Team Manager → Add Member", self.test_team_manager_add_member),
            ("API Endpoints", self.test_api_endpoints),
            ("Frontend Pages Load", self.test_frontend_pages_load),
            ("Database → Frontend Integration", self.test_database_to_frontend_integration),
            ("Live CMS Functionality", self.test_live_cms_functionality)
        ]
        
        for test_name, test_func in tests:
            self.log(f"🔍 Running: {test_name}")
            test_func()
            print()
        
        # Final Results
        self.log("=" * 80)
        self.log("🎯 COMPREHENSIVE TEST RESULTS:", "CRITICAL")
        self.log("=" * 80)
        
        # Show successful tests first
        if self.success_results:
            self.log("✅ SUCCESSFUL TESTS:")
            for result in self.success_results:
                print(f"    {result}")
            print()
        
        # Show failed tests
        if self.critical_failures:
            self.log("❌ FAILED TESTS:")
            for failure in self.critical_failures:
                print(f"    {failure}")
            print()
        
        self.log(f"📊 OVERALL RESULTS: {self.tests_passed}/{self.tests_run} tests passed")
        
        success_rate = self.tests_passed / self.tests_run if self.tests_run > 0 else 0
        
        if success_rate >= 0.9:
            self.log("🎉 EXCELLENT! CMS System is fully functional", "SUCCESS")
            return True
        elif success_rate >= 0.75:
            self.log("✅ GOOD! CMS System is mostly functional with minor issues", "SUCCESS")
            return True
        elif success_rate >= 0.5:
            self.log("⚠️ PARTIAL! CMS System has significant issues that need fixing", "WARNING")
            return False
        else:
            self.log("❌ CRITICAL! CMS System has major failures", "ERROR")
            return False

def main():
    """Main test execution"""
    print("🚨 FINALER VOLLSTÄNDIGER TEST - Hohmann Bau KOMPLETTES CMS SYSTEM")
    print("Testing Admin Panel → Database → Frontend Integration")
    print("=" * 80)
    
    tester = HohmannCMSTest()
    success = tester.run_comprehensive_test()
    
    return 0 if success else 1

if __name__ == "__main__":
    sys.exit(main())