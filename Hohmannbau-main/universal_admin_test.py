import requests
import sys
import json
from datetime import datetime

class UniversalAdminTester:
    def __init__(self, base_url="http://localhost:8001/api"):
        self.base_url = base_url
        self.token = None
        self.tests_run = 0
        self.tests_passed = 0
        self.session = requests.Session()

    def run_test(self, name, method, endpoint, expected_status, data=None, files=None, headers=None):
        """Run a single API test"""
        url = f"{self.base_url}{endpoint}"
        test_headers = {'Content-Type': 'application/json'}
        
        if headers:
            test_headers.update(headers)
        
        if self.token:
            test_headers['Authorization'] = f'Bearer {self.token}'

        self.tests_run += 1
        print(f"\n🔍 Testing {name}...")
        print(f"   URL: {url}")
        
        try:
            if method == 'GET':
                response = self.session.get(url, headers=test_headers)
            elif method == 'POST':
                if files:
                    # Remove Content-Type for multipart/form-data
                    if 'Content-Type' in test_headers:
                        del test_headers['Content-Type']
                    response = self.session.post(url, data=data, files=files, headers=test_headers)
                else:
                    response = self.session.post(url, json=data, headers=test_headers)
            elif method == 'PUT':
                response = self.session.put(url, json=data, headers=test_headers)
            elif method == 'DELETE':
                response = self.session.delete(url, headers=test_headers)

            success = response.status_code == expected_status
            if success:
                self.tests_passed += 1
                print(f"✅ Passed - Status: {response.status_code}")
                try:
                    response_data = response.json()
                    print(f"   Response: {json.dumps(response_data, indent=2)[:200]}...")
                except:
                    print(f"   Response: {response.text[:200]}...")
            else:
                print(f"❌ Failed - Expected {expected_status}, got {response.status_code}")
                print(f"   Response: {response.text[:500]}")

            return success, response.json() if response.text and response.text.strip() else {}

        except Exception as e:
            print(f"❌ Failed - Error: {str(e)}")
            return False, {}

    def test_admin_login(self):
        """Test admin login with default credentials"""
        login_data = {
            "username": "admin",
            "password": "admin123"
        }
        success, response = self.run_test("Admin Login", "POST", "/admin/login", 200, data=login_data)
        if success and 'access_token' in response:
            self.token = response['access_token']
            print(f"   Token obtained: {self.token[:50]}...")
            return True
        return False

    def test_universal_page_editor(self):
        """Test Universal Page Editor functionality"""
        print("\n📝 Testing Universal Page Editor...")
        
        # Test getting page content for all pages
        pages = ['home', 'services', 'projects', 'team', 'contact', 'career', 'footer', 'navigation']
        
        for page in pages:
            success, response = self.run_test(f"Get {page} content", "GET", f"/content/{page}", 200)
            if not success:
                return False
        
        # Test creating/updating page content
        test_content = {
            "page_name": "home",
            "content": {
                "hero_title": "Test Title Updated",
                "hero_subtitle": "Test Subtitle Updated",
                "hero_image": "https://example.com/test-image.jpg",
                "hero_cta_text": "Test CTA",
                "about_title": "About Test",
                "about_text": "This is a test about section."
            }
        }
        
        success, response = self.run_test("Update Home Page Content", "POST", "/content", 200, data=test_content)
        if not success:
            return False
            
        # Test updating specific page content
        update_content = {
            "content": {
                "hero_title": "Updated Again",
                "hero_subtitle": "Updated Subtitle Again"
            }
        }
        
        success, response = self.run_test("Update Home Page Content (PUT)", "PUT", "/content/home", 200, data=update_content)
        return success

    def test_design_system_manager(self):
        """Test Design System Manager functionality"""
        print("\n🎨 Testing Design System Manager...")
        
        # Test getting design settings
        settings_types = ['theme', 'typography', 'layout', 'seo']
        
        for setting_type in settings_types:
            success, response = self.run_test(f"Get {setting_type} settings", "GET", f"/settings/{setting_type}", 200)
            if not success:
                return False
        
        # Test updating theme settings
        theme_settings = {
            "setting_name": "theme",
            "settings": {
                "primary_color": "#ff6b35",
                "secondary_color": "#f7931e",
                "accent_color": "#ffb700",
                "background_color": "#ffffff",
                "text_color": "#333333",
                "border_color": "#e0e0e0"
            }
        }
        
        success, response = self.run_test("Update Theme Settings", "POST", "/settings", 200, data=theme_settings)
        if not success:
            return False
            
        # Test updating typography settings
        typography_settings = {
            "setting_name": "typography",
            "settings": {
                "font_family": "Roboto, sans-serif",
                "heading_font": "Roboto, sans-serif",
                "font_size_base": "18px",
                "font_size_lg": "20px",
                "font_size_xl": "24px",
                "line_height": "1.7"
            }
        }
        
        success, response = self.run_test("Update Typography Settings", "POST", "/settings", 200, data=typography_settings)
        if not success:
            return False
            
        # Test updating layout settings
        layout_settings = {
            "setting_name": "layout",
            "settings": {
                "container_width": "1400px",
                "section_padding": "100px",
                "card_border_radius": "12px",
                "button_border_radius": "8px"
            }
        }
        
        success, response = self.run_test("Update Layout Settings", "POST", "/settings", 200, data=layout_settings)
        return success

    def test_media_manager(self):
        """Test Media Manager functionality"""
        print("\n📁 Testing Media Manager...")
        
        # Test getting media files
        success, response = self.run_test("Get Media Files", "GET", "/media", 200)
        if not success:
            return False
        
        # Test file upload
        test_file_content = b"This is a test file content for media manager testing."
        files = {
            'file': ('test_image.jpg', test_file_content, 'image/jpeg')
        }
        
        success, response = self.run_test("Upload Media File", "POST", "/upload", 200, files=files)
        if not success:
            return False
            
        # Test getting media files again to see the uploaded file
        success, response = self.run_test("Get Media Files After Upload", "GET", "/media", 200)
        return success

    def test_existing_admin_features(self):
        """Test existing admin features to ensure they still work"""
        print("\n🏢 Testing Existing Admin Features...")
        
        # Test dashboard
        success, response = self.run_test("Admin Dashboard", "GET", "/admin/dashboard", 200)
        if not success:
            return False
            
        # Test creating a project
        project_data = {
            "title": "Universal Admin Test Project",
            "category": "Testing",
            "description": "This project was created to test the Universal Admin Panel.",
            "image_url": "https://example.com/test-project.jpg"
        }
        
        success, response = self.run_test("Create Project", "POST", "/projects", 200, data=project_data)
        if not success:
            return False
            
        # Test creating a job posting
        job_data = {
            "title": "Test Job Position",
            "description": "This is a test job posting created by the Universal Admin Panel test.",
            "requirements": "• Test requirement 1\n• Test requirement 2",
            "location": "Test City",
            "employment_type": "Vollzeit"
        }
        
        success, response = self.run_test("Create Job Posting", "POST", "/jobs", 200, data=job_data)
        if not success:
            return False
            
        # Test creating a team member
        team_data = {
            "name": "Test Team Member",
            "role": "Test Role",
            "image_url": "https://example.com/test-member.jpg",
            "bio": "This is a test team member created by the Universal Admin Panel test."
        }
        
        success, response = self.run_test("Create Team Member", "POST", "/team", 200, data=team_data)
        return success

