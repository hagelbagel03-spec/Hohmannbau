import { useState, useEffect } from "react";
import axios from "axios";
import { Button } from "./ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Input } from "./ui/input";
import { Textarea } from "./ui/textarea";
import { Badge } from "./ui/badge";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "./ui/tabs";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "./ui/dialog";
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from "./ui/alert-dialog";
import { Label } from "./ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "./ui/select";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "./ui/table";
import { 
  Users, 
  Building, 
  MessageSquare, 
  FileText, 
  Settings, 
  Plus, 
  Edit2, 
  Trash2, 
  Eye,
  LogOut,
  BarChart3,
  Mail,
  Phone,
  Calendar,
  MapPin
} from "lucide-react";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

// Login Component
const AdminLogin = ({ onLogin }) => {
  const [credentials, setCredentials] = useState({ username: '', password: '' });
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState('');

  const handleLogin = async (e) => {
    e.preventDefault();
    setIsLoading(true);
    setError('');

    try {
      const response = await axios.post(`${API}/admin/login`, credentials);
      const token = response.data.access_token;
      localStorage.setItem('admin_token', token);
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      onLogin(token);
    } catch (error) {
      setError('Ungültige Anmeldedaten');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 flex items-center justify-center">
      <Card className="w-full max-w-md">
        <CardHeader className="text-center">
          <CardTitle className="text-2xl font-bold text-green-800">Hohmann Bau</CardTitle>
          <CardDescription>Admin-Panel Anmeldung</CardDescription>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleLogin} className="space-y-4">
            {error && (
              <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                {error}
              </div>
            )}
            <div>
              <Label htmlFor="username">Benutzername</Label>
              <Input
                id="username"
                type="text"
                value={credentials.username}
                onChange={(e) => setCredentials({...credentials, username: e.target.value})}
                required
              />
            </div>
            <div>
              <Label htmlFor="password">Passwort</Label>
              <Input
                id="password"
                type="password"
                value={credentials.password}
                onChange={(e) => setCredentials({...credentials, password: e.target.value})}
                required
              />
            </div>
            <Button 
              type="submit" 
              className="w-full bg-green-700 hover:bg-green-800"
              disabled={isLoading}
            >
              {isLoading ? 'Anmelden...' : 'Anmelden'}
            </Button>
          </form>
          <div className="mt-4 text-sm text-gray-600 text-center">
            Standard-Anmeldedaten: admin / admin123
          </div>
        </CardContent>
      </Card>
    </div>
  );
};

