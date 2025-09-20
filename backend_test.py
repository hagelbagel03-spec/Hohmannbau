import requests
import sys
import json
from datetime import datetime

class HohmannBauAPITester:
    def __init__(self, base_url="https://buildpro-13.preview.emergentagent.com/api"):
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

    def test_health_check(self):
        """Test API health check"""
        return self.run_test("Health Check", "GET", "/", 200)

    def test_health_endpoint(self):
        """Test health endpoint"""
        return self.run_test("Health Endpoint", "GET", "/health", 200)

    def test_contact_form(self):
        """Test contact form submission"""
        contact_data = {
            "name": "Test User",
            "email": "test@example.com",
            "message": "This is a test message from automated testing."
        }
        return self.run_test("Contact Form", "POST", "/contact", 200, data=contact_data)

    def test_get_contact_messages(self):
        """Test getting contact messages"""
        return self.run_test("Get Contact Messages", "GET", "/contact", 200)

    def test_get_projects(self):
        """Test getting projects"""
        return self.run_test("Get Projects", "GET", "/projects", 200)

    def test_get_team_members(self):
        """Test getting team members"""
        return self.run_test("Get Team Members", "GET", "/team", 200)

    def test_get_job_postings(self):
        """Test getting job postings"""
        return self.run_test("Get Job Postings", "GET", "/jobs", 200)

    def test_get_news(self):
        """Test getting news posts"""
        return self.run_test("Get News", "GET", "/news", 200)

    def test_admin_login(self):
        """Test admin login"""
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

    def test_admin_dashboard(self):
        """Test admin dashboard (requires authentication)"""
        return self.run_test("Admin Dashboard", "GET", "/admin/dashboard", 200)

    def test_create_project(self):
        """Test creating a new project (admin function)"""
        project_data = {
            "title": "Test Project",
            "category": "Test Category",
            "description": "This is a test project created by automated testing.",
            "image_url": "https://example.com/test-image.jpg"
        }
        return self.run_test("Create Project", "POST", "/projects", 200, data=project_data)

    def test_job_application(self):
        """Test job application submission with file upload"""
        # First get a job ID
        success, jobs_response = self.test_get_job_postings()
        if not success or not jobs_response:
            print("❌ Cannot test job application - no jobs available")
            return False
        
        job_id = jobs_response[0]['id'] if jobs_response else "test-job-id"
        
        application_data = {
            "job_id": job_id,
            "name": "Test Applicant",
            "email": "applicant@example.com",
            "phone": "+49 123 456 789",
            "cover_letter": "This is a test cover letter for automated testing."
        }
        
        # Create a dummy CV file
        files = {
            'cv_file': ('test_cv.txt', 'This is a test CV file content', 'text/plain')
        }
        
        return self.run_test("Job Application", "POST", "/applications", 200, 
                           data=application_data, files=files)

    def test_quote_request(self):
        """Test quote request submission with file upload"""
        quote_data = {
            "name": "Test Client",
            "email": "client@example.com",
            "phone": "+49 123 456 789",
            "project_type": "Hochbau",
            "description": "This is a test quote request for automated testing.",
            "budget_range": "50000-100000",
            "timeline": "3-6 months"
        }
        
        # Create a dummy blueprint file
        files = {
            'blueprint_file': ('test_blueprint.pdf', 'This is a test blueprint file content', 'application/pdf')
        }
        
        return self.run_test("Quote Request", "POST", "/quote-request", 200, 
                           data=quote_data, files=files)

def main():
    print("🏗️  Starting Hohmann Bau API Tests")
    print("=" * 50)
    
    tester = HohmannBauAPITester()
    
    # Test public endpoints first
    print("\n📋 Testing Public Endpoints...")
    tester.test_health_check()
    tester.test_health_endpoint()
    tester.test_contact_form()
    tester.test_get_contact_messages()
    tester.test_get_projects()
    tester.test_get_team_members()
    tester.test_get_job_postings()
    tester.test_get_news()
    
    # Test file upload endpoints
    print("\n📁 Testing File Upload Endpoints...")
    tester.test_job_application()
    tester.test_quote_request()
    
    # Test admin endpoints
    print("\n🔐 Testing Admin Endpoints...")
    if tester.test_admin_login():
        tester.test_admin_dashboard()
        tester.test_create_project()
    else:
        print("❌ Admin login failed, skipping admin-only tests")
    
    # Print final results
    print("\n" + "=" * 50)
    print(f"📊 Final Results: {tester.tests_passed}/{tester.tests_run} tests passed")
    
    if tester.tests_passed == tester.tests_run:
        print("🎉 All tests passed!")
        return 0
    else:
        print(f"⚠️  {tester.tests_run - tester.tests_passed} tests failed")
        return 1

if __name__ == "__main__":
    sys.exit(main())