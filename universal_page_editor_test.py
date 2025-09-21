#!/usr/bin/env python3
"""
Universal Page Editor Test Suite
Tests all 8 page types mentioned in the review request
"""

import requests
import json
import sys
from datetime import datetime

class UniversalPageEditorTester:
    def __init__(self, base_url="http://localhost:8080"):
        self.base_url = base_url
        self.session = requests.Session()
        self.tests_run = 0
        self.tests_passed = 0
        
        # Login first
        self.login()
        
        # Define the 8 page types from the review request
        self.page_types = {
            "home": {
                "name": "🏠 Homepage",
                "fields": ["hero_title", "hero_subtitle", "about_title", "about_text"]
            },
            "services": {
                "name": "🔧 Leistungen", 
                "fields": ["title", "subtitle", "description"]
            },
            "projects": {
                "name": "🏗️ Projekte",
                "fields": ["title", "subtitle", "description"]
            },
            "team": {
                "name": "👥 Team",
                "fields": ["title", "subtitle", "description"]
            },
            "contact": {
                "name": "📞 Kontakt",
                "fields": ["title", "subtitle", "description"]
            },
            "career": {
                "name": "💼 Karriere",
                "fields": ["title", "subtitle", "description"]
            },
            "footer": {
                "name": "📄 Footer",
                "fields": ["company_name", "company_description", "copyright"]
            },
            "navigation": {
                "name": "🧭 Navigation",
                "fields": ["logo_text", "menu_items", "cta_button"]
            }
        }
        
    def log(self, message, status="INFO"):
        timestamp = datetime.now().strftime("%H:%M:%S")
        status_emoji = {
            "INFO": "ℹ️",
            "SUCCESS": "✅", 
            "ERROR": "❌",
            "WARNING": "⚠️"
        }
        print(f"[{timestamp}] {status_emoji.get(status, 'ℹ️')} {message}")
        
    def login(self):
        """Login to admin panel"""
        login_data = {
            'username': 'admin',
            'password': 'admin123'
        }
        response = self.session.post(f"{self.base_url}/admin/login.php", data=login_data)
        self.log("Logged into admin panel")
        
    def test_page_editing(self, page_name, page_info):
        """Test editing a specific page type"""
        self.tests_run += 1
        
        try:
            self.log(f"Testing {page_info['name']} editing...")
            
            # Create test content for this page type
            test_content = {}
            for field in page_info['fields']:
                if field == "menu_items":
                    test_content[field] = [
                        {"label": "Test Home", "link": "/", "active": True},
                        {"label": "Test Services", "link": "/services", "active": True}
                    ]
                elif field == "cta_button":
                    test_content[field] = {
                        "text": "Test CTA",
                        "link": "/test",
                        "style": "primary"
                    }
                else:
                    test_content[field] = f"Test {field.replace('_', ' ').title()} for {page_name}"
            
            # Save the content
            save_data = {
                'action': 'save_page_content',
                'page_name': page_name,
                'content': json.dumps(test_content)
            }
            
            save_response = self.session.post(f"{self.base_url}/admin/universal_admin.php", data=save_data)
            
            if save_response.status_code == 200:
                try:
                    save_result = save_response.json()
                    if save_result.get('success'):
                        self.log(f"✅ {page_info['name']} content saved successfully")
                        
                        # Verify by loading the content back
                        load_data = {
                            'action': 'get_page_content',
                            'page_name': page_name
                        }
                        
                        load_response = self.session.post(f"{self.base_url}/admin/universal_admin.php", data=load_data)
                        
                        if load_response.status_code == 200:
                            loaded_content = load_response.json()
                            
                            # Check if at least one field was saved correctly
                            fields_verified = 0
                            for field in page_info['fields']:
                                if field in loaded_content and loaded_content[field]:
                                    fields_verified += 1
                            
                            if fields_verified > 0:
                                self.log(f"✅ {page_info['name']} content verified ({fields_verified}/{len(page_info['fields'])} fields)")
                                self.tests_passed += 1
                                return True
                            else:
                                self.log(f"❌ {page_info['name']} content verification failed")
                                return False
                        else:
                            self.log(f"❌ Failed to load {page_info['name']} content")
                            return False
                    else:
                        self.log(f"❌ Failed to save {page_info['name']} content")
                        return False
                except json.JSONDecodeError:
                    self.log(f"❌ Invalid JSON response for {page_info['name']}")
                    return False
            else:
                self.log(f"❌ HTTP error {save_response.status_code} for {page_info['name']}")
                return False
                
        except Exception as e:
            self.log(f"❌ Exception testing {page_info['name']}: {str(e)}")
            return False
    
    def test_design_system_colors(self):
        """Test Design System Manager - Colors"""
        self.tests_run += 1
        
        try:
            self.log("Testing Design System - Color Management...")
            
            test_colors = {
                "primary_color": "#ff6b6b",
                "secondary_color": "#4ecdc4", 
                "accent_color": "#45b7d1",
                "background_color": "#f8f9fa",
                "text_color": "#2c3e50"
            }
            
            save_data = {
                'action': 'save_design_settings',
                'setting_type': 'colors',
                'settings': json.dumps(test_colors)
            }
            
            response = self.session.post(f"{self.base_url}/admin/universal_admin.php", data=save_data)
            
            if response.status_code == 200:
                result = response.json()
                if result.get('success'):
                    self.log("✅ Color system settings saved successfully")
                    self.tests_passed += 1
                    return True
                else:
                    self.log("❌ Color system save failed")
                    return False
            else:
                self.log(f"❌ Color system HTTP error {response.status_code}")
                return False
                
        except Exception as e:
            self.log(f"❌ Color system test exception: {str(e)}")
            return False
    
    def test_design_system_typography(self):
        """Test Design System Manager - Typography"""
        self.tests_run += 1
        
        try:
            self.log("Testing Design System - Typography Management...")
            
            test_typography = {
                "font_family": "Roboto, sans-serif",
                "heading_font": "Montserrat, sans-serif",
                "font_size_base": "18px",
                "line_height": "1.7"
            }
            
            save_data = {
                'action': 'save_design_settings',
                'setting_type': 'typography',
                'settings': json.dumps(test_typography)
            }
            
            response = self.session.post(f"{self.base_url}/admin/universal_admin.php", data=save_data)
            
            if response.status_code == 200:
                result = response.json()
                if result.get('success'):
                    self.log("✅ Typography settings saved successfully")
                    self.tests_passed += 1
                    return True
                else:
                    self.log("❌ Typography save failed")
                    return False
            else:
                self.log(f"❌ Typography HTTP error {response.status_code}")
                return False
                
        except Exception as e:
            self.log(f"❌ Typography test exception: {str(e)}")
            return False
    
    def run_comprehensive_test(self):
        """Run comprehensive Universal Page Editor test"""
        self.log("🌍 Starting Universal Page Editor Comprehensive Test", "INFO")
        self.log("=" * 70)
        
        # Test all 8 page types
        self.log("📝 Testing Universal Page Editor for all page types...")
        for page_name, page_info in self.page_types.items():
            self.test_page_editing(page_name, page_info)
            print()  # Add spacing
        
        # Test Design System Manager
        self.log("🎨 Testing Design System Manager...")
        self.test_design_system_colors()
        self.test_design_system_typography()
        
        # Summary
        self.log("=" * 70)
        self.log(f"📊 UNIVERSAL PAGE EDITOR RESULTS: {self.tests_passed}/{self.tests_run} tests passed")
        
        if self.tests_passed == self.tests_run:
            self.log("🎉 ALL UNIVERSAL PAGE EDITOR TESTS PASSED!", "SUCCESS")
            return True
        elif self.tests_passed >= self.tests_run * 0.8:
            self.log("⚠️ Most Universal Page Editor tests passed", "WARNING")
            return True
        else:
            self.log("❌ CRITICAL ISSUES with Universal Page Editor", "ERROR")
            return False

def main():
    """Main test execution"""
    print("🌍 Universal Page Editor - Comprehensive Test Suite")
    print("Testing all 8 page types + Design System Manager")
    print("=" * 70)
    
    tester = UniversalPageEditorTester()
    success = tester.run_comprehensive_test()
    
    return 0 if success else 1

if __name__ == "__main__":
    sys.exit(main())