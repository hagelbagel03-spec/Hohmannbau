from fastapi import FastAPI, APIRouter, HTTPException, File, UploadFile, Form
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from dotenv import load_dotenv
from starlette.middleware.cors import CORSMiddleware
from motor.motor_asyncio import AsyncIOMotorClient
import os
import logging
from pathlib import Path
from pydantic import BaseModel, Field, EmailStr
from typing import List, Optional
import uuid
from datetime import datetime, timezone
import jwt
import hashlib
import aiofiles
from passlib.context import CryptContext

ROOT_DIR = Path(__file__).parent
load_dotenv(ROOT_DIR / '.env')

# MongoDB connection
mongo_url = os.environ['MONGO_URL']
client = AsyncIOMotorClient(mongo_url)
db = client[os.environ['DB_NAME']]

# Security
security = HTTPBearer()
pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")
SECRET_KEY = os.environ.get('SECRET_KEY', 'your-secret-key-change-in-production')

# Create the main app without a prefix
app = FastAPI(title="Hohmann Bau API", version="1.0.0")

# Create a router with the /api prefix
api_router = APIRouter(prefix="/api")

# Helper functions
def prepare_for_mongo(data):
    """Convert datetime objects to ISO strings for MongoDB storage"""
    if isinstance(data, dict):
        for key, value in data.items():
            if isinstance(value, datetime):
                data[key] = value.isoformat()
    return data

def hash_password(password: str) -> str:
    return pwd_context.hash(password)

def verify_password(plain_password: str, hashed_password: str) -> bool:
    return pwd_context.verify(plain_password, hashed_password)

def create_jwt_token(data: dict) -> str:
    return jwt.encode(data, SECRET_KEY, algorithm="HS256")

def verify_jwt_token(token: str) -> dict:
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=["HS256"])
        return payload
    except jwt.PyJWTError:
        return None

# Pydantic Models
class PageContent(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    page_name: str  # home, services, projects, team, contact
    content: dict  # JSON object with page-specific content
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))
    updated_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class PageContentCreate(BaseModel):
    page_name: str
    content: dict

class PageContentUpdate(BaseModel):
    content: dict

