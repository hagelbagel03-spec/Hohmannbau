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
import { Switch } from "./ui/switch";
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
  MapPin,
  Palette,
  Type,
  Layout,
  Upload,
  Image,
  Globe,
  Menu,
  Save,
  RefreshCw,
  Download,
  Copy,
  Monitor,
  Smartphone
} from "lucide-react";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

// Color Picker Component
const ColorPicker = ({ color, onChange, label }) => {
  return (
    <div className="space-y-2">
      <Label>{label}</Label>
      <div className="flex items-center space-x-3">
        <input
          type="color"
          value={color}
          onChange={(e) => onChange(e.target.value)}
          className="w-12 h-12 rounded border-2 border-gray-300 cursor-pointer"
        />
        <Input
          type="text"
          value={color}
          onChange={(e) => onChange(e.target.value)}
          placeholder="#ffffff"
          className="flex-1"
        />
      </div>
    </div>
  );
};

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
    <div className="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center">
      <Card className="w-full max-w-md shadow-2xl">
        <CardHeader className="text-center bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
          <CardTitle className="text-3xl font-bold">Hohmann Bau</CardTitle>
          <CardDescription className="text-green-100">Universal Admin-Panel</CardDescription>
        </CardHeader>
        <CardContent className="p-8">
          <form onSubmit={handleLogin} className="space-y-6">
            {error && (
              <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {error}
              </div>
            )}
            <div>
              <Label htmlFor="username" className="text-gray-700 font-medium">Benutzername</Label>
              <Input
                id="username"
                type="text"
                value={credentials.username}
                onChange={(e) => setCredentials({...credentials, username: e.target.value})}
                className="mt-2"
                placeholder="admin"
                required
              />
            </div>
            <div>
              <Label htmlFor="password" className="text-gray-700 font-medium">Passwort</Label>
              <Input
                id="password"
                type="password"
                value={credentials.password}
                onChange={(e) => setCredentials({...credentials, password: e.target.value})}
                className="mt-2"
                placeholder="••••••••"
                required
              />
            </div>
            <Button 
              type="submit" 
              className="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-lg py-6"
              disabled={isLoading}
            >
              {isLoading ? (
                <div className="flex items-center">
                  <div className="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-3"></div>
                  Anmelden...
                </div>
              ) : (
                'Anmelden'
              )}
            </Button>
          </form>
          <div className="mt-6 text-center">
            <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
              <h4 className="font-semibold text-blue-900 mb-2">Standard-Zugangsdaten:</h4>
              <p className="text-blue-700"><strong>Benutzername:</strong> admin</p>
              <p className="text-blue-700"><strong>Passwort:</strong> admin123</p>
            </div>
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
    <div className="space-y-8">
      <div className="flex items-center justify-between">
        <h2 className="text-4xl font-bold text-gray-900">Universal Admin Dashboard</h2>
        <Button onClick={fetchDashboardStats} className="bg-blue-600 hover:bg-blue-700">
          <Refresh className="w-4 h-4 mr-2" />
          Aktualisieren
        </Button>
      </div>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {[
          { key: 'contact_messages', label: 'Kontaktnachrichten', icon: MessageSquare, color: 'blue' },
          { key: 'projects', label: 'Projekte', icon: Building, color: 'green' },
          { key: 'applications', label: 'Bewerbungen', icon: Users, color: 'purple' },
          { key: 'quote_requests', label: 'Angebotsanfragen', icon: FileText, color: 'orange' },
          { key: 'support_tickets', label: 'Support Tickets', icon: MessageSquare, color: 'red' },
          { key: 'news_articles', label: 'News Artikel', icon: FileText, color: 'indigo' },
          { key: 'job_postings', label: 'Stellenausschreibungen', icon: Users, color: 'yellow' },
          { key: 'services', label: 'Services', icon: Settings, color: 'teal' },
          { key: 'team_members', label: 'Team Mitglieder', icon: Users, color: 'pink' }
        ].map((item, index) => (
          <Card key={item.key} className="hover:shadow-lg transition-shadow duration-300">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium">{item.label}</CardTitle>
              <item.icon className={`h-4 w-4 text-${item.color}-600`} />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">{stats[item.key] || 0}</div>
              <p className="text-xs text-muted-foreground">
                {index % 2 === 0 ? '+20.1% vom letzten Monat' : 'Aktueller Stand'}
              </p>
            </CardContent>
          </Card>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <Card className="bg-gradient-to-r from-green-50 to-blue-50">
          <CardHeader>
            <CardTitle className="text-2xl text-gray-900">Willkommen im Universal Admin Panel</CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <p className="text-gray-600">
              Mit diesem erweiterten Admin-Panel können Sie <strong>ALLES</strong> an Ihrer Website ändern:
            </p>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div className="space-y-2">
                <h4 className="font-semibold text-green-700">📝 Inhalte</h4>
                <ul className="text-sm space-y-1 text-gray-600">
                  <li>• Alle Seiten bearbeiten</li>
                  <li>• Texte & Bilder ändern</li>
                  <li>• Navigation anpassen</li>
                  <li>• Footer verwalten</li>
                </ul>
              </div>
              <div className="space-y-2">
                <h4 className="font-semibold text-blue-700">🎨 Design</h4>
                <ul className="text-sm space-y-1 text-gray-600">
                  <li>• Farben ändern</li>
                  <li>• Schriftarten anpassen</li>
                  <li>• Layout bearbeiten</li>
                  <li>• Buttons stylen</li>
                </ul>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle className="text-xl">Schnellaktionen</CardTitle>
          </CardHeader>
          <CardContent className="space-y-3">
            <Button className="w-full justify-start" variant="outline">
              <Edit2 className="w-4 h-4 mr-3" />
              Homepage bearbeiten
            </Button>
            <Button className="w-full justify-start" variant="outline">
              <Palette className="w-4 h-4 mr-3" />
              Design anpassen
            </Button>
            <Button className="w-full justify-start" variant="outline">
              <Upload className="w-4 h-4 mr-3" />
              Bilder hochladen
            </Button>
            <Button className="w-full justify-start" variant="outline">
              <Settings className="w-4 h-4 mr-3" />
              Einstellungen
            </Button>
          </CardContent>
        </Card>
      </div>
    </div>
  );
};

// Universal Page Editor Component
const UniversalPageEditor = () => {
  const [selectedPage, setSelectedPage] = useState(null);
  const [pageContent, setPageContent] = useState({});
  const [isEditing, setIsEditing] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const pages = [
    { 
      name: 'home', 
      label: 'Homepage', 
      description: 'Hero-Bereich, Willkommenstext, Call-to-Actions',
      icon: '🏠',
      fields: ['hero_title', 'hero_subtitle', 'hero_image', 'hero_cta_text', 'about_title', 'about_text']
    },
    { 
      name: 'services', 
      label: 'Leistungen', 
      description: 'Service-Beschreibungen, Features, Preise',
      icon: '🔧',
      fields: ['title', 'subtitle', 'description']
    },
    { 
      name: 'projects', 
      label: 'Projekte', 
      description: 'Projekt-Portfolio, Referenzen, Galerie',
      icon: '🏗️',
      fields: ['title', 'subtitle', 'description']
    },
    { 
      name: 'team', 
      label: 'Team', 
      description: 'Team-Mitglieder, Biografien, Kontakte',
      icon: '👥',
      fields: ['title', 'subtitle', 'description']
    },
    { 
      name: 'contact', 
      label: 'Kontakt', 
      description: 'Kontaktinformationen, Formulare, Karten',
      icon: '📞',
      fields: ['title', 'subtitle', 'description']
    },
    { 
      name: 'career', 
      label: 'Karriere', 
      description: 'Stellenausschreibungen, Bewerbungsprozess',
      icon: '💼',
      fields: ['title', 'subtitle', 'description']
    },
    { 
      name: 'footer', 
      label: 'Footer', 
      description: 'Footer-Links, Copyright, Soziale Medien',
      icon: '📄',
      fields: ['company_name', 'company_description', 'copyright']
    },
    { 
      name: 'navigation', 
      label: 'Navigation', 
      description: 'Menü-Punkte, Logo, CTA-Button',
      icon: '🧭',
      fields: ['logo_text', 'cta_button_text']
    }
  ];

  const fetchPageContent = async (pageName) => {
    setIsLoading(true);
    try {
      const response = await axios.get(`${API}/content/${pageName}`);
      if (response.data && response.data.content) {
        setPageContent(response.data.content);
      } else {
        setPageContent(getDefaultContent(pageName));
      }
    } catch (error) {
      setPageContent(getDefaultContent(pageName));
    }
    setIsLoading(false);
  };

  const getDefaultContent = (pageName) => {
    const defaults = {
      home: {
        hero_title: "Bauen mit Vertrauen",
        hero_subtitle: "Ihr zuverlässiger Partner für Hochbau, Tiefbau und Sanierungen",
        hero_image: "https://images.unsplash.com/photo-1599995903128-531fc7fb694b",
        hero_cta_text: "Jetzt Angebot anfordern",
        about_title: "Über uns",
        about_text: "Mit über 25 Jahren Erfahrung sind wir Ihr vertrauensvoller Partner."
      },
      services: {
        title: "Unsere Leistungen",
        subtitle: "Umfassende Baulösungen aus einer Hand",
        description: "Von der ersten Idee bis zur schlüsselfertigen Übergabe."
      },
      navigation: {
        logo_text: "Hohmann Bau",
        cta_button_text: "Angebot erhalten"
      },
      footer: {
        company_name: "Hohmann Bau GmbH",
        company_description: "Ihr Partner für professionelle Bauprojekte",
        copyright: "© 2024 Hohmann Bau GmbH. Alle Rechte vorbehalten."
      }
    };
    return defaults[pageName] || {};
  };

  const savePageContent = async () => {
    setIsLoading(true);
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
    setIsLoading(false);
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

  const renderFieldEditor = (field, value) => {
    const fieldLabels = {
      hero_title: 'Hero Titel',
      hero_subtitle: 'Hero Untertitel', 
      hero_image: 'Hero Bild URL',
      hero_cta_text: 'Hero Button Text',
      about_title: 'Über uns Titel',
      about_text: 'Über uns Text',
      title: 'Seitentitel',
      subtitle: 'Untertitel',
      description: 'Beschreibung',
      company_name: 'Firmenname',
      company_description: 'Firmenbeschreibung',
      copyright: 'Copyright Text',
      logo_text: 'Logo Text',
      cta_button_text: 'Button Text'
    };

    const isLongText = ['description', 'about_text'].includes(field);
    
    return (
      <div key={field}>
        <Label htmlFor={field} className="text-sm font-medium text-gray-700">
          {fieldLabels[field] || field}
        </Label>
        {isLongText ? (
          <Textarea
            id={field}
            value={value || ''}
            onChange={(e) => updateContent(field, e.target.value)}
            placeholder={`${fieldLabels[field]} eingeben...`}
            rows={4}
            className="mt-1"
          />
        ) : (
          <Input
            id={field}
            value={value || ''}
            onChange={(e) => updateContent(field, e.target.value)}
            placeholder={`${fieldLabels[field]} eingeben...`}
            className="mt-1"
          />
        )}
      </div>
    );
  };

  return (
    <div className="space-y-8">
      <div className="flex justify-between items-center">
        <h2 className="text-4xl font-bold text-gray-900">Universal Page Editor</h2>
        <div className="flex items-center space-x-3">
          {selectedPage && (
            <>
              {isEditing ? (
                <>
                  <Button 
                    onClick={savePageContent} 
                    className="bg-green-600 hover:bg-green-700"
                    disabled={isLoading}
                  >
                    <Save className="w-4 h-4 mr-2" />
                    {isLoading ? 'Speichern...' : 'Speichern'}
                  </Button>
                  <Button 
                    onClick={() => setIsEditing(false)} 
                    variant="outline"
                    disabled={isLoading}
                  >
                    Abbrechen
                  </Button>
                </>
              ) : (
                <Button 
                  onClick={() => setIsEditing(true)} 
                  className="bg-blue-600 hover:bg-blue-700"
                >
                  <Edit2 className="w-4 h-4 mr-2" />
                  Bearbeiten
                </Button>
              )}
            </>
          )}
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {/* Page Selection Sidebar */}
        <div className="lg:col-span-1">
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center">
                <Globe className="w-5 h-5 mr-2" />
                Seiten auswählen
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-2">
              {pages.map((page) => (
                <Card 
                  key={page.name}
                  className={`cursor-pointer transition-all duration-200 hover:shadow-md ${
                    selectedPage?.name === page.name 
                      ? 'bg-green-50 border-green-200 shadow-lg' 
                      : 'hover:bg-gray-50'
                  }`}
                  onClick={() => handlePageSelect(page)}
                >
                  <CardHeader className="py-4">
                    <div className="flex items-center space-x-3">
                      <span className="text-2xl">{page.icon}</span>
                      <div>
                        <CardTitle className="text-base">{page.label}</CardTitle>
                        <CardDescription className="text-xs">{page.description}</CardDescription>
                      </div>
                    </div>
                  </CardHeader>
                </Card>
              ))}
            </CardContent>
          </Card>
        </div>

        {/* Content Editor */}
        <div className="lg:col-span-3">
          {selectedPage ? (
            <Card>
              <CardHeader>
                <div className="flex items-center justify-between">
                  <div className="flex items-center space-x-3">
                    <span className="text-3xl">{selectedPage.icon}</span>
                    <div>
                      <CardTitle className="text-2xl">
                        {selectedPage.label} bearbeiten
                      </CardTitle>
                      <CardDescription>
                        {selectedPage.description}
                      </CardDescription>
                    </div>
                  </div>
                  <Badge variant={isEditing ? "default" : "secondary"}>
                    {isEditing ? "Bearbeitungsmodus" : "Ansichtsmodus"}
                  </Badge>
                </div>
              </CardHeader>
              <CardContent>
                {isLoading ? (
                  <div className="flex items-center justify-center py-12">
                    <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                    <span className="ml-3">Lade Inhalte...</span>
                  </div>
                ) : (
                  <div className="space-y-6">
                    {isEditing ? (
                      <div className="space-y-6">
                        <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
                          <h4 className="font-semibold text-blue-900 mb-2">Bearbeitungsmodus</h4>
                          <p className="text-blue-700 text-sm">
                            Ändern Sie die Inhalte und klicken Sie auf "Speichern" um die Änderungen zu übernehmen.
                          </p>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                          {selectedPage.fields.map(field => 
                            renderFieldEditor(field, pageContent[field])
                          )}
                        </div>
                      </div>
                    ) : (
                      <div className="space-y-6">
                        <p className="text-gray-600 bg-gray-50 p-4 rounded-lg">
                          Klicken Sie auf <strong>"Bearbeiten"</strong> um die Inhalte dieser Seite zu ändern.
                        </p>
                        {Object.keys(pageContent).length > 0 && (
                          <div className="bg-gray-50 p-6 rounded-lg">
                            <h4 className="font-semibold mb-4 text-gray-900">Aktuelle Inhalte:</h4>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                              {Object.entries(pageContent).map(([key, value]) => (
                                <div key={key} className="bg-white p-4 rounded border">
                                  <Label className="text-sm font-medium text-gray-600">{key}</Label>
                                  <p className="text-gray-900 mt-1 break-words">
                                    {typeof value === 'string' && value.length > 100 
                                      ? `${value.substring(0, 100)}...` 
                                      : value}
                                  </p>
                                </div>
                              ))}
                            </div>
                          </div>
                        )}
                      </div>
                    )}
                  </div>
                )}
              </CardContent>
            </Card>
          ) : (
            <Card>
              <CardContent className="text-center py-20">
                <Globe className="w-16 h-16 mx-auto text-gray-400 mb-4" />
                <h3 className="text-xl font-medium text-gray-900 mb-2">Seite auswählen</h3>
                <p className="text-gray-600">
                  Wählen Sie eine Seite aus der Liste links aus, um deren Inhalte zu bearbeiten.
                </p>
              </CardContent>
            </Card>
          )}
        </div>
      </div>
    </div>
  );
};

// Design System Manager Component
const DesignSystemManager = () => {
  const [theme, setTheme] = useState({
    primary_color: '#16a34a',
    secondary_color: '#059669', 
    accent_color: '#10b981',
    background_color: '#ffffff',
    text_color: '#1f2937',
    border_color: '#e5e7eb'
  });

  const [typography, setTypography] = useState({
    font_family: 'Inter, system-ui, sans-serif',
    heading_font: 'Inter, system-ui, sans-serif',
    font_size_base: '16px',
    font_size_lg: '18px',
    font_size_xl: '20px',
    line_height: '1.6'
  });

  const [layout, setLayout] = useState({
    container_width: '1200px',
    section_padding: '80px',
    card_border_radius: '8px',
    button_border_radius: '6px'
  });

  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    fetchDesignSettings();
  }, []);

  const fetchDesignSettings = async () => {
    try {
      const [themeRes, typoRes, layoutRes] = await Promise.all([
        axios.get(`${API}/settings/theme`),
        axios.get(`${API}/settings/typography`),
        axios.get(`${API}/settings/layout`)
      ]);

      if (themeRes.data?.settings) setTheme(themeRes.data.settings);
      if (typoRes.data?.settings) setTypography(typoRes.data.settings);
      if (layoutRes.data?.settings) setLayout(layoutRes.data.settings);
    } catch (error) {
      console.log('Using default design settings');
    }
  };

  const saveDesignSettings = async () => {
    setIsLoading(true);
    try {
      await Promise.all([
        axios.post(`${API}/settings`, { setting_name: 'theme', settings: theme }),
        axios.post(`${API}/settings`, { setting_name: 'typography', settings: typography }),
        axios.post(`${API}/settings`, { setting_name: 'layout', settings: layout })
      ]);
      alert('Design-Einstellungen erfolgreich gespeichert!');
    } catch (error) {
      alert('Fehler beim Speichern der Design-Einstellungen.');
    }
    setIsLoading(false);
  };

  const previewCSS = () => {
    return `
/* Generated CSS Variables */
:root {
  --primary-color: ${theme.primary_color};
  --secondary-color: ${theme.secondary_color};
  --accent-color: ${theme.accent_color};
  --background-color: ${theme.background_color};
  --text-color: ${theme.text_color};
  --border-color: ${theme.border_color};
  
  --font-family: ${typography.font_family};
  --heading-font: ${typography.heading_font};
  --font-size-base: ${typography.font_size_base};
  --font-size-lg: ${typography.font_size_lg};
  --font-size-xl: ${typography.font_size_xl};
  --line-height: ${typography.line_height};
  
  --container-width: ${layout.container_width};
  --section-padding: ${layout.section_padding};
  --card-border-radius: ${layout.card_border_radius};
  --button-border-radius: ${layout.button_border_radius};
}`;
  };

  return (
    <div className="space-y-8">
      <div className="flex justify-between items-center">
        <h2 className="text-4xl font-bold text-gray-900">Design System Manager</h2>
        <div className="flex space-x-3">
          <Button 
            onClick={saveDesignSettings}
            className="bg-green-600 hover:bg-green-700"
            disabled={isLoading}
          >
            <Save className="w-4 h-4 mr-2" />
            {isLoading ? 'Speichern...' : 'Alle Änderungen speichern'}
          </Button>
          <Dialog>
            <DialogTrigger asChild>
              <Button variant="outline">
                <Eye className="w-4 h-4 mr-2" />
                CSS Vorschau
              </Button>
            </DialogTrigger>
            <DialogContent className="max-w-4xl">
              <DialogHeader>
                <DialogTitle>Generierte CSS Variablen</DialogTitle>
              </DialogHeader>
              <div className="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm overflow-auto max-h-96">
                <pre>{previewCSS()}</pre>
              </div>
            </DialogContent>
          </Dialog>
        </div>
      </div>

      <Tabs defaultValue="colors" className="space-y-6">
        <TabsList className="grid w-full grid-cols-4">
          <TabsTrigger value="colors" className="flex items-center">
            <Palette className="w-4 h-4 mr-2" />
            Farben
          </TabsTrigger>
          <TabsTrigger value="typography" className="flex items-center">
            <Type className="w-4 h-4 mr-2" />
            Typografie
          </TabsTrigger>
          <TabsTrigger value="layout" className="flex items-center">
            <Layout className="w-4 h-4 mr-2" />
            Layout
          </TabsTrigger>
          <TabsTrigger value="preview" className="flex items-center">
            <Monitor className="w-4 h-4 mr-2" />
            Vorschau
          </TabsTrigger>
        </TabsList>

        <TabsContent value="colors" className="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle>Farbschema anpassen</CardTitle>
              <CardDescription>
                Definieren Sie die Hauptfarben Ihrer Website. Änderungen werden sofort angewendet.
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <ColorPicker
                  color={theme.primary_color}
                  onChange={(color) => setTheme({...theme, primary_color: color})}
                  label="Primärfarbe (Buttons, Links)"
                />
                <ColorPicker
                  color={theme.secondary_color}
                  onChange={(color) => setTheme({...theme, secondary_color: color})}
                  label="Sekundärfarbe (Hover-Effekte)"
                />
                <ColorPicker
                  color={theme.accent_color}
                  onChange={(color) => setTheme({...theme, accent_color: color})}
                  label="Akzentfarbe (Highlights)"
                />
                <ColorPicker
                  color={theme.background_color}
                  onChange={(color) => setTheme({...theme, background_color: color})}
                  label="Hintergrundfarbe"
                />
                <ColorPicker
                  color={theme.text_color}
                  onChange={(color) => setTheme({...theme, text_color: color})}
                  label="Textfarbe"
                />
                <ColorPicker
                  color={theme.border_color}
                  onChange={(color) => setTheme({...theme, border_color: color})}
                  label="Rahmenfarbe"
                />
              </div>
            </CardContent>
          </Card>
        </TabsContent>

        <TabsContent value="typography" className="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle>Typografie-Einstellungen</CardTitle>
              <CardDescription>
                Konfigurieren Sie Schriftarten und Textgrößen für Ihre Website.
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <Label>Hauptschriftart</Label>
                  <Select 
                    value={typography.font_family} 
                    onValueChange={(value) => setTypography({...typography, font_family: value})}
                  >
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Inter, system-ui, sans-serif">Inter (Modern)</SelectItem>
                      <SelectItem value="Roboto, sans-serif">Roboto (Google)</SelectItem>
                      <SelectItem value="Arial, sans-serif">Arial (Classic)</SelectItem>
                      <SelectItem value="Georgia, serif">Georgia (Serif)</SelectItem>
                      <SelectItem value="'Times New Roman', serif">Times New Roman</SelectItem>
                    </SelectContent>
                  </Select>
                </div>

                <div>
                  <Label>Überschriften-Schriftart</Label>
                  <Select 
                    value={typography.heading_font} 
                    onValueChange={(value) => setTypography({...typography, heading_font: value})}
                  >
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Inter, system-ui, sans-serif">Inter (Modern)</SelectItem>
                      <SelectItem value="Roboto, sans-serif">Roboto (Google)</SelectItem>
                      <SelectItem value="Arial, sans-serif">Arial (Classic)</SelectItem>
                      <SelectItem value="Georgia, serif">Georgia (Serif)</SelectItem>
                      <SelectItem value="'Times New Roman', serif">Times New Roman</SelectItem>
                    </SelectContent>
                  </Select>
                </div>

                <div>
                  <Label>Basis Schriftgröße</Label>
                  <Input
                    value={typography.font_size_base}
                    onChange={(e) => setTypography({...typography, font_size_base: e.target.value})}
                    placeholder="16px"
                  />
                </div>

                <div>
                  <Label>Große Schriftgröße</Label>
                  <Input
                    value={typography.font_size_lg}
                    onChange={(e) => setTypography({...typography, font_size_lg: e.target.value})}
                    placeholder="18px"
                  />
                </div>

                <div>
                  <Label>Extra Große Schriftgröße</Label>
                  <Input
                    value={typography.font_size_xl}
                    onChange={(e) => setTypography({...typography, font_size_xl: e.target.value})}
                    placeholder="20px"
                  />
                </div>

                <div>
                  <Label>Zeilenhöhe</Label>
                  <Input
                    value={typography.line_height}
                    onChange={(e) => setTypography({...typography, line_height: e.target.value})}
                    placeholder="1.6"
                  />
                </div>
              </div>
            </CardContent>
          </Card>
        </TabsContent>

        <TabsContent value="layout" className="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle>Layout-Einstellungen</CardTitle>
              <CardDescription>
                Definieren Sie Abstände, Größen und andere Layout-Parameter.
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <Label>Container-Breite</Label>
                  <Input
                    value={layout.container_width}
                    onChange={(e) => setLayout({...layout, container_width: e.target.value})}
                    placeholder="1200px"
                  />
                  <p className="text-sm text-gray-500 mt-1">Maximale Breite der Inhalte</p>
                </div>

                <div>
                  <Label>Sektions-Abstand</Label>
                  <Input
                    value={layout.section_padding}
                    onChange={(e) => setLayout({...layout, section_padding: e.target.value})}
                    placeholder="80px"
                  />
                  <p className="text-sm text-gray-500 mt-1">Vertikaler Abstand zwischen Bereichen</p>
                </div>

                <div>
                  <Label>Karten-Rundung</Label>
                  <Input
                    value={layout.card_border_radius}
                    onChange={(e) => setLayout({...layout, card_border_radius: e.target.value})}
                    placeholder="8px"
                  />
                  <p className="text-sm text-gray-500 mt-1">Rundung von Karten und Boxen</p>
                </div>

                <div>
                  <Label>Button-Rundung</Label>
                  <Input
                    value={layout.button_border_radius}
                    onChange={(e) => setLayout({...layout, button_border_radius: e.target.value})}
                    placeholder="6px"
                  />
                  <p className="text-sm text-gray-500 mt-1">Rundung von Buttons</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </TabsContent>

        <TabsContent value="preview" className="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle>Live Vorschau</CardTitle>
              <CardDescription>
                Sehen Sie, wie Ihre Änderungen auf der Website aussehen werden.
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div 
                className="p-8 rounded-lg border-2 border-dashed border-gray-300"
                style={{
                  backgroundColor: theme.background_color,
                  color: theme.text_color,
                  fontFamily: typography.font_family,
                  fontSize: typography.font_size_base,
                  lineHeight: typography.line_height
                }}
              >
                <div className="space-y-6">
                  <h1 
                    style={{ 
                      fontFamily: typography.heading_font,
                      fontSize: typography.font_size_xl,
                      color: theme.primary_color 
                    }}
                    className="font-bold"
                  >
                    Hohmann Bau
                  </h1>
                  
                  <h2 
                    style={{ 
                      fontFamily: typography.heading_font,
                      fontSize: typography.font_size_lg 
                    }}
                    className="font-semibold"
                  >
                    Bauen mit Vertrauen
                  </h2>
                  
                  <p>
                    Dies ist ein Vorschautext, der zeigt, wie Ihre Website mit den neuen Design-Einstellungen aussehen wird.
                  </p>
                  
                  <div className="flex space-x-4">
                    <button
                      style={{
                        backgroundColor: theme.primary_color,
                        borderRadius: layout.button_border_radius,
                        color: theme.background_color
                      }}
                      className="px-6 py-3 font-medium"
                    >
                      Primärer Button
                    </button>
                    
                    <button
                      style={{
                        backgroundColor: theme.secondary_color,
                        borderRadius: layout.button_border_radius,
                        color: theme.background_color
                      }}
                      className="px-6 py-3 font-medium"
                    >
                      Sekundärer Button
                    </button>
                  </div>
                  
                  <div
                    style={{
                      borderRadius: layout.card_border_radius,
                      borderColor: theme.border_color
                    }}
                    className="border p-6"
                  >
                    <h3 
                      style={{ 
                        fontFamily: typography.heading_font,
                        color: theme.accent_color 
                      }}
                      className="font-semibold mb-2"
                    >
                      Beispiel Karte
                    </h3>
                    <p>
                      Diese Karte zeigt, wie Inhalts-Container mit Ihren neuen Einstellungen aussehen.
                    </p>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </TabsContent>
      </Tabs>
    </div>
  );
};