def main():
    print("🏗️  Starting Universal Admin Panel Tests")
    print("=" * 60)
    
    tester = UniversalAdminTester()
    
    # Test admin login first
    print("\n🔐 Testing Admin Authentication...")
    if not tester.test_admin_login():
        print("❌ Admin login failed, stopping tests")
        return 1
    
    # Test Universal Page Editor
    if not tester.test_universal_page_editor():
        print("❌ Universal Page Editor tests failed")
        return 1
    
    # Test Design System Manager
    if not tester.test_design_system_manager():
        print("❌ Design System Manager tests failed")
        return 1
    
    # Test Media Manager
    if not tester.test_media_manager():
        print("❌ Media Manager tests failed")
        return 1
    
    # Test existing admin features
    if not tester.test_existing_admin_features():
        print("❌ Existing admin features tests failed")
        return 1
    
    # Print final results
    print("\n" + "=" * 60)
    print(f"📊 Final Results: {tester.tests_passed}/{tester.tests_run} tests passed")
    
    if tester.tests_passed == tester.tests_run:
        print("🎉 All Universal Admin Panel tests passed!")
        return 0
    else:
        print(f"⚠️  {tester.tests_run - tester.tests_passed} tests failed")
        return 1

if __name__ == "__main__":
    sys.exit(main())