// Dashboard Component
const Dashboard = () => {
  const [stats, setStats] = useState({});

  useEffect(() => {
    fetchDashboardStats();
  }, []);

  const fetchDashboardStats = async () => {
    try {
      const response = await axios.get(`${API}/admin/dashboard`);
      setStats(response.data);
    } catch (error) {
      console.error('Error fetching dashboard stats:', error);
    }
  };

  return (
    <div className="space-y-6">
      <h2 className="text-3xl font-bold text-gray-900">Dashboard</h2>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Kontaktnachrichten</CardTitle>
            <MessageSquare className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{stats.contact_messages || 0}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Projekte</CardTitle>
            <Building className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{stats.projects || 0}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Bewerbungen</CardTitle>
            <Users className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{stats.applications || 0}</div>
          </CardContent>
        </Card>
        
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Angebotsanfragen</CardTitle>
            <FileText className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{stats.quote_requests || 0}</div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
};

// Support Management Component
const SupportManagement = () => {
  const [supportTickets, setSupportTickets] = useState([]);
  const [helpArticles, setHelpArticles] = useState([]);
  const [isCreateArticleOpen, setIsCreateArticleOpen] = useState(false);
  const [newArticle, setNewArticle] = useState({
    title: '',
    content: '',
    category: '',
    order: 0
  });

  useEffect(() => {
    fetchSupportTickets();
    fetchHelpArticles();
  }, []);

  const fetchSupportTickets = async () => {
    try {
      const response = await axios.get(`${API}/admin/support-tickets`);
      setSupportTickets(response.data);
    } catch (error) {
      console.error('Error fetching support tickets:', error);
    }
  };

  const fetchHelpArticles = async () => {
    try {
      const response = await axios.get(`${API}/admin/help-articles`);
      setHelpArticles(response.data);
    } catch (error) {
      console.error('Error fetching help articles:', error);
    }
  };

  const updateTicketStatus = async (ticketId, status) => {
    try {
      await axios.put(`${API}/admin/support-tickets/${ticketId}?status=${status}`);
      fetchSupportTickets();
    } catch (error) {
      console.error('Error updating ticket:', error);
    }
  };

  const createHelpArticle = async (e) => {
    e.preventDefault();
    try {
      await axios.post(`${API}/help-articles`, newArticle);
      setNewArticle({ title: '', content: '', category: '', order: 0 });
      setIsCreateArticleOpen(false);
      fetchHelpArticles();
    } catch (error) {
      console.error('Error creating help article:', error);
    }
  };

  const deleteHelpArticle = async (articleId) => {
    try {
      await axios.delete(`${API}/help-articles/${articleId}`);
      fetchHelpArticles();
    } catch (error) {
      console.error('Error deleting help article:', error);
    }
  };

  return (
    <div className="space-y-6">
      <h2 className="text-3xl font-bold text-gray-900">Support & Hilfe</h2>
      
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Support Tickets */}
        <Card>
          <CardHeader>
            <CardTitle>Support-Tickets ({supportTickets.length})</CardTitle>
            <CardDescription>Kundenanfragen und Support-Tickets verwalten</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-3 max-h-96 overflow-y-auto">
              {supportTickets.map((ticket) => (
                <Card key={ticket.id} className="border-l-4 border-l-blue-500">
                  <CardContent className="p-4">
                    <div className="flex justify-between items-start mb-2">
                      <h4 className="font-semibold">{ticket.subject}</h4>
                      <Badge variant={ticket.status === 'open' ? 'destructive' : ticket.status === 'closed' ? 'secondary' : 'default'}>
                        {ticket.status}
                      </Badge>
                    </div>
                    <p className="text-sm text-gray-600 mb-2">Von: {ticket.name} ({ticket.email})</p>
                    <p className="text-sm line-clamp-2">{ticket.message}</p>
                    <div className="flex space-x-2 mt-3">
                      <Button size="sm" onClick={() => updateTicketStatus(ticket.id, 'in_progress')}>
                        In Bearbeitung
                      </Button>
                      <Button size="sm" variant="outline" onClick={() => updateTicketStatus(ticket.id, 'closed')}>
                        Schließen
                      </Button>
                    </div>
                  </CardContent>
                </Card>
              ))}
              {supportTickets.length === 0 && (
                <p className="text-gray-500 text-center py-4">Keine Support-Tickets vorhanden</p>
              )}
            </div>
          </CardContent>
        </Card>

        {/* Help Articles */}
        <Card>
          <CardHeader>
            <CardTitle className="flex justify-between items-center">
              Hilfe-Artikel ({helpArticles.length})
              <Dialog open={isCreateArticleOpen} onOpenChange={setIsCreateArticleOpen}>
                <DialogTrigger asChild>
                  <Button className="bg-green-700 hover:bg-green-800">
                    <Plus className="w-4 h-4 mr-2" />
                    Artikel erstellen
                  </Button>
                </DialogTrigger>
                <DialogContent className="max-w-2xl">
                  <DialogHeader>
                    <DialogTitle>Neuen Hilfe-Artikel erstellen</DialogTitle>
                  </DialogHeader>
                  <form onSubmit={createHelpArticle} className="space-y-4">
                    <div>
                      <Label htmlFor="title">Titel</Label>
                      <Input
                        id="title"
                        value={newArticle.title}
                        onChange={(e) => setNewArticle({...newArticle, title: e.target.value})}
                        required
                      />
                    </div>
                    <div>
                      <Label htmlFor="category">Kategorie</Label>
                      <Input
                        id="category"
                        value={newArticle.category}
                        onChange={(e) => setNewArticle({...newArticle, category: e.target.value})}
                        required
                      />
                    </div>
                    <div>
                      <Label htmlFor="content">Inhalt</Label>
                      <Textarea
                        id="content"
                        value={newArticle.content}
                        onChange={(e) => setNewArticle({...newArticle, content: e.target.value})}
                        rows={6}
                        required
                      />
                    </div>
                    <div>
                      <Label htmlFor="order">Reihenfolge</Label>
                      <Input
                        id="order"
                        type="number"
                        value={newArticle.order}
                        onChange={(e) => setNewArticle({...newArticle, order: parseInt(e.target.value)})}
                      />
                    </div>
                    <div className="flex justify-end space-x-2">
                      <Button type="button" variant="outline" onClick={() => setIsCreateArticleOpen(false)}>
                        Abbrechen
                      </Button>
                      <Button type="submit" className="bg-green-700 hover:bg-green-800">
                        Artikel erstellen
                      </Button>
                    </div>
                  </form>
                </DialogContent>
              </Dialog>
            </CardTitle>
            <CardDescription>FAQ und Hilfe-Artikel verwalten</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-3 max-h-96 overflow-y-auto">
              {helpArticles.map((article) => (
                <Card key={article.id}>
                  <CardContent className="p-4">
                    <div className="flex justify-between items-start mb-2">
                      <h4 className="font-semibold">{article.title}</h4>
                      <Badge variant="secondary">{article.category}</Badge>
                    </div>
                    <p className="text-sm text-gray-600 line-clamp-2">{article.content}</p>
                    <div className="flex space-x-2 mt-3">
                      <Button size="sm" variant="outline">
                        <Edit2 className="w-4 h-4" />
                      </Button>
                      <AlertDialog>
                        <AlertDialogTrigger asChild>
                          <Button size="sm" variant="destructive">
                            <Trash2 className="w-4 h-4" />
                          </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                          <AlertDialogHeader>
                            <AlertDialogTitle>Artikel löschen?</AlertDialogTitle>
                            <AlertDialogDescription>
                              Diese Aktion kann nicht rückgängig gemacht werden.
                            </AlertDialogDescription>
                          </AlertDialogHeader>
                          <AlertDialogFooter>
                            <AlertDialogCancel>Abbrechen</AlertDialogCancel>
                            <AlertDialogAction onClick={() => deleteHelpArticle(article.id)}>
                              Löschen
                            </AlertDialogAction>
                          </AlertDialogFooter>
                        </AlertDialogContent>
                      </AlertDialog>
                    </div>
                  </CardContent>
                </Card>
              ))}
              {helpArticles.length === 0 && (
                <p className="text-gray-500 text-center py-4">Keine Hilfe-Artikel vorhanden</p>
              )}
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
};

// Content Management Component
const ContentManagement = () => {
  const [pages, setPages] = useState([
    { name: 'home', label: 'Homepage', description: 'Hero-Bereich und Startseite' },
    { name: 'services', label: 'Leistungen', description: 'Dienstleistungen und Services' },
    { name: 'projects', label: 'Projekte', description: 'Projektübersicht und Referenzen' },
    { name: 'team', label: 'Team', description: 'Team-Seite und Mitarbeiter' },
    { name: 'contact', label: 'Kontakt', description: 'Kontaktseite und Informationen' }
  ]);
  const [selectedPage, setSelectedPage] = useState(null);
  const [pageContent, setPageContent] = useState({});
  const [isEditing, setIsEditing] = useState(false);

  const fetchPageContent = async (pageName) => {
    try {
      const response = await axios.get(`${API}/content/${pageName}`);
      setPageContent(response.data.content || {});
    } catch (error) {
      setPageContent({});
    }
  };

  const savePageContent = async () => {
    try {
      await axios.post(`${API}/content`, {
        page_name: selectedPage.name,
        content: pageContent
      });
      alert('Inhalte erfolgreich gespeichert!');
      setIsEditing(false);
    } catch (error) {
      alert('Fehler beim Speichern der Inhalte.');
    }
  };

  const handlePageSelect = (page) => {
    setSelectedPage(page);
    fetchPageContent(page.name);
    setIsEditing(false);
  };

  const updateContent = (key, value) => {
    setPageContent(prev => ({
      ...prev,
      [key]: value
    }));
  };

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h2 className="text-3xl font-bold text-gray-900">Inhalte verwalten</h2>
        {selectedPage && (
          <div className="space-x-2">
            {isEditing ? (
              <>
                <Button onClick={savePageContent} className="bg-green-700 hover:bg-green-800">
                  Speichern
                </Button>
                <Button onClick={() => setIsEditing(false)} variant="outline">
                  Abbrechen
                </Button>
              </>
            ) : (
              <Button onClick={() => setIsEditing(true)} className="bg-blue-600 hover:bg-blue-700">
                Bearbeiten
              </Button>
            )}
          </div>
        )}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Page Selection */}
        <Card>
          <CardHeader>
            <CardTitle>Seite auswählen</CardTitle>
          </CardHeader>
          <CardContent className="space-y-2">
            {pages.map((page) => (
              <Card 
                key={page.name}
                className={`cursor-pointer transition-colors ${
                  selectedPage?.name === page.name ? 'bg-green-50 border-green-200' : 'hover:bg-gray-50'
                }`}
                onClick={() => handlePageSelect(page)}
              >
                <CardHeader className="py-3">
                  <CardTitle className="text-base">{page.label}</CardTitle>
                  <CardDescription className="text-sm">{page.description}</CardDescription>
                </CardHeader>
              </Card>
            ))}
          </CardContent>
        </Card>

        {/* Content Editor */}
        <div className="lg:col-span-2">
          {selectedPage ? (
            <Card>
              <CardHeader>
                <CardTitle>Inhalte bearbeiten: {selectedPage.label}</CardTitle>
                <CardDescription>
                  Bearbeiten Sie die Inhalte für die {selectedPage.label}-Seite
                </CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                {isEditing ? (
                  <div className="space-y-4">
                    {selectedPage.name === 'home' && (
                      <>
                        <div>
                          <Label htmlFor="hero_title">Hauptüberschrift</Label>
                          <Input
                            id="hero_title"
                            value={pageContent.hero_title || ''}
                            onChange={(e) => updateContent('hero_title', e.target.value)}
                            placeholder="Bauen mit Vertrauen"
                          />
                        </div>
                        <div>
                          <Label htmlFor="hero_subtitle">Untertitel</Label>
                          <Input
                            id="hero_subtitle"
                            value={pageContent.hero_subtitle || ''}
                            onChange={(e) => updateContent('hero_subtitle', e.target.value)}
                            placeholder="Ihr zuverlässiger Partner für..."
                          />
                        </div>
                        <div>
                          <Label htmlFor="hero_image">Hintergrundbild URL</Label>
                          <Input
                            id="hero_image"
                            value={pageContent.hero_image || ''}
                            onChange={(e) => updateContent('hero_image', e.target.value)}
                            placeholder="https://..."
                          />
                        </div>
                      </>
                    )}
                    
                    {(selectedPage.name === 'services' || selectedPage.name === 'projects' || selectedPage.name === 'team' || selectedPage.name === 'contact') && (
                      <>
                        <div>
                          <Label htmlFor="title">Seitentitel</Label>
                          <Input
                            id="title"
                            value={pageContent.title || ''}
                            onChange={(e) => updateContent('title', e.target.value)}
                            placeholder={`${selectedPage.label} Titel`}
                          />
                        </div>
                        <div>
                          <Label htmlFor="subtitle">Untertitel</Label>
                          <Input
                            id="subtitle"
                            value={pageContent.subtitle || ''}
                            onChange={(e) => updateContent('subtitle', e.target.value)}
                            placeholder={`${selectedPage.label} Untertitel`}
                          />
                        </div>
                        <div>
                          <Label htmlFor="description">Beschreibung</Label>
                          <Textarea
                            id="description"
                            value={pageContent.description || ''}
                            onChange={(e) => updateContent('description', e.target.value)}
                            placeholder={`Beschreibung für ${selectedPage.label}`}
                            rows={4}
                          />
                        </div>
                      </>
                    )}
                  </div>
                ) : (
                  <div className="space-y-4">
                    <p className="text-gray-600">
                      Klicken Sie auf "Bearbeiten" um die Inhalte dieser Seite zu ändern.
                    </p>
                    {Object.keys(pageContent).length > 0 && (
                      <div className="bg-gray-50 p-4 rounded-lg">
                        <h4 className="font-semibold mb-2">Aktuelle Inhalte:</h4>
                        <pre className="text-sm text-gray-700 whitespace-pre-wrap">
                          {JSON.stringify(pageContent, null, 2)}
                        </pre>
                      </div>
                    )}
                  </div>
                )}
              </CardContent>
            </Card>
          ) : (
            <Card>
              <CardContent className="text-center py-12">
                <h3 className="text-lg font-medium text-gray-900 mb-2">Seite auswählen</h3>
                <p className="text-gray-600">Wählen Sie eine Seite aus der Liste links aus, um deren Inhalte zu bearbeiten.</p>
              </CardContent>
            </Card>
          )}
        </div>
      </div>
    </div>
  );
};

