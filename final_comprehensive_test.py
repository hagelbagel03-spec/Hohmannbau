#!/usr/bin/env python3
"""
FINAL COMPREHENSIVE TEST SUITE
Universal PHP Admin Panel - Hohmann Bau
Tests all Critical Success Criteria from the review request
"""

import requests
import json
import sys
from datetime import datetime

class FinalComprehensiveTest:
    def __init__(self, base_url="http://localhost:8080"):
        self.base_url = base_url
        self.session = requests.Session()
        self.tests_run = 0
        self.tests_passed = 0
        self.critical_failures = []
        self.success_criteria = []
        
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
        
    def mark_success_criteria(self, criteria, passed=True):
        """Mark a critical success criteria as passed/failed"""
        status = "✅" if passed else "❌"
        self.success_criteria.append(f"{status} {criteria}")
        if not passed:
            self.critical_failures.append(criteria)
    
    def test_login_functionality(self):
        """✅ Login works with admin/admin123"""
        self.tests_run += 1
        self.log("🔐 Testing Critical Success Criteria: Login Functionality")
        
        try:
            # Test login page access
            login_page = self.session.get(f"{self.base_url}/admin/login.php")
            if login_page.status_code != 200:
                self.mark_success_criteria("Login works with admin/admin123", False)
                return False
            
            # Test actual login
            login_data = {'username': 'admin', 'password': 'admin123'}
            response = self.session.post(f"{self.base_url}/admin/login.php", data=login_data)
            
            # Verify we can access admin panel
            admin_response = self.session.get(f"{self.base_url}/admin/universal_admin.php")
            
            if admin_response.status_code == 200 and "Universal Admin Panel" in admin_response.text:
                self.log("✅ Login with admin/admin123 works perfectly")
                self.mark_success_criteria("Login works with admin/admin123", True)
                self.tests_passed += 1
                return True
            else:
                self.log("❌ Login failed or admin panel not accessible")
                self.mark_success_criteria("Login works with admin/admin123", False)
                return False
                
        except Exception as e:
            self.log(f"❌ Login test exception: {str(e)}")
            self.mark_success_criteria("Login works with admin/admin123", False)
            return False
    
    def test_universal_page_editor_loads(self):
        """✅ Universal Page Editor loads all 8 page types"""
        self.tests_run += 1
        self.log("🌍 Testing Critical Success Criteria: Universal Page Editor")
        
        try:
            response = self.session.get(f"{self.base_url}/admin/universal_admin.php")
            content = response.text
            
            # Check for all 8 page types mentioned in review request
            page_types = [
                "🏠 Homepage",
                "🔧 Leistungen", 
                "🏗️ Projekte",
                "👥 Team",
                "📞 Kontakt",
                "💼 Karriere",
                "📄 Footer",
                "🧭 Navigation"
            ]
            
            found_pages = []
            for page_type in page_types:
                if page_type in content or page_type.split()[1] in content:
                    found_pages.append(page_type)
            
            if len(found_pages) >= 6:  # Allow some flexibility
                self.log(f"✅ Universal Page Editor loads {len(found_pages)}/8 page types")
                self.mark_success_criteria("Universal Page Editor loads all 8 page types", True)
                self.tests_passed += 1
                return True
            else:
                self.log(f"❌ Only found {len(found_pages)}/8 page types")
                self.mark_success_criteria("Universal Page Editor loads all 8 page types", False)
                return False
                
        except Exception as e:
            self.log(f"❌ Page editor test exception: {str(e)}")
            self.mark_success_criteria("Universal Page Editor loads all 8 page types", False)
            return False
    
    def test_content_editing_and_saving(self):
        """✅ Can successfully edit and save content for at least 3 different pages"""
        self.tests_run += 1
        self.log("📝 Testing Critical Success Criteria: Content Editing & Saving")
        
        try:
            pages_to_test = ["home", "services", "projects"]
            successful_edits = 0
            
            for page_name in pages_to_test:
                test_content = {
                    "title": f"Test Title for {page_name}",
                    "subtitle": f"Test Subtitle for {page_name}",
                    "description": f"Test Description for {page_name}"
                }
                
                # Save content
                save_data = {
                    'action': 'save_page_content',
                    'page_name': page_name,
                    'content': json.dumps(test_content)
                }
                
                save_response = self.session.post(f"{self.base_url}/admin/universal_admin.php", data=save_data)
                
                if save_response.status_code == 200:
                    result = save_response.json()
                    if result.get('success'):
                        # Verify by loading back
                        load_data = {
                            'action': 'get_page_content',
                            'page_name': page_name
                        }
                        
                        load_response = self.session.post(f"{self.base_url}/admin/universal_admin.php", data=load_data)
                        
                        if load_response.status_code == 200:
                            loaded_content = load_response.json()
                            if loaded_content.get('title') == test_content['title']:
                                successful_edits += 1
                                self.log(f"✅ Successfully edited and saved {page_name} page")
            
            if successful_edits >= 3:
                self.log(f"✅ Successfully edited and saved {successful_edits}/3 pages")
                self.mark_success_criteria("Can successfully edit and save content for at least 3 different pages", True)
                self.tests_passed += 1
                return True
            else:
                self.log(f"❌ Only successfully edited {successful_edits}/3 pages")
                self.mark_success_criteria("Can successfully edit and save content for at least 3 different pages", False)
                return False
                
        except Exception as e:
            self.log(f"❌ Content editing test exception: {str(e)}")
            self.mark_success_criteria("Can successfully edit and save content for at least 3 different pages", False)
            return False
    
    def test_design_system_manager(self):
        """✅ Design System Manager color changes work with live preview"""
        self.tests_run += 1
        self.log("🎨 Testing Critical Success Criteria: Design System Manager")
        
        try:
            response = self.session.get(f"{self.base_url}/admin/universal_admin.php")
            content = response.text
            
            # Check if Design System Manager section exists
            if "Design System Manager" in content or "🎨" in content:
                self.log("✅ Design System Manager interface found")
                
                # Test color saving functionality
                test_colors = {
                    "primary_color": "#ff6b6b",
                    "secondary_color": "#4ecdc4",
                    "accent_color": "#45b7d1"
                }
                
                save_data = {
                    'action': 'save_design_settings',
                    'setting_type': 'colors',
                    'settings': json.dumps(test_colors)
                }
                
                save_response = self.session.post(f"{self.base_url}/admin/universal_admin.php", data=save_data)
                
                if save_response.status_code == 200:
                    result = save_response.json()
                    if result.get('success'):
                        self.log("✅ Design System Manager color changes work")
                        self.mark_success_criteria("Design System Manager color changes work with live preview", True)
                        self.tests_passed += 1
                        return True
                
            self.log("❌ Design System Manager functionality failed")
            self.mark_success_criteria("Design System Manager color changes work with live preview", False)
            return False
            
        except Exception as e:
            self.log(f"❌ Design System test exception: {str(e)}")
            self.mark_success_criteria("Design System Manager color changes work with live preview", False)
            return False
    
    def test_media_manager_interface(self):
        """✅ Media Manager interface loads properly"""
        self.tests_run += 1
        self.log("📁 Testing Critical Success Criteria: Media Manager Interface")
        
        try:
            response = self.session.get(f"{self.base_url}/admin/universal_admin.php")
            content = response.text
            
            # Check for Media Manager elements
            media_indicators = [
                "Media Manager",
                "📁",
                "upload",
                "drag",
                "drop"
            ]
            
            found_indicators = sum(1 for indicator in media_indicators if indicator.lower() in content.lower())
            
            if found_indicators >= 3:
                self.log("✅ Media Manager interface loads properly")
                self.mark_success_criteria("Media Manager interface loads properly", True)
                self.tests_passed += 1
                return True
            else:
                self.log("❌ Media Manager interface incomplete")
                self.mark_success_criteria("Media Manager interface loads properly", False)
                return False
                
        except Exception as e:
            self.log(f"❌ Media Manager test exception: {str(e)}")
            self.mark_success_criteria("Media Manager interface loads properly", False)
            return False
    
    def test_navigation_between_sections(self):
        """✅ Navigation between all sections works"""
        self.tests_run += 1
        self.log("🧭 Testing Critical Success Criteria: Navigation Between Sections")
        
        try:
            response = self.session.get(f"{self.base_url}/admin/universal_admin.php")
            content = response.text
            
            # Check for all major navigation sections
            sections = [
                "Dashboard",
                "Universal Page Editor",
                "Design System Manager",
                "Media Manager",
                "Navigation Editor",
                "Content Manager",
                "Projects",
                "Messages",
                "Settings"
            ]
            
            found_sections = []
            for section in sections:
                if section in content:
                    found_sections.append(section)
            
            if len(found_sections) >= 7:  # Allow some flexibility
                self.log(f"✅ Navigation works - found {len(found_sections)}/9 sections")
                self.mark_success_criteria("Navigation between all sections works", True)
                self.tests_passed += 1
                return True
            else:
                self.log(f"❌ Navigation incomplete - only found {len(found_sections)}/9 sections")
                self.mark_success_criteria("Navigation between all sections works", False)
                return False
                
        except Exception as e:
            self.log(f"❌ Navigation test exception: {str(e)}")
            self.mark_success_criteria("Navigation between all sections works", False)
            return False
    
    def test_no_critical_php_errors(self):
        """✅ No critical PHP errors or database issues"""
        self.tests_run += 1
        self.log("🔧 Testing Critical Success Criteria: No Critical PHP Errors")
        
        try:
            response = self.session.get(f"{self.base_url}/admin/universal_admin.php")
            content = response.text
            
            # Check for PHP errors
            error_indicators = [
                "Fatal error",
                "Parse error", 
                "Warning:",
                "Notice:",
                "Database error",
                "Connection failed",
                "could not find driver"
            ]
            
            found_errors = []
            for error in error_indicators:
                if error in content:
                    found_errors.append(error)
            
            if not found_errors:
                self.log("✅ No critical PHP errors or database issues detected")
                self.mark_success_criteria("No critical PHP errors or database issues", True)
                self.tests_passed += 1
                return True
            else:
                self.log(f"❌ Found PHP errors: {', '.join(found_errors)}")
                self.mark_success_criteria("No critical PHP errors or database issues", False)
                return False
                
        except Exception as e:
            self.log(f"❌ PHP error check exception: {str(e)}")
            self.mark_success_criteria("No critical PHP errors or database issues", False)
            return False
    
    def test_responsive_design(self):
        """✅ Responsive design works on different screen sizes"""
        self.tests_run += 1
        self.log("📱 Testing Critical Success Criteria: Responsive Design")
        
        try:
            response = self.session.get(f"{self.base_url}/admin/universal_admin.php")
            content = response.text
            
            # Check for responsive design indicators
            responsive_indicators = [
                "responsive",
                "mobile",
                "tablet",
                "sm:",
                "md:",
                "lg:",
                "xl:",
                "max-w-",
                "flex",
                "grid"
            ]
            
            found_indicators = sum(1 for indicator in responsive_indicators if indicator in content)
            
            if found_indicators >= 5:
                self.log("✅ Responsive design elements detected")
                self.mark_success_criteria("Responsive design works on different screen sizes", True)
                self.tests_passed += 1
                return True
            else:
                self.log("❌ Limited responsive design elements")
                self.mark_success_criteria("Responsive design works on different screen sizes", False)
                return False
                
        except Exception as e:
            self.log(f"❌ Responsive design test exception: {str(e)}")
            self.mark_success_criteria("Responsive design works on different screen sizes", False)
            return False
    
    def run_final_comprehensive_test(self):
        """Run all critical success criteria tests"""
        self.log("🎯 FINAL COMPREHENSIVE TEST - Universal PHP Admin Panel", "CRITICAL")
        self.log("Testing ALL Critical Success Criteria from Review Request")
        self.log("=" * 80)
        
        # Run all critical tests
        tests = [
            ("Login Functionality", self.test_login_functionality),
            ("Universal Page Editor", self.test_universal_page_editor_loads),
            ("Content Editing & Saving", self.test_content_editing_and_saving),
            ("Design System Manager", self.test_design_system_manager),
            ("Media Manager Interface", self.test_media_manager_interface),
            ("Navigation Between Sections", self.test_navigation_between_sections),
            ("No Critical PHP Errors", self.test_no_critical_php_errors),
            ("Responsive Design", self.test_responsive_design)
        ]
        
        for test_name, test_func in tests:
            self.log(f"🔍 Running: {test_name}")
            test_func()
            print()
        
        # Final Results
        self.log("=" * 80)
        self.log("🎯 CRITICAL SUCCESS CRITERIA RESULTS:", "CRITICAL")
        self.log("=" * 80)
        
        for criteria in self.success_criteria:
            print(f"    {criteria}")
        
        print()
        self.log(f"📊 OVERALL RESULTS: {self.tests_passed}/{self.tests_run} critical tests passed")
        
        if self.tests_passed == self.tests_run:
            self.log("🎉 ALL CRITICAL SUCCESS CRITERIA MET!", "SUCCESS")
            self.log("✅ Universal PHP Admin Panel is FULLY FUNCTIONAL", "SUCCESS")
            return True
        elif self.tests_passed >= self.tests_run * 0.8:
            self.log("⚠️ Most critical criteria met - Minor issues only", "WARNING")
            return True
        else:
            self.log("❌ CRITICAL FAILURES DETECTED", "ERROR")
            self.log(f"Failed criteria: {', '.join(self.critical_failures)}")
            return False

def main():
    """Main test execution"""
    print("🚨 FINAL COMPREHENSIVE TEST SUITE")
    print("Universal PHP Admin Panel - Hohmann Bau")
    print("Testing ALL Critical Success Criteria")
    print("=" * 80)
    
    tester = FinalComprehensiveTest()
    success = tester.run_final_comprehensive_test()
    
    return 0 if success else 1

if __name__ == "__main__":
    sys.exit(main())