#!/usr/bin/env python3
"""
Comprehensive Backend API Test for Hohmann Bau PHP Application
Tests all critical API endpoints mentioned in the review request
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
        self.failed_tests = []

    def log_test(self, name, success, details=""):
        """Log test results"""
        self.tests_run += 1
        if success:
            self.tests_passed += 1
            print(f"✅ {name} - PASSED")
        else:
            self.failed_tests.append(f"{name}: {details}")
            print(f"❌ {name} - FAILED: {details}")

    def test_api_health(self):
        """Test API health endpoint"""
        try:
            response = requests.get(f"{self.api_url}/health", timeout=10)
            success = response.status_code == 200
            if success:
                data = response.json()
                success = 'status' in data and data['status'] == 'healthy'
            self.log_test("API Health Check", success, f"Status: {response.status_code}")
            return success
        except Exception as e:
            self.log_test("API Health Check", False, str(e))
            return False

    def test_content_management_get(self):
        """Test content management GET endpoint"""
        try:
            # Test getting homepage content
            response = requests.get(f"{self.api_url}/content?page=homepage", timeout=10)
            success = response.status_code == 200
            self.log_test("Content Management GET", success, f"Status: {response.status_code}")
            return success
        except Exception as e:
            self.log_test("Content Management GET", False, str(e))
            return False

    def test_content_management_post(self):
        """Test content management POST endpoint (critical for admin panel)"""
        try:
            test_content = {
                "page_name": "test_page",
                "content": {
                    "title": "Test Title",
                    "description": "Test Description"
                }
            }
            
            response = requests.post(
                f"{self.api_url}/content",
                json=test_content,
                headers={'Content-Type': 'application/json'},
                timeout=10
            )
            
            success = response.status_code == 200
            if success:
                try:
                    data = response.json()
                    success = 'content' in data or 'page_name' in data
                except:
                    success = False
                    
            self.log_test("Content Management POST", success, f"Status: {response.status_code}")
            return success
        except Exception as e:
            self.log_test("Content Management POST", False, str(e))
            return False

    def test_messages_endpoint(self):
        """Test messages endpoint (for admin panel message management)"""
        try:
            response = requests.get(f"{self.api_url}/messages", timeout=10)
            success = response.status_code == 200
            if success:
                try:
                    data = response.json()
                    success = isinstance(data, list)  # Should return array of messages
                except:
                    success = False
                    
            self.log_test("Messages GET Endpoint", success, f"Status: {response.status_code}")
            return success
        except Exception as e:
            self.log_test("Messages GET Endpoint", False, str(e))
            return False

    def test_message_action_endpoint(self):
        """Test message action endpoint (for reply and mark complete buttons)"""
        try:
            test_action = {
                "action": "reply",
                "message_id": "test-id"
            }
            
            response = requests.post(
                f"{self.api_url}/message-action",
                json=test_action,
                headers={'Content-Type': 'application/json'},
                timeout=10
            )
            
            success = response.status_code == 200
            self.log_test("Message Action Endpoint", success, f"Status: {response.status_code}")
            return success
        except Exception as e:
            self.log_test("Message Action Endpoint", False, str(e))
            return False

    def test_contact_form_submission(self):
        """Test contact form submission"""
        try:
            contact_data = {
                "name": "Test User",
                "email": "test@example.com",
                "message": "Test message from API test"
            }
            
            response = requests.post(
                f"{self.api_url}/contact",
                json=contact_data,
                headers={'Content-Type': 'application/json'},
                timeout=10
            )
            
            success = response.status_code == 200
            if success:
                try:
                    data = response.json()
                    success = 'id' in data and 'name' in data
                except:
                    success = False
                    
            self.log_test("Contact Form Submission", success, f"Status: {response.status_code}")
            return success
        except Exception as e:
            self.log_test("Contact Form Submission", False, str(e))
            return False

    def test_quote_request_submission(self):
        """Test quote request form submission"""
        try:
            # Test with form data (multipart/form-data simulation)
            quote_data = {
                'name': 'Test Client',
                'email': 'client@example.com',
                'phone': '+49 123 456 789',
                'project_type': 'Neubau',
                'description': 'Test project description',
                'budget_range': '50000-100000',
                'timeline': '6 months'
            }
            
            response = requests.post(
                f"{self.api_url}/quote-request",
                data=quote_data,  # Using data instead of json for form submission
                timeout=10
            )
            
            success = response.status_code == 200
            if success:
                try:
                    data = response.json()
                    success = 'id' in data and 'message' in data
                except:
                    success = False
                    
            self.log_test("Quote Request Submission", success, f"Status: {response.status_code}")
            return success
        except Exception as e:
            self.log_test("Quote Request Submission", False, str(e))
            return False

    def test_job_application_submission(self):
        """Test job application form submission"""
        try:
            # Test with form data
            application_data = {
                'job_id': 'test-job-id',
                'name': 'Test Applicant',
                'email': 'applicant@example.com',
                'phone': '+49 123 456 789',
                'cover_letter': 'Test cover letter content'
            }
            
            response = requests.post(
                f"{self.api_url}/applications",
                data=application_data,
                timeout=10
            )
            
            success = response.status_code == 200
            if success:
                try:
                    data = response.json()
                    success = 'id' in data and 'message' in data
                except:
                    success = False
                    
            self.log_test("Job Application Submission", success, f"Status: {response.status_code}")
            return success
        except Exception as e:
            self.log_test("Job Application Submission", False, str(e))
            return False

    def test_projects_endpoint(self):
        """Test projects endpoint"""
        try:
            response = requests.get(f"{self.api_url}/projects", timeout=10)
            success = response.status_code == 200
            if success:
                try:
                    data = response.json()
                    success = isinstance(data, list)
                except:
                    success = False
                    
            self.log_test("Projects Endpoint", success, f"Status: {response.status_code}")
            return success
        except Exception as e:
            self.log_test("Projects Endpoint", False, str(e))
            return False

    def test_team_endpoint(self):
        """Test team endpoint"""
        try:
            response = requests.get(f"{self.api_url}/team", timeout=10)
            success = response.status_code == 200
            if success:
                try:
                    data = response.json()
                    success = isinstance(data, list)
                except:
                    success = False
                    
            self.log_test("Team Endpoint", success, f"Status: {response.status_code}")
            return success
        except Exception as e:
            self.log_test("Team Endpoint", False, str(e))
            return False

    def run_all_tests(self):
        """Run all backend API tests"""
        print("🚀 Starting Hohmann Bau PHP Backend API Tests")
        print("=" * 60)
        
        # Critical tests first
        critical_tests = [
            self.test_api_health,
            self.test_content_management_get,
            self.test_content_management_post,
            self.test_messages_endpoint,
            self.test_message_action_endpoint,
        ]
        
        # Form submission tests
        form_tests = [
            self.test_contact_form_submission,
            self.test_quote_request_submission,
            self.test_job_application_submission,
        ]
        
        # Data retrieval tests
        data_tests = [
            self.test_projects_endpoint,
            self.test_team_endpoint,
        ]
        
        print("\n📋 Running Critical API Tests...")
        for test in critical_tests:
            test()
            
        print("\n📝 Running Form Submission Tests...")
        for test in form_tests:
            test()
            
        print("\n📊 Running Data Retrieval Tests...")
        for test in data_tests:
            test()
        
        # Print summary
        print("\n" + "=" * 60)
        print(f"📊 TEST SUMMARY")
        print(f"Total Tests: {self.tests_run}")
        print(f"Passed: {self.tests_passed}")
        print(f"Failed: {len(self.failed_tests)}")
        
        if self.failed_tests:
            print(f"\n❌ FAILED TESTS:")
            for failure in self.failed_tests:
                print(f"  - {failure}")
        
        success_rate = (self.tests_passed / self.tests_run) * 100 if self.tests_run > 0 else 0
        print(f"\n✅ Success Rate: {success_rate:.1f}%")
        
        return len(self.failed_tests) == 0

def main():
    """Main test execution"""
    print("Hohmann Bau PHP Backend API Tester")
    print("Testing against: http://localhost:8080")
    
    tester = HohmannBauAPITester()
    success = tester.run_all_tests()
    
    return 0 if success else 1

if __name__ == "__main__":
    sys.exit(main())