// Contact Management Component  
const ContactManagement = () => {
  const [contactInfo, setContactInfo] = useState({
    address: '',
    phone: '',
    email: '',
    opening_hours: ''
  });
  const [isEditing, setIsEditing] = useState(false);

  useEffect(() => {
    fetchContactInfo();
  }, []);

  const fetchContactInfo = async () => {
    try {
      const response = await axios.get(`${API}/contact-info`);
      setContactInfo(response.data);
    } catch (error) {
      console.error('Error fetching contact info:', error);
    }
  };

  const saveContactInfo = async () => {
    try {
      await axios.put(`${API}/contact-info`, contactInfo);
      alert('Kontaktinformationen erfolgreich gespeichert!');
      setIsEditing(false);
    } catch (error) {
      alert('Fehler beim Speichern der Kontaktinformationen.');
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h2 className="text-3xl font-bold text-gray-900">Kontaktinformationen</h2>
        <div className="space-x-2">
          {isEditing ? (
            <>
              <Button onClick={saveContactInfo} className="bg-green-700 hover:bg-green-800">
                Speichern
              </Button>
              <Button onClick={() => setIsEditing(false)} variant="outline">
                Abbrechen
              </Button>
            </>
          ) : (
            <Button onClick={() => setIsEditing(true)} className="bg-blue-600 hover:bg-blue-700">
              Bearbeiten
            </Button>
          )}
        </div>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Kontaktdaten verwalten</CardTitle>
          <CardDescription>
            Bearbeiten Sie die Kontaktinformationen, die auf der Website angezeigt werden.
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-4">
          {isEditing ? (
            <>
              <div>
                <Label htmlFor="address">Adresse</Label>
                <Input
                  id="address"
                  value={contactInfo.address}
                  onChange={(e) => setContactInfo({...contactInfo, address: e.target.value})}
                  placeholder="Straße, PLZ Ort"
                />
              </div>
              <div>
                <Label htmlFor="phone">Telefon</Label>
                <Input
                  id="phone"
                  value={contactInfo.phone}
                  onChange={(e) => setContactInfo({...contactInfo, phone: e.target.value})}
                  placeholder="+49 123 456 789"
                />
              </div>
              <div>
                <Label htmlFor="email">E-Mail</Label>
                <Input
                  id="email"
                  type="email"
                  value={contactInfo.email}
                  onChange={(e) => setContactInfo({...contactInfo, email: e.target.value})}
                  placeholder="info@hohmann-bau.de"
                />
              </div>
              <div>
                <Label htmlFor="opening_hours">Öffnungszeiten</Label>
                <Input
                  id="opening_hours"
                  value={contactInfo.opening_hours}
                  onChange={(e) => setContactInfo({...contactInfo, opening_hours: e.target.value})}
                  placeholder="Mo-Fr: 08:00-17:00 Uhr"
                />
              </div>
            </>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <Label>Adresse</Label>
                <p className="text-gray-700">{contactInfo.address}</p>
              </div>
              <div>
                <Label>Telefon</Label>
                <p className="text-gray-700">{contactInfo.phone}</p>
              </div>
              <div>
                <Label>E-Mail</Label>
                <p className="text-gray-700">{contactInfo.email}</p>
              </div>
              <div>
                <Label>Öffnungszeiten</Label>
                <p className="text-gray-700">{contactInfo.opening_hours}</p>
              </div>
            </div>
          )}
        </CardContent>
      </Card>
    </div>
  );
};

// Projects Management
const ProjectsManagement = () => {
  const [projects, setProjects] = useState([]);
  const [isCreateDialogOpen, setIsCreateDialogOpen] = useState(false);
  const [editingProject, setEditingProject] = useState(null);
  const [newProject, setNewProject] = useState({
    title: '',
    category: '',
    description: '',
    image_url: ''
  });

  useEffect(() => {
    fetchProjects();
  }, []);

  const fetchProjects = async () => {
    try {
      const response = await axios.get(`${API}/projects`);
      setProjects(response.data);
    } catch (error) {
      console.error('Error fetching projects:', error);
    }
  };

  const handleCreateProject = async (e) => {
    e.preventDefault();
    try {
      await axios.post(`${API}/projects`, newProject);
      setNewProject({ title: '', category: '', description: '', image_url: '' });
      setIsCreateDialogOpen(false);
      fetchProjects();
    } catch (error) {
      console.error('Error creating project:', error);
    }
  };

  const handleDeleteProject = async (projectId) => {
    try {
      await axios.delete(`${API}/projects/${projectId}`);
      fetchProjects();
    } catch (error) {
      console.error('Error deleting project:', error);
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h2 className="text-3xl font-bold text-gray-900">Projekte verwalten</h2>
        <Dialog open={isCreateDialogOpen} onOpenChange={setIsCreateDialogOpen}>
          <DialogTrigger asChild>
            <Button className="bg-green-700 hover:bg-green-800">
              <Plus className="w-4 h-4 mr-2" />
              Projekt hinzufügen
            </Button>
          </DialogTrigger>
          <DialogContent className="max-w-2xl">
            <DialogHeader>
              <DialogTitle>Neues Projekt erstellen</DialogTitle>
            </DialogHeader>
            <form onSubmit={handleCreateProject} className="space-y-4">
              <div>
                <Label htmlFor="title">Titel</Label>
                <Input
                  id="title"
                  value={newProject.title}
                  onChange={(e) => setNewProject({...newProject, title: e.target.value})}
                  required
                />
              </div>
              <div>
                <Label htmlFor="category">Kategorie</Label>
                <Select onValueChange={(value) => setNewProject({...newProject, category: value})}>
                  <SelectTrigger>
                    <SelectValue placeholder="Kategorie wählen" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="Wohnbau">Wohnbau</SelectItem>
                    <SelectItem value="Gewerbebau">Gewerbebau</SelectItem>
                    <SelectItem value="Sanierung">Sanierung</SelectItem>
                    <SelectItem value="Infrastruktur">Infrastruktur</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <div>
                <Label htmlFor="image_url">Bild-URL</Label>
                <Input
                  id="image_url"
                  value={newProject.image_url}
                  onChange={(e) => setNewProject({...newProject, image_url: e.target.value})}
                  required
                />
              </div>
              <div>
                <Label htmlFor="description">Beschreibung</Label>
                <Textarea
                  id="description"
                  value={newProject.description}
                  onChange={(e) => setNewProject({...newProject, description: e.target.value})}
                  rows={4}
                  required
                />
              </div>
              <div className="flex justify-end space-x-2">
                <Button type="button" variant="outline" onClick={() => setIsCreateDialogOpen(false)}>
                  Abbrechen
                </Button>
                <Button type="submit" className="bg-green-700 hover:bg-green-800">
                  Projekt erstellen
                </Button>
              </div>
            </form>
          </DialogContent>
        </Dialog>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {projects.map((project) => (
          <Card key={project.id}>
            <div className="relative h-48 overflow-hidden">
              <img 
                src={project.image_url} 
                alt={project.title}
                className="w-full h-full object-cover"
              />
              <Badge className="absolute top-4 left-4 bg-green-700">
                {project.category}
              </Badge>
            </div>
            <CardHeader>
              <CardTitle className="text-lg">{project.title}</CardTitle>
              <CardDescription className="line-clamp-2">
                {project.description}
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div className="flex justify-end space-x-2">
                <Button size="sm" variant="outline">
                  <Edit2 className="w-4 h-4" />
                </Button>
                <AlertDialog>
                  <AlertDialogTrigger asChild>
                    <Button size="sm" variant="destructive">
                      <Trash2 className="w-4 h-4" />
                    </Button>
                  </AlertDialogTrigger>
                  <AlertDialogContent>
                    <AlertDialogHeader>
                      <AlertDialogTitle>Projekt löschen?</AlertDialogTitle>
                      <AlertDialogDescription>
                        Diese Aktion kann nicht rückgängig gemacht werden.
                      </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                      <AlertDialogCancel>Abbrechen</AlertDialogCancel>
                      <AlertDialogAction onClick={() => handleDeleteProject(project.id)}>
                        Löschen
                      </AlertDialogAction>
                    </AlertDialogFooter>
                  </AlertDialogContent>
                </AlertDialog>
              </div>
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  );
};

// Contact Messages Management
const ContactMessagesManagement = () => {
  const [messages, setMessages] = useState([]);

  useEffect(() => {
    fetchMessages();
  }, []);

  const fetchMessages = async () => {
    try {
      const response = await axios.get(`${API}/contact`);
      setMessages(response.data);
    } catch (error) {
      console.error('Error fetching messages:', error);
    }
  };

  return (
    <div className="space-y-6">
      <h2 className="text-3xl font-bold text-gray-900">Kontaktnachrichten</h2>
      
      <Card>
        <CardContent className="p-0">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Name</TableHead>
                <TableHead>E-Mail</TableHead>
                <TableHead>Nachricht</TableHead>
                <TableHead>Datum</TableHead>
                <TableHead>Aktionen</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {messages.map((message) => (
                <TableRow key={message.id}>
                  <TableCell className="font-medium">{message.name}</TableCell>
                  <TableCell>{message.email}</TableCell>
                  <TableCell className="max-w-xs truncate">{message.message}</TableCell>
                  <TableCell>{new Date(message.created_at).toLocaleDateString('de-DE')}</TableCell>
                  <TableCell>
                    <Dialog>
                      <DialogTrigger asChild>
                        <Button size="sm" variant="outline">
                          <Eye className="w-4 h-4" />
                        </Button>
                      </DialogTrigger>
                      <DialogContent>
                        <DialogHeader>
                          <DialogTitle>Nachricht von {message.name}</DialogTitle>
                        </DialogHeader>
                        <div className="space-y-4">
                          <div>
                            <Label>E-Mail:</Label>
                            <p className="text-sm text-gray-600">{message.email}</p>
                          </div>
                          <div>
                            <Label>Datum:</Label>
                            <p className="text-sm text-gray-600">
                              {new Date(message.created_at).toLocaleString('de-DE')}
                            </p>
                          </div>
                          <div>
                            <Label>Nachricht:</Label>
                            <p className="text-sm text-gray-600 mt-2 p-3 bg-gray-50 rounded">
                              {message.message}
                            </p>
                          </div>
                        </div>
                      </DialogContent>
                    </Dialog>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </CardContent>
      </Card>
    </div>
  );
};

// Main Admin Panel Component
const AdminPanel = () => {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [activeTab, setActiveTab] = useState('dashboard');

  useEffect(() => {
    const token = localStorage.getItem('admin_token');
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      setIsAuthenticated(true);
    }
  }, []);

  const handleLogin = (token) => {
    setIsAuthenticated(true);
  };

  const handleLogout = () => {
    localStorage.removeItem('admin_token');
    delete axios.defaults.headers.common['Authorization'];
    setIsAuthenticated(false);
  };

  if (!isAuthenticated) {
    return <AdminLogin onLogin={handleLogin} />;
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="border-b bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="font-bold text-2xl text-green-800">Hohmann Bau - Admin</div>
            <Button onClick={handleLogout} variant="outline">
              <LogOut className="w-4 h-4 mr-2" />
              Abmelden
            </Button>
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <Tabs value={activeTab} onValueChange={setActiveTab}>
          <TabsList className="grid w-full grid-cols-6">
            <TabsTrigger value="dashboard">Dashboard</TabsTrigger>
            <TabsTrigger value="content">Inhalte</TabsTrigger>
            <TabsTrigger value="projects">Projekte</TabsTrigger>
            <TabsTrigger value="messages">Nachrichten</TabsTrigger>
            <TabsTrigger value="contact">Kontakt</TabsTrigger>
            <TabsTrigger value="team">Team</TabsTrigger>
          </TabsList>

          <div className="mt-8">
            <TabsContent value="dashboard">
              <Dashboard />
            </TabsContent>

            <TabsContent value="content">
              <ContentManagement />
            </TabsContent>

            <TabsContent value="projects">
              <ProjectsManagement />
            </TabsContent>

            <TabsContent value="messages">
              <ContactMessagesManagement />
            </TabsContent>

            <TabsContent value="contact">
              <ContactManagement />
            </TabsContent>

            <TabsContent value="team">
              <div className="text-center py-12">
                <h3 className="text-lg font-medium text-gray-900">Team-Verwaltung</h3>
                <p className="text-gray-600 mt-2">Team-Management wird in der nächsten Version verfügbar sein.</p>
              </div>
            </TabsContent>
          </div>
        </Tabs>
      </div>
    </div>
  );
};

export default AdminPanel;