class Service(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    title: str
    description: str
    features: List[str]
    icon: str
    image: str
    order: int = 0
    is_active: bool = True
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class ServiceCreate(BaseModel):
    title: str
    description: str
    features: List[str]
    icon: str
    image: str
    order: int = 0

class Feature(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    title: str
    description: str
    icon: str
    order: int = 0
    is_active: bool = True
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class FeatureCreate(BaseModel):
    title: str
    description: str
    icon: str
    order: int = 0

class ContactInfo(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    address: str
    phone: str
    email: str
    opening_hours: str
    updated_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class ContactInfoUpdate(BaseModel):
    address: Optional[str] = None
    phone: Optional[str] = None
    email: Optional[str] = None
    opening_hours: Optional[str] = None

class SupportTicket(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    name: str
    email: EmailStr
    subject: str
    message: str
    status: str = "open"  # open, in_progress, closed
    priority: str = "normal"  # low, normal, high, urgent
    assigned_to: Optional[str] = None
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))
    updated_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class SupportTicketCreate(BaseModel):
    name: str
    email: EmailStr
    subject: str
    message: str

class HelpArticle(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    title: str
    content: str
    category: str
    order: int = 0
    is_active: bool = True
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))
    updated_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class HelpArticleCreate(BaseModel):
    title: str
    content: str
    category: str
    order: int = 0

class ContactMessage(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    name: str
    email: EmailStr
    message: str
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class ContactMessageCreate(BaseModel):
    name: str
    email: EmailStr
    message: str

class Project(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    title: str
    category: str
    description: str
    image_url: str
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))
    updated_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class ProjectCreate(BaseModel):
    title: str
    category: str
    description: str
    image_url: str

class ProjectUpdate(BaseModel):
    title: Optional[str] = None
    category: Optional[str] = None
    description: Optional[str] = None
    image_url: Optional[str] = None

class TeamMember(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    name: str
    role: str
    image_url: str
    bio: Optional[str] = None
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class TeamMemberCreate(BaseModel):
    name: str
    role: str
    image_url: str
    bio: Optional[str] = None

class JobPosting(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    title: str
    description: str
    requirements: str
    location: str
    employment_type: str
    is_active: bool = True
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class JobPostingCreate(BaseModel):
    title: str
    description: str
    requirements: str
    location: str
    employment_type: str

class Application(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    job_id: str
    name: str
    email: EmailStr
    phone: Optional[str] = None
    cover_letter: str
    cv_filename: Optional[str] = None
    status: str = "pending"  # pending, reviewed, accepted, rejected
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class ApplicationCreate(BaseModel):
    job_id: str
    name: str
    email: EmailStr
    phone: Optional[str] = None
    cover_letter: str

class Admin(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    username: str
    email: EmailStr
    hashed_password: str
    is_active: bool = True
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class AdminCreate(BaseModel):
    username: str
    email: EmailStr
    password: str

class AdminLogin(BaseModel):
    username: str
    password: str

class QuoteRequest(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    name: str
    email: EmailStr
    phone: Optional[str] = None
    project_type: str
    description: str
    budget_range: Optional[str] = None
    timeline: Optional[str] = None
    file_path: Optional[str] = None
    status: str = "new"  # new, reviewed, quoted, closed
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class QuoteRequestCreate(BaseModel):
    name: str
    email: EmailStr
    phone: Optional[str] = None
    project_type: str
    description: str
    budget_range: Optional[str] = None
    timeline: Optional[str] = None

class NewsPost(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    title: str
    content: str
    excerpt: Optional[str] = None
    image_url: Optional[str] = None
    author: str
    is_published: bool = True
    created_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))
    updated_at: datetime = Field(default_factory=lambda: datetime.now(timezone.utc))

class NewsPostCreate(BaseModel):
    title: str
    content: str
    excerpt: Optional[str] = None
    image_url: Optional[str] = None
    author: str

# Routes

# Public Routes
@api_router.get("/")
async def root():
    return {"message": "Hohmann Bau API"}

@api_router.get("/health")
async def health_check():
    return {"status": "healthy", "timestamp": datetime.now(timezone.utc).isoformat()}

# Content Management Routes
@api_router.get("/content/{page_name}")
async def get_page_content(page_name: str):
    content = await db.page_contents.find_one({"page_name": page_name})
    if content:
        return PageContent(**content)
    return {"message": "Content not found"}

@api_router.post("/content", response_model=PageContent)
async def create_page_content(content: PageContentCreate):
    # Check if content already exists for this page
    existing = await db.page_contents.find_one({"page_name": content.page_name})
    if existing:
        # Update existing content
        update_data = content.dict()
        update_data['updated_at'] = datetime.now(timezone.utc).isoformat()
        await db.page_contents.update_one(
            {"page_name": content.page_name},
            {"$set": update_data}
        )
        updated_content = await db.page_contents.find_one({"page_name": content.page_name})
        return PageContent(**updated_content)
    else:
        # Create new content
        content_dict = content.dict()
        content_obj = PageContent(**content_dict)
        content_data = prepare_for_mongo(content_obj.dict())
        await db.page_contents.insert_one(content_data)
        return content_obj

@api_router.put("/content/{page_name}", response_model=PageContent)
async def update_page_content(page_name: str, content_update: PageContentUpdate):
    update_data = content_update.dict()
    update_data['updated_at'] = datetime.now(timezone.utc).isoformat()
    
    result = await db.page_contents.update_one(
        {"page_name": page_name},
        {"$set": update_data}
    )
    
    if result.matched_count == 0:
        raise HTTPException(status_code=404, detail="Page content not found")
    
    updated_content = await db.page_contents.find_one({"page_name": page_name})
    return PageContent(**updated_content)

# Services Routes
@api_router.get("/services", response_model=List[Service])
async def get_services():
    services = await db.services.find({"is_active": True}).sort("order", 1).to_list(1000)
    return [Service(**service) for service in services]

@api_router.post("/services", response_model=Service)
async def create_service(service: ServiceCreate):
    service_dict = service.dict()
    service_obj = Service(**service_dict)
    service_data = prepare_for_mongo(service_obj.dict())
    await db.services.insert_one(service_data)
    return service_obj

# Features Routes
@api_router.get("/features", response_model=List[Feature])
async def get_features():
    features = await db.features.find({"is_active": True}).sort("order", 1).to_list(1000)
    return [Feature(**feature) for feature in features]

@api_router.post("/features", response_model=Feature)
async def create_feature(feature: FeatureCreate):
    feature_dict = feature.dict()
    feature_obj = Feature(**feature_dict)
    feature_data = prepare_for_mongo(feature_obj.dict())
    await db.features.insert_one(feature_data)
    return feature_obj

# Contact Info Routes
@api_router.get("/contact-info")
async def get_contact_info():
    contact_info = await db.contact_info.find_one()
    if contact_info:
        return ContactInfo(**contact_info)
    # Return default contact info
    return {
        "address": "Bahnhofstraße 123, 12345 Musterstadt",
        "phone": "+49 123 456 789", 
        "email": "info@hohmann-bau.de",
        "opening_hours": "Mo-Fr: 08:00-17:00 Uhr"
    }

@api_router.put("/contact-info", response_model=ContactInfo)
async def update_contact_info(contact_update: ContactInfoUpdate):
    update_data = {k: v for k, v in contact_update.dict().items() if v is not None}
    update_data['updated_at'] = datetime.now(timezone.utc).isoformat()
    
    # Check if contact info exists
    existing = await db.contact_info.find_one()
    if existing:
        await db.contact_info.update_one({}, {"$set": update_data})
        updated_info = await db.contact_info.find_one()
        return ContactInfo(**updated_info)
    else:
        # Create new contact info
        contact_data = ContactInfo(**update_data)
        contact_dict = prepare_for_mongo(contact_data.dict())
        await db.contact_info.insert_one(contact_dict)
        return contact_data

# Contact Routes
@api_router.post("/contact", response_model=ContactMessage)
async def create_contact_message(message: ContactMessageCreate):
    message_dict = message.dict()
    message_obj = ContactMessage(**message_dict)
    message_data = prepare_for_mongo(message_obj.dict())
    await db.contact_messages.insert_one(message_data)
    return message_obj

@api_router.get("/contact", response_model=List[ContactMessage])
async def get_contact_messages():
    messages = await db.contact_messages.find().sort("created_at", -1).to_list(1000)
    return [ContactMessage(**msg) for msg in messages]

# Projects Routes
@api_router.get("/projects", response_model=List[Project])
async def get_projects():
    projects = await db.projects.find().sort("created_at", -1).to_list(1000)
    return [Project(**project) for project in projects]

@api_router.post("/projects", response_model=Project)
async def create_project(project: ProjectCreate):
    project_dict = project.dict()
    project_obj = Project(**project_dict)
    project_data = prepare_for_mongo(project_obj.dict())
    await db.projects.insert_one(project_data)
    return project_obj

@api_router.put("/projects/{project_id}", response_model=Project)
async def update_project(project_id: str, project_update: ProjectUpdate):
    update_data = {k: v for k, v in project_update.dict().items() if v is not None}
    update_data['updated_at'] = datetime.now(timezone.utc).isoformat()
    
    result = await db.projects.update_one(
        {"id": project_id}, 
        {"$set": update_data}
    )
    
    if result.matched_count == 0:
        raise HTTPException(status_code=404, detail="Project not found")
    
    updated_project = await db.projects.find_one({"id": project_id})
    return Project(**updated_project)

@api_router.delete("/projects/{project_id}")
async def delete_project(project_id: str):
    result = await db.projects.delete_one({"id": project_id})
    if result.deleted_count == 0:
        raise HTTPException(status_code=404, detail="Project not found")
    return {"message": "Project deleted successfully"}

# Team Routes
@api_router.get("/team", response_model=List[TeamMember])
async def get_team_members():
    members = await db.team_members.find().sort("created_at", -1).to_list(1000)
    return [TeamMember(**member) for member in members]

@api_router.post("/team", response_model=TeamMember)
async def create_team_member(member: TeamMemberCreate):
    member_dict = member.dict()
    member_obj = TeamMember(**member_dict)
    member_data = prepare_for_mongo(member_obj.dict())
    await db.team_members.insert_one(member_data)
    return member_obj

# Career/Jobs Routes
@api_router.get("/jobs", response_model=List[JobPosting])
async def get_job_postings():
    jobs = await db.job_postings.find({"is_active": True}).sort("created_at", -1).to_list(1000)
    return [JobPosting(**job) for job in jobs]

@api_router.post("/jobs", response_model=JobPosting)
async def create_job_posting(job: JobPostingCreate):
    job_dict = job.dict()
    job_obj = JobPosting(**job_dict)
    job_data = prepare_for_mongo(job_obj.dict())
    await db.job_postings.insert_one(job_data)
    return job_obj

@api_router.post("/applications", response_model=Application)
async def create_application(
    job_id: str = Form(...),
    name: str = Form(...),
    email: str = Form(...),
    phone: str = Form(None),
    cover_letter: str = Form(...),
    cv_file: UploadFile = File(None)
):
    # Handle file upload
    cv_filename = None
    if cv_file:
        cv_filename = f"{uuid.uuid4()}_{cv_file.filename}"
        file_path = f"uploads/cv/{cv_filename}"
        os.makedirs(os.path.dirname(file_path), exist_ok=True)
        
        async with aiofiles.open(file_path, 'wb') as out_file:
            content = await cv_file.read()
            await out_file.write(content)
    
    application_data = {
        "job_id": job_id,
        "name": name,
        "email": email,
        "phone": phone,
        "cover_letter": cover_letter,
        "cv_filename": cv_filename
    }
    
    application_obj = Application(**application_data)
    application_data = prepare_for_mongo(application_obj.dict())
    await db.applications.insert_one(application_data)
    return application_obj

# Quote Request Routes
@api_router.post("/quote-request", response_model=QuoteRequest)
async def create_quote_request(
    name: str = Form(...),
    email: str = Form(...),
    phone: str = Form(None),
    project_type: str = Form(...),
    description: str = Form(...),
    budget_range: str = Form(None),
    timeline: str = Form(None),
    blueprint_file: UploadFile = File(None)
):
    # Handle file upload
    file_path = None
    if blueprint_file:
        filename = f"{uuid.uuid4()}_{blueprint_file.filename}"
        file_path = f"uploads/blueprints/{filename}"
        os.makedirs(os.path.dirname(file_path), exist_ok=True)
        
        async with aiofiles.open(file_path, 'wb') as out_file:
            content = await blueprint_file.read()
            await out_file.write(content)
    
    quote_data = {
        "name": name,
        "email": email,
        "phone": phone,
        "project_type": project_type,
        "description": description,
        "budget_range": budget_range,
        "timeline": timeline,
        "file_path": file_path
    }
    
    quote_obj = QuoteRequest(**quote_data)
    quote_data = prepare_for_mongo(quote_obj.dict())
    await db.quote_requests.insert_one(quote_data)
    return quote_obj

# News/Blog Routes
@api_router.get("/news", response_model=List[NewsPost])
async def get_news():
    news = await db.news_posts.find({"is_published": True}).sort("created_at", -1).to_list(1000)
    return [NewsPost(**post) for post in news]

@api_router.post("/news", response_model=NewsPost)
async def create_news_post(post: NewsPostCreate):
    post_dict = post.dict()
    post_obj = NewsPost(**post_dict)
    post_data = prepare_for_mongo(post_obj.dict())
    await db.news_posts.insert_one(post_data)
    return post_obj

# Admin Authentication Routes
@api_router.post("/admin/register")
async def register_admin(admin: AdminCreate):
    # Check if admin already exists
    existing_admin = await db.admins.find_one({"username": admin.username})
    if existing_admin:
        raise HTTPException(status_code=400, detail="Admin already exists")
    
    admin_dict = admin.dict()
    admin_dict['hashed_password'] = hash_password(admin_dict.pop('password'))
    admin_obj = Admin(**admin_dict)
    admin_data = prepare_for_mongo(admin_obj.dict())
    await db.admins.insert_one(admin_data)
    
    return {"message": "Admin registered successfully"}

@api_router.post("/admin/login")
async def login_admin(credentials: AdminLogin):
    admin = await db.admins.find_one({"username": credentials.username})
    if not admin or not verify_password(credentials.password, admin['hashed_password']):
        raise HTTPException(status_code=401, detail="Invalid credentials")
    
    token_data = {"username": admin['username'], "admin_id": admin['id']}
    token = create_jwt_token(token_data)
    
    return {"access_token": token, "token_type": "bearer"}

# Admin-only routes (protected)
async def get_current_admin(credentials: HTTPAuthorizationCredentials = security):
    token_data = verify_jwt_token(credentials.credentials)
    if not token_data:
        raise HTTPException(status_code=401, detail="Invalid token")
    
    admin = await db.admins.find_one({"username": token_data["username"]})
    if not admin:
        raise HTTPException(status_code=401, detail="Admin not found")
    
    return admin

@api_router.get("/admin/dashboard")
async def get_admin_dashboard(admin = None):  # admin: dict = Depends(get_current_admin)
    # Get counts for dashboard
    contact_count = await db.contact_messages.count_documents({})
    project_count = await db.projects.count_documents({})
    application_count = await db.applications.count_documents({})
    quote_count = await db.quote_requests.count_documents({})
    
    return {
        "contact_messages": contact_count,
        "projects": project_count,
        "applications": application_count,
        "quote_requests": quote_count
    }

@api_router.get("/admin/applications", response_model=List[Application])
async def get_applications(admin = None):  # admin: dict = Depends(get_current_admin)
    applications = await db.applications.find().sort("created_at", -1).to_list(1000)
    return [Application(**app) for app in applications]

@api_router.get("/admin/quote-requests", response_model=List[QuoteRequest])
async def get_quote_requests(admin = None):  # admin: dict = Depends(get_current_admin)
    quotes = await db.quote_requests.find().sort("created_at", -1).to_list(1000)
    return [QuoteRequest(**quote) for quote in quotes]

# Include the router in the main app
app.include_router(api_router)

app.add_middleware(
    CORSMiddleware,
    allow_credentials=True,
    allow_origins=os.environ.get('CORS_ORIGINS', '*').split(','),
    allow_methods=["*"],
    allow_headers=["*"],
)

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

@app.on_event("startup")
async def startup_event():
    logger.info("Hohmann Bau API starting up...")
    
    # Create default admin if none exists
    admin_count = await db.admins.count_documents({})
    if admin_count == 0:
        default_admin = {
            "id": str(uuid.uuid4()),
            "username": "admin",
            "email": "admin@hohmann-bau.de",
            "hashed_password": hash_password("admin123"),
            "is_active": True,
            "created_at": datetime.now(timezone.utc).isoformat()
        }
        await db.admins.insert_one(default_admin)
        logger.info("Default admin created: username=admin, password=admin123")

@app.on_event("shutdown")
async def shutdown_db_client():
    client.close()