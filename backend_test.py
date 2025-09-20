#!/usr/bin/env python3
"""
Backend API Testing for Hohmann Bau PHP Website
Tests all form submission endpoints to verify "Fehler beim Senden" errors are resolved
"""

import requests
import sys
import json
from datetime import datetime

class HohmannBauAPITester:
    def __init__(self, base_url="http://localhost:8080"):
        self.base_url = base_url
        self.api_url = f"{base_url}/api/index.php"
        self.tests_run = 0
        self.tests_passed = 0
        self.errors = []

    def log_test(self, name, success, details=""):
        """Log test results"""
        self.tests_run += 1
        if success:
            self.tests_passed += 1
            print(f"✅ {name} - PASSED")
        else:
            print(f"❌ {name} - FAILED: {details}")
            self.errors.append(f"{name}: {details}")
        
        if details:
            print(f"   Details: {details}")

    def test_health_check(self):
        """Test API health endpoint"""
        try:
            response = requests.get(f"{self.api_url}/health", timeout=10)
            success = response.status_code == 200
            details = f"Status: {response.status_code}"
            if success:
                data = response.json()
                details += f", Response: {data}"
            self.log_test("API Health Check", success, details)
            return success
        except Exception as e:
            self.log_test("API Health Check", False, str(e))
            return False

    def test_contact_form_submission(self):
        """Test contact form submission endpoint"""
        test_data = {
            "name": "Test User",
            "email": "test@example.com",
            "message": "This is a test message for contact form submission."
        }
        
        try:
            response = requests.post(
                f"{self.api_url}/contact",
                json=test_data,
                headers={'Content-Type': 'application/json'},
                timeout=10
            )
            
            success = response.status_code == 200
            details = f"Status: {response.status_code}"
            
            if success:
                try:
                    data = response.json()
                    if 'id' in data and 'name' in data:
                        details += f", Contact saved with ID: {data['id']}"
                    else:
                        success = False
                        details += f", Invalid response format: {data}"
                except:
                    success = False
                    details += f", Invalid JSON response: {response.text[:100]}"
            else:
                details += f", Response: {response.text[:200]}"
                
            self.log_test("Contact Form Submission", success, details)
            return success
            
        except Exception as e:
            self.log_test("Contact Form Submission", False, str(e))
            return False

    def test_quote_request_submission(self):
        """Test quote request form submission endpoint"""
        test_data = {
            "name": "Test Customer",
            "email": "customer@example.com",
            "phone": "+49 123 456 789",
            "project_type": "neubau",
            "description": "Test project description for quote request testing.",
            "budget_range": "100k-250k",
            "timeline": "6-monate"
        }
        
        try:
            # Use form data instead of JSON for file upload compatibility
            response = requests.post(
                f"{self.api_url}/quote-request",
                data=test_data,
                timeout=10
            )
            
            success = response.status_code == 200
            details = f"Status: {response.status_code}"
            
            if success:
                try:
                    data = response.json()
                    if 'id' in data and 'message' in data:
                        details += f", Quote request saved with ID: {data['id']}"
                    else:
                        success = False
                        details += f", Invalid response format: {data}"
                except:
                    success = False
                    details += f", Invalid JSON response: {response.text[:100]}"
            else:
                details += f", Response: {response.text[:200]}"
                
            self.log_test("Quote Request Form Submission", success, details)
            return success
            
        except Exception as e:
            self.log_test("Quote Request Form Submission", False, str(e))
            return False

    def test_job_application_submission(self):
        """Test job application form submission endpoint"""
        test_data = {
            "job_id": "bauleiter",
            "name": "Test Applicant",
            "email": "applicant@example.com",
            "phone": "+49 987 654 321",
            "cover_letter": "This is a test cover letter for job application testing. I am very interested in this position."
        }
        
        try:
            # Use form data for file upload compatibility
            response = requests.post(
                f"{self.api_url}/applications",
                data=test_data,
                timeout=10
            )
            
            success = response.status_code == 200
            details = f"Status: {response.status_code}"
            
            if success:
                try:
                    data = response.json()
                    if 'id' in data and 'message' in data:
                        details += f", Application saved with ID: {data['id']}"
                    else:
                        success = False
                        details += f", Invalid response format: {data}"
                except:
                    success = False
                    details += f", Invalid JSON response: {response.text[:100]}"
            else:
                details += f", Response: {response.text[:200]}"
                
            self.log_test("Job Application Form Submission", success, details)
            return success
            
        except Exception as e:
            self.log_test("Job Application Form Submission", False, str(e))
            return False

    def test_contact_info_endpoint(self):
        """Test contact info endpoint"""
        try:
            response = requests.get(f"{self.api_url}/contact-info", timeout=10)
            success = response.status_code == 200
            details = f"Status: {response.status_code}"
            
            if success:
                try:
                    data = response.json()
                    if 'address' in data and 'phone' in data:
                        details += f", Contact info loaded successfully"
                    else:
                        success = False
                        details += f", Missing required fields: {data}"
                except:
                    success = False
                    details += f", Invalid JSON response: {response.text[:100]}"
            else:
                details += f", Response: {response.text[:200]}"
                
            self.log_test("Contact Info Endpoint", success, details)
            return success
            
        except Exception as e:
            self.log_test("Contact Info Endpoint", False, str(e))
            return False

    def run_all_tests(self):
        """Run all backend API tests"""
        print("🚀 Starting Hohmann Bau Backend API Tests")
        print("=" * 50)
        
        # Test API health first
        if not self.test_health_check():
            print("❌ API is not healthy, stopping tests")
            return False
        
        # Test all form submission endpoints
        self.test_contact_form_submission()
        self.test_quote_request_submission()
        self.test_job_application_submission()
        self.test_contact_info_endpoint()
        
        # Print summary
        print("\n" + "=" * 50)
        print(f"📊 Test Results: {self.tests_passed}/{self.tests_run} tests passed")
        
        if self.errors:
            print("\n❌ Failed Tests:")
            for error in self.errors:
                print(f"   - {error}")
        
        success_rate = (self.tests_passed / self.tests_run) * 100 if self.tests_run > 0 else 0
        print(f"✅ Success Rate: {success_rate:.1f}%")
        
        return self.tests_passed == self.tests_run

def main():
    """Main test execution"""
    tester = HohmannBauAPITester()
    
    try:
        success = tester.run_all_tests()
        return 0 if success else 1
    except KeyboardInterrupt:
        print("\n⚠️ Tests interrupted by user")
        return 1
    except Exception as e:
        print(f"\n💥 Unexpected error: {e}")
        return 1

if __name__ == "__main__":
    sys.exit(main())