// Media Manager Component  
const MediaManager = () => {
  const [mediaFiles, setMediaFiles] = useState([]);
  const [isUploading, setIsUploading] = useState(false);
  const [selectedFiles, setSelectedFiles] = useState([]);

  useEffect(() => {
    fetchMediaFiles();
  }, []);

  const fetchMediaFiles = async () => {
    try {
      const response = await axios.get(`${API}/media`);
      setMediaFiles(response.data);
    } catch (error) {
      console.error('Error fetching media files:', error);
    }
  };

  const handleFileUpload = async (files) => {
    setIsUploading(true);
    try {
      for (const file of files) {
        const formData = new FormData();
        formData.append('file', file);
        await axios.post(`${API}/upload`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      }
      fetchMediaFiles();
    } catch (error) {
      alert('Fehler beim Hochladen der Dateien');
    }
    setIsUploading(false);
  };

  return (
    <div className="space-y-8">
      <div className="flex justify-between items-center">
        <h2 className="text-4xl font-bold text-gray-900">Medien Verwaltung</h2>
        <div className="flex space-x-3">
          <Button onClick={fetchMediaFiles} variant="outline">
            <Refresh className="w-4 h-4 mr-2" />
            Aktualisieren
          </Button>
          <label htmlFor="file-upload">
            <Button asChild className="bg-green-600 hover:bg-green-700 cursor-pointer">
              <span>
                <Upload className="w-4 h-4 mr-2" />
                Dateien hochladen
              </span>
            </Button>
          </label>
          <input
            id="file-upload"
            type="file"
            multiple
            className="hidden"
            onChange={(e) => e.target.files && handleFileUpload(Array.from(e.target.files))}
          />
        </div>
      </div>

      {isUploading && (
        <Card className="bg-blue-50 border-blue-200">
          <CardContent className="p-6">
            <div className="flex items-center">
              <div className="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mr-3"></div>
              <span>Dateien werden hochgeladen...</span>
            </div>
          </CardContent>
        </Card>
      )}

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {mediaFiles.map((file) => (
          <Card key={file.id} className="hover:shadow-lg transition-shadow">
            <CardContent className="p-4">
              <div className="aspect-square bg-gray-100 rounded-lg mb-3 flex items-center justify-center">
                {file.file_type === 'image' ? (
                  <img 
                    src={`/uploads/${file.filename}`}
                    alt={file.original_name}
                    className="w-full h-full object-cover rounded-lg"
                  />
                ) : (
                  <FileText className="w-12 h-12 text-gray-400" />
                )}
              </div>
              <h4 className="font-medium text-sm truncate">{file.original_name}</h4>
              <p className="text-xs text-gray-500">{(file.file_size / 1024).toFixed(1)} KB</p>
              <div className="flex space-x-2 mt-3">
                <Button size="sm" variant="outline" className="flex-1">
                  <Copy className="w-3 h-3 mr-1" />
                  URL
                </Button>
                <Button size="sm" variant="outline" className="flex-1">
                  <Download className="w-3 h-3 mr-1" />
                </Button>
                <Button size="sm" variant="destructive">
                  <Trash2 className="w-3 h-3" />
                </Button>
              </div>
            </CardContent>
          </Card>
        ))}
      </div>

      {mediaFiles.length === 0 && (
        <Card>
          <CardContent className="text-center py-12">
            <Image className="w-16 h-16 mx-auto text-gray-400 mb-4" />
            <h3 className="text-lg font-medium text-gray-900 mb-2">Keine Medien vorhanden</h3>
            <p className="text-gray-600 mb-4">
              Laden Sie Bilder und andere Dateien hoch, um sie auf Ihrer Website zu verwenden.
            </p>
            <label htmlFor="file-upload-empty">
              <Button asChild className="cursor-pointer">
                <span>
                  <Upload className="w-4 h-4 mr-2" />
                  Erste Datei hochladen
                </span>
              </Button>
            </label>
            <input
              id="file-upload-empty"
              type="file"
              multiple
              className="hidden"
              onChange={(e) => e.target.files && handleFileUpload(Array.from(e.target.files))}
            />
          </CardContent>
        </Card>
      )}
    </div>
  );
};

// Main Admin Panel Component with all existing features + new ones
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

  const navigationItems = [
    { value: 'dashboard', label: 'Dashboard', icon: BarChart3, color: 'text-blue-600' },
    { value: 'pages', label: 'Seiten Editor', icon: Edit2, color: 'text-green-600' },
    { value: 'design', label: 'Design System', icon: Palette, color: 'text-purple-600' },
    { value: 'media', label: 'Medien', icon: Image, color: 'text-orange-600' },
    { value: 'projects', label: 'Projekte', icon: Building, color: 'text-cyan-600' },
    { value: 'career', label: 'Karriere', icon: Users, color: 'text-indigo-600' },
    { value: 'messages', label: 'Nachrichten', icon: MessageSquare, color: 'text-pink-600' },
    { value: 'contact', label: 'Kontakt', icon: Phone, color: 'text-teal-600' },
    { value: 'support', label: 'Support', icon: MessageSquare, color: 'text-red-600' },
    { value: 'settings', label: 'Einstellungen', icon: Settings, color: 'text-gray-600' }
  ];

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="border-b bg-white shadow-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center space-x-4">
              <div className="font-bold text-2xl bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent">
                Hohmann Bau
              </div>
              <Badge variant="outline" className="bg-green-50 text-green-700 border-green-200">
                Universal Admin
              </Badge>
            </div>
            <Button onClick={handleLogout} variant="outline" className="hover:bg-red-50 hover:text-red-700 hover:border-red-200">
              <LogOut className="w-4 h-4 mr-2" />
              Abmelden
            </Button>
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div className="flex gap-8">
          {/* Enhanced Sidebar Navigation */}
          <div className="w-72 flex-shrink-0">
            <Card className="shadow-lg">
              <CardHeader className="bg-gradient-to-r from-green-50 to-blue-50">
                <CardTitle className="text-lg flex items-center">
                  <Menu className="w-5 h-5 mr-2" />
                  Navigation
                </CardTitle>
              </CardHeader>
              <CardContent className="p-0">
                <div className="space-y-1 p-3">
                  {navigationItems.map((tab) => (
                    <button
                      key={tab.value}
                      onClick={() => setActiveTab(tab.value)}
                      className={`w-full text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center space-x-3 group ${
                        activeTab === tab.value 
                          ? 'bg-green-100 text-green-800 shadow-md' 
                          : 'hover:bg-gray-100 text-gray-700'
                      }`}
                    >
                      <tab.icon className={`w-5 h-5 ${activeTab === tab.value ? 'text-green-600' : tab.color} transition-colors`} />
                      <span className="font-medium">{tab.label}</span>
                      {activeTab === tab.value && (
                        <div className="ml-auto w-2 h-2 bg-green-500 rounded-full"></div>
                      )}
                    </button>
                  ))}
                </div>
              </CardContent>
            </Card>

            {/* Quick Stats Card */}
            <Card className="mt-6 shadow-lg bg-gradient-to-br from-blue-50 to-indigo-50">
              <CardHeader>
                <CardTitle className="text-base text-blue-900">System Status</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-3">
                  <div className="flex justify-between items-center">
                    <span className="text-sm text-blue-700">React Version</span>
                    <Badge variant="outline" className="text-blue-700 border-blue-200">Aktiv</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm text-blue-700">API Status</span>
                    <Badge variant="outline" className="text-green-700 border-green-200">Online</Badge>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm text-blue-700">Database</span>
                    <Badge variant="outline" className="text-green-700 border-green-200">Verbunden</Badge>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Main Content Area */}
          <div className="flex-1 min-w-0">
            <div className="bg-white rounded-lg shadow-lg p-8">
              {activeTab === 'dashboard' && <Dashboard />}
              {activeTab === 'pages' && <UniversalPageEditor />}
              {activeTab === 'design' && <DesignSystemManager />}
              {activeTab === 'media' && <MediaManager />}
              {/* Include all existing components */}
              {activeTab === 'projects' && <ProjectsManagement />}
              {activeTab === 'career' && <CareerManagement />}
              {activeTab === 'messages' && <ContactMessagesManagement />}
              {activeTab === 'contact' && <ContactManagement />}
              {activeTab === 'support' && <SupportManagement />}
              {activeTab === 'settings' && (
                <div className="text-center py-12">
                  <Settings className="w-16 h-16 mx-auto text-gray-400 mb-4" />
                  <h3 className="text-lg font-medium text-gray-900">Erweiterte Einstellungen</h3>
                  <p className="text-gray-600 mt-2">Weitere Konfigurationsoptionen werden hier verfügbar sein.</p>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

// All existing components remain the same...
// CareerManagement, SupportManagement, ContentManagement, ContactManagement, 
// ProjectsManagement, ContactMessagesManagement components stay exactly as they were

// Include all the existing components from the original AdminPanel.js
const CareerManagement = () => {
  const [jobPostings, setJobPostings] = useState([]);
  const [applications, setApplications] = useState([]);
  const [isCreateJobOpen, setIsCreateJobOpen] = useState(false);
  const [editingJob, setEditingJob] = useState(null);
  const [newJob, setNewJob] = useState({
    title: '',
    description: '',
    requirements: '',
    location: '',
    employment_type: 'Vollzeit'
  });

  useEffect(() => {
    fetchJobPostings();
    fetchApplications();
  }, []);

  const fetchJobPostings = async () => {
    try {
      const response = await axios.get(`${API}/admin/jobs`);
      setJobPostings(response.data);
    } catch (error) {
      console.error('Error fetching job postings:', error);
    }
  };

  const fetchApplications = async () => {
    try {
      const response = await axios.get(`${API}/admin/applications`);
      setApplications(response.data);
    } catch (error) {
      console.error('Error fetching applications:', error);
    }
  };

  const handleCreateJob = async (e) => {
    e.preventDefault();
    try {
      await axios.post(`${API}/jobs`, newJob);
      setNewJob({ title: '', description: '', requirements: '', location: '', employment_type: 'Vollzeit' });
      setIsCreateJobOpen(false);
      fetchJobPostings();
    } catch (error) {
      alert('Fehler beim Erstellen der Stellenausschreibung.');
    }
  };

  const handleUpdateJob = async (e) => {
    e.preventDefault();
    try {
      await axios.put(`${API}/jobs/${editingJob.id}`, editingJob);
      setEditingJob(null);
      fetchJobPostings();
    } catch (error) {
      alert('Fehler beim Aktualisieren der Stellenausschreibung.');
    }
  };

  const handleDeleteJob = async (jobId) => {
    try {
      await axios.delete(`${API}/jobs/${jobId}`);
      fetchJobPostings();
    } catch (error) {
      alert('Fehler beim Löschen der Stellenausschreibung.');
    }
  };

  const toggleJobActive = async (jobId) => {
    try {
      await axios.put(`${API}/jobs/${jobId}/toggle`);
      fetchJobPostings();
    } catch (error) {
      alert('Fehler beim Ändern des Status.');
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h2 className="text-3xl font-bold text-gray-900">Karriere & Bewerbungen</h2>
        <Dialog open={isCreateJobOpen} onOpenChange={setIsCreateJobOpen}>
          <DialogTrigger asChild>
            <Button className="bg-green-700 hover:bg-green-800">
              <Plus className="w-4 h-4 mr-2" />
              Stellenausschreibung erstellen
            </Button>
          </DialogTrigger>
          <DialogContent className="max-w-3xl">
            <DialogHeader>
              <DialogTitle>Neue Stellenausschreibung</DialogTitle>
            </DialogHeader>
            <form onSubmit={handleCreateJob} className="space-y-4">
              <div>
                <Label htmlFor="title">Stellentitel *</Label>
                <Input
                  id="title"
                  value={newJob.title}
                  onChange={(e) => setNewJob({...newJob, title: e.target.value})}
                  placeholder="z.B. Bauleiter (m/w/d)"
                  required
                />
              </div>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <Label htmlFor="location">Standort *</Label>
                  <Input
                    id="location"
                    value={newJob.location}
                    onChange={(e) => setNewJob({...newJob, location: e.target.value})}
                    placeholder="z.B. Musterstadt"
                    required
                  />
                </div>
                <div>
                  <Label htmlFor="employment_type">Arbeitszeit *</Label>
                  <Select onValueChange={(value) => setNewJob({...newJob, employment_type: value})} defaultValue="Vollzeit">
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Vollzeit">Vollzeit</SelectItem>
                      <SelectItem value="Teilzeit">Teilzeit</SelectItem>
                      <SelectItem value="Minijob">Minijob</SelectItem>
                      <SelectItem value="Praktikum">Praktikum</SelectItem>
                      <SelectItem value="Ausbildung">Ausbildung</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
              <div>
                <Label htmlFor="description">Stellenbeschreibung *</Label>
                <Textarea
                  id="description"
                  value={newJob.description}
                  onChange={(e) => setNewJob({...newJob, description: e.target.value})}
                  rows={6}
                  placeholder="Beschreiben Sie die Aufgaben und Verantwortlichkeiten..."
                  required
                />
              </div>
              <div>
                <Label htmlFor="requirements">Anforderungen *</Label>
                <Textarea
                  id="requirements"
                  value={newJob.requirements}
                  onChange={(e) => setNewJob({...newJob, requirements: e.target.value})}
                  rows={6}
                  placeholder="• Qualifikation 1&#10;• Qualifikation 2&#10;• ..."
                  required
                />
              </div>
              <div className="flex justify-end space-x-2">
                <Button type="button" variant="outline" onClick={() => setIsCreateJobOpen(false)}>
                  Abbrechen
                </Button>
                <Button type="submit" className="bg-green-700 hover:bg-green-800">
                  Stellenausschreibung erstellen
                </Button>
              </div>
            </form>
          </DialogContent>
        </Dialog>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Job Postings Management */}
        <Card>
          <CardHeader>
            <CardTitle>Stellenausschreibungen ({jobPostings.length})</CardTitle>
            <CardDescription>Aktuelle und inaktive Stellenausschreibungen verwalten</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4 max-h-96 overflow-y-auto">
              {jobPostings.map((job) => (
                <Card key={job.id} className={`border-l-4 ${job.is_active ? 'border-l-green-500' : 'border-l-gray-400'}`}>
                  <CardContent className="p-4">
                    <div className="flex justify-between items-start mb-2">
                      <div>
                        <h4 className="font-semibold">{job.title}</h4>
                        <p className="text-sm text-gray-600">{job.location} • {job.employment_type}</p>
                      </div>
                      <Badge variant={job.is_active ? 'default' : 'secondary'}>
                        {job.is_active ? 'Aktiv' : 'Inaktiv'}
                      </Badge>
                    </div>
                    <p className="text-sm line-clamp-2 mb-3">{job.description}</p>
                    <div className="flex flex-wrap gap-2">
                      <Dialog>
                        <DialogTrigger asChild>
                          <Button size="sm" variant="outline" onClick={() => setEditingJob({...job})}>
                            <Edit2 className="w-4 h-4 mr-1" />
                            Bearbeiten
                          </Button>
                        </DialogTrigger>
                        <DialogContent className="max-w-3xl">
                          <DialogHeader>
                            <DialogTitle>Stellenausschreibung bearbeiten</DialogTitle>
                          </DialogHeader>
                          {editingJob && (
                            <form onSubmit={handleUpdateJob} className="space-y-4">
                              <div>
                                <Label htmlFor="edit_title">Stellentitel</Label>
                                <Input
                                  id="edit_title"
                                  value={editingJob.title}
                                  onChange={(e) => setEditingJob({...editingJob, title: e.target.value})}
                                  required
                                />
                              </div>
                              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                  <Label htmlFor="edit_location">Standort</Label>
                                  <Input
                                    id="edit_location"
                                    value={editingJob.location}
                                    onChange={(e) => setEditingJob({...editingJob, location: e.target.value})}
                                    required
                                  />
                                </div>
                                <div>
                                  <Label htmlFor="edit_employment_type">Arbeitszeit</Label>
                                  <Select 
                                    onValueChange={(value) => setEditingJob({...editingJob, employment_type: value})} 
                                    defaultValue={editingJob.employment_type}
                                  >
                                    <SelectTrigger>
                                      <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                      <SelectItem value="Vollzeit">Vollzeit</SelectItem>
                                      <SelectItem value="Teilzeit">Teilzeit</SelectItem>
                                      <SelectItem value="Minijob">Minijob</SelectItem>
                                      <SelectItem value="Praktikum">Praktikum</SelectItem>
                                      <SelectItem value="Ausbildung">Ausbildung</SelectItem>
                                    </SelectContent>
                                  </Select>
                                </div>
                              </div>
                              <div>
                                <Label htmlFor="edit_description">Stellenbeschreibung</Label>
                                <Textarea
                                  id="edit_description"
                                  value={editingJob.description}
                                  onChange={(e) => setEditingJob({...editingJob, description: e.target.value})}
                                  rows={6}
                                  required
                                />
                              </div>
                              <div>
                                <Label htmlFor="edit_requirements">Anforderungen</Label>
                                <Textarea
                                  id="edit_requirements"
                                  value={editingJob.requirements}
                                  onChange={(e) => setEditingJob({...editingJob, requirements: e.target.value})}
                                  rows={6}
                                  required
                                />
                              </div>
                              <div className="flex justify-end space-x-2">
                                <Button type="button" variant="outline" onClick={() => setEditingJob(null)}>
                                  Abbrechen
                                </Button>
                                <Button type="submit" className="bg-green-700 hover:bg-green-800">
                                  Änderungen speichern
                                </Button>
                              </div>
                            </form>
                          )}
                        </DialogContent>
                      </Dialog>
                      <Button 
                        size="sm" 
                        variant={job.is_active ? "secondary" : "default"}
                        onClick={() => toggleJobActive(job.id)}
                      >
                        {job.is_active ? 'Deaktivieren' : 'Aktivieren'}
                      </Button>
                      <AlertDialog>
                        <AlertDialogTrigger asChild>
                          <Button size="sm" variant="destructive">
                            <Trash2 className="w-4 h-4" />
                          </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                          <AlertDialogHeader>
                            <AlertDialogTitle>Stellenausschreibung löschen?</AlertDialogTitle>
                            <AlertDialogDescription>
                              Diese Aktion kann nicht rückgängig gemacht werden. Alle zugehörigen Bewerbungen bleiben erhalten.
                            </AlertDialogDescription>
                          </AlertDialogHeader>
                          <AlertDialogFooter>
                            <AlertDialogCancel>Abbrechen</AlertDialogCancel>
                            <AlertDialogAction onClick={() => handleDeleteJob(job.id)}>
                              Löschen
                            </AlertDialogAction>
                          </AlertDialogFooter>
                        </AlertDialogContent>
                      </AlertDialog>
                    </div>
                  </CardContent>
                </Card>
              ))}
              {jobPostings.length === 0 && (
                <p className="text-gray-500 text-center py-4">Keine Stellenausschreibungen vorhanden</p>
              )}
            </div>
          </CardContent>
        </Card>

        {/* Applications Management */}
        <Card>
          <CardHeader>
            <CardTitle>Bewerbungen ({applications.length})</CardTitle>
            <CardDescription>Eingegangene Bewerbungen verwalten</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4 max-h-96 overflow-y-auto">
              {applications.map((application) => (
                <Card key={application.id} className="border-l-4 border-l-blue-500">
                  <CardContent className="p-4">
                    <div className="flex justify-between items-start mb-2">
                      <div>
                        <h4 className="font-semibold">{application.name}</h4>
                        <p className="text-sm text-gray-600">{application.email}</p>
                        {application.phone && (
                          <p className="text-sm text-gray-600">{application.phone}</p>
                        )}
                      </div>
                      <Badge variant={
                        application.status === 'pending' ? 'secondary' :
                        application.status === 'reviewed' ? 'default' :
                        application.status === 'accepted' ? 'default' : 'destructive'
                      }>
                        {application.status === 'pending' ? 'Neu' :
                         application.status === 'reviewed' ? 'Geprüft' :
                         application.status === 'accepted' ? 'Angenommen' : 'Abgelehnt'}
                      </Badge>
                    </div>
                    <p className="text-sm text-gray-600 mb-2">
                      Stelle: {jobPostings.find(job => job.id === application.job_id)?.title || 'Unbekannt'}
                    </p>
                    <p className="text-sm line-clamp-3 mb-3">{application.cover_letter}</p>
                    <div className="flex flex-wrap gap-2">
                      <Dialog>
                        <DialogTrigger asChild>
                          <Button size="sm" variant="outline">
                            <Eye className="w-4 h-4 mr-1" />
                            Details
                          </Button>
                        </DialogTrigger>
                        <DialogContent className="max-w-2xl">
                          <DialogHeader>
                            <DialogTitle>Bewerbung von {application.name}</DialogTitle>
                          </DialogHeader>
                          <div className="space-y-4">
                            <div className="grid grid-cols-2 gap-4">
                              <div>
                                <Label>Name:</Label>
                                <p>{application.name}</p>
                              </div>
                              <div>
                                <Label>E-Mail:</Label>
                                <p>{application.email}</p>
                              </div>
                              <div>
                                <Label>Telefon:</Label>
                                <p>{application.phone || 'Nicht angegeben'}</p>
                              </div>
                              <div>
                                <Label>Status:</Label>
                                <Badge>{application.status}</Badge>
                              </div>
                              <div>
                                <Label>Bewerbungsdatum:</Label>
                                <p>{new Date(application.created_at).toLocaleDateString('de-DE')}</p>
                              </div>
                              <div>
                                <Label>Lebenslauf:</Label>
                                <p>{application.cv_filename || 'Nicht hochgeladen'}</p>
                              </div>
                            </div>
                            <div>
                              <Label>Anschreiben:</Label>
                              <div className="mt-2 p-3 bg-gray-50 rounded-lg">
                                <p className="whitespace-pre-wrap">{application.cover_letter}</p>
                              </div>
                            </div>
                          </div>
                        </DialogContent>
                      </Dialog>
                      {application.cv_filename && (
                        <Button size="sm" variant="outline">
                          <FileText className="w-4 h-4 mr-1" />
                          CV herunterladen
                        </Button>
                      )}
                    </div>
                  </CardContent>
                </Card>
              ))}
              {applications.length === 0 && (
                <p className="text-gray-500 text-center py-4">Keine Bewerbungen vorhanden</p>
              )}
            </div>
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

export default AdminPanel;