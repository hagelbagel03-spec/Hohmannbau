import { useState, useEffect } from "react";
import "./App.css";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import axios from "axios";
import { Button } from "./components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./components/ui/card";
import { Input } from "./components/ui/input";
import { Textarea } from "./components/ui/textarea";
import { Badge } from "./components/ui/badge";
import { Separator } from "./components/ui/separator";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "./components/ui/dialog";
import { Building, Hammer, Users, MapPin, Phone, Mail, Facebook, Instagram, Linkedin, Briefcase, Calculator } from "lucide-react";
import AdminPanel from "./components/AdminPanel";
import CareerPage from "./components/CareerPage";
import QuotePage from "./components/QuotePage";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

// Navigation Component
const Navigation = ({ activeSection, setActiveSection }) => {
  const navItems = [
    { id: 'home', label: 'Home' },
    { id: 'services', label: 'Leistungen' },
    { id: 'projects', label: 'Projekte' },
    { id: 'team', label: 'Team' },
    { id: 'contact', label: 'Kontakt' }
  ];

  return (
    <nav className="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-md z-50 border-b border-gray-200">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          <div className="font-bold text-2xl text-green-800">Hohmann Bau</div>
          <div className="hidden md:flex items-center space-x-8">
            {navItems.map((item) => (
              <button
                key={item.id}
                onClick={() => setActiveSection(item.id)}
                className={`text-sm font-medium transition-colors hover:text-green-700 ${
                  activeSection === item.id ? 'text-green-700 border-b-2 border-green-700' : 'text-gray-600'
                }`}
              >
                {item.label}
              </button>
            ))}
            <a 
              href="/karriere" 
              className="text-sm font-medium text-gray-600 hover:text-green-700 transition-colors flex items-center"
            >
              <Briefcase className="w-4 h-4 mr-1" />
              Karriere
            </a>
            <a 
              href="/angebot" 
              className="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center"
            >
              <Calculator className="w-4 h-4 mr-1" />
              Angebot anfordern
            </a>
          </div>
        </div>
      </div>
    </nav>
  );
};

// Hero Section
const HeroSection = () => {
  return (
    <section id="home" className="relative min-h-screen flex items-center justify-center">
      <div 
        className="absolute inset-0 bg-cover bg-center bg-no-repeat"
        style={{
          backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1599995903128-531fc7fb694b?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwyfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85')`
        }}
      />
      <div className="relative z-10 text-center text-white max-w-4xl mx-auto px-4">
        <h1 className="text-5xl md:text-7xl font-bold mb-6 leading-tight">
          Bauen mit Vertrauen
        </h1>
        <p className="text-xl md:text-2xl mb-8 text-gray-200">
          Ihr zuverlässiger Partner für Hochbau, Tiefbau und Sanierungen
        </p>
        <Button 
          className="bg-green-700 hover:bg-green-800 text-white px-8 py-3 text-lg font-medium rounded-lg transition-all duration-300 transform hover:scale-105 mr-4"
          onClick={() => document.getElementById('contact').scrollIntoView({ behavior: 'smooth' })}
        >
          Kontakt aufnehmen
        </Button>
        <a href="/angebot">
          <Button 
            variant="outline"
            className="border-white text-white hover:bg-white hover:text-green-800 px-8 py-3 text-lg font-medium rounded-lg transition-all duration-300"
          >
            <Calculator className="w-5 h-5 mr-2" />
            Angebot anfordern
          </Button>
        </a>
      </div>
    </section>
  );
};

// Services Section
const ServicesSection = () => {
  const services = [
    {
      icon: <Building className="w-12 h-12 text-green-700" />,
      title: "Hochbau",
      description: "Vom Einfamilienhaus bis zum Geschäftskomplex - wir realisieren Ihre Bauprojekte mit höchster Qualität."
    },
    {
      icon: <Hammer className="w-12 h-12 text-green-700" />,
      title: "Tiefbau",
      description: "Fundamente, Kanalisationen und Infrastrukturprojekte - die solide Basis für jedes Bauvorhaben."
    },
    {
      icon: <Building className="w-12 h-12 text-green-700" />,
      title: "Sanierung",
      description: "Modernisierung und Renovierung bestehender Gebäude mit nachhaltigen und energieeffizienten Lösungen."
    },
    {
      icon: <Users className="w-12 h-12 text-green-700" />,
      title: "Projektsteuerung",
      description: "Professionelle Begleitung Ihres Bauprojekts von der Planung bis zur schlüsselfertigen Übergabe."
    }
  ];

  return (
    <section id="services" className="py-20 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-16">
          <h2 className="text-4xl font-bold text-gray-900 mb-4">Unsere Leistungen</h2>
          <p className="text-xl text-gray-600">Umfassende Baulösungen aus einer Hand</p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {services.map((service, index) => (
            <Card key={index} className="text-center hover:shadow-lg transition-shadow duration-300">
              <CardHeader>
                <div className="flex justify-center mb-4">
                  {service.icon}
                </div>
                <CardTitle className="text-xl font-bold text-gray-900">{service.title}</CardTitle>
              </CardHeader>
              <CardContent>
                <CardDescription className="text-gray-600">
                  {service.description}
                </CardDescription>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
};

// Projects Section
const ProjectsSection = () => {
  const [selectedProject, setSelectedProject] = useState(null);

  const projects = [
    {
      id: 1,
      title: "Wohnkomplex Musterstadt",
      category: "Wohnbau",
      image: "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDQ2NDN8MHwxfHNlYXJjaHwxfHxidWlsZGluZ3xlbnwwfHx8fDE3NTgzNzgxMjV8MA&ixlib=rb-4.1.0&q=85",
      description: "Moderner Wohnkomplex mit 120 Einheiten, energieeffizient und nachhaltig gebaut."
    },
    {
      id: 2,
      title: "Bürogebäude Zentrum",
      category: "Gewerbebau",
      image: "https://images.unsplash.com/photo-1488972685288-c3fd157d7c7a?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NTY2NzF8MHwxfHNlYXJjaHwxfHxhcmNoaXRlY3R1cmV8ZW58MHx8fHwxNzU4Mzc4MTMwfDA&ixlib=rb-4.1.0&q=85",
      description: "Hochmodernes Bürogebäude mit innovativer Architektur und nachhaltiger Gebäudetechnik."
    },
    {
      id: 3,
      title: "Industriehallen Komplex",
      category: "Infrastruktur",
      image: "https://images.unsplash.com/photo-1534237710431-e2fc698436d0?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDQ2NDN8MHwxfHNlYXJjaHwzfHxidWlsZGluZ3xlbnwwfHx8fDE3NTgzNzgxMjV8MA&ixlib=rb-4.1.0&q=85",
      description: "Großflächige Industriehallen für Logistik und Produktion mit modernster Ausstattung."
    },
    {
      id: 4,
      title: "Altbausanierung Historisch",
      category: "Sanierung",
      image: "https://images.pexels.com/photos/302769/pexels-photo-302769.jpeg",
      description: "Behutsame Sanierung eines denkmalgeschützten Gebäudes unter Erhaltung des historischen Charakters."
    }
  ];

  return (
    <section id="projects" className="py-20 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-16">
          <h2 className="text-4xl font-bold text-gray-900 mb-4">Unsere Projekte</h2>
          <p className="text-xl text-gray-600">Referenzen aus verschiedenen Bereichen</p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {projects.map((project) => (
            <Dialog key={project.id}>
              <DialogTrigger asChild>
                <Card className="cursor-pointer hover:shadow-lg transition-shadow duration-300">
                  <div className="relative h-48 overflow-hidden">
                    <img 
                      src={project.image} 
                      alt={project.title}
                      className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                    />
                    <Badge className="absolute top-4 left-4 bg-green-700">
                      {project.category}
                    </Badge>
                  </div>
                  <CardHeader>
                    <CardTitle className="text-lg">{project.title}</CardTitle>
                  </CardHeader>
                </Card>
              </DialogTrigger>
              <DialogContent className="max-w-2xl">
                <DialogHeader>
                  <DialogTitle>{project.title}</DialogTitle>
                  <DialogDescription>
                    <Badge className="mb-4">{project.category}</Badge>
                  </DialogDescription>
                </DialogHeader>
                <img 
                  src={project.image} 
                  alt={project.title}
                  className="w-full h-64 object-cover rounded-lg mb-4"
                />
                <p className="text-gray-700">{project.description}</p>
              </DialogContent>
            </Dialog>
          ))}
        </div>
      </div>
    </section>
  );
};

// Team Section
const TeamSection = () => {
  const team = [
    {
      name: "Klaus Hohmann",
      role: "Geschäftsführer",
      image: "https://images.unsplash.com/photo-1694521787162-5373b598945c?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwxfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85"
    },
    {
      name: "Maria Schneider",
      role: "Projektleiterin",
      image: "https://images.unsplash.com/photo-1541888946425-d81bb19240f5?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwzfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85"
    },
    {
      name: "Thomas Weber",
      role: "Bauleiter",
      image: "https://images.unsplash.com/photo-1535732759880-bbd5c7265e3f?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHw0fHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85"
    },
    {
      name: "Sandra Mueller",
      role: "Architektin",
      image: "https://images.unsplash.com/photo-1694521787162-5373b598945c?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwxfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85"
    }
  ];

  return (
    <section id="team" className="py-20 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-16">
          <h2 className="text-4xl font-bold text-gray-900 mb-4">Unser Team</h2>
          <p className="text-xl text-gray-600">Erfahrene Fachkräfte für Ihr Projekt</p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {team.map((member, index) => (
            <Card key={index} className="text-center hover:shadow-lg transition-shadow duration-300">
              <CardHeader>
                <div className="w-32 h-32 mx-auto mb-4 overflow-hidden rounded-full">
                  <img 
                    src={member.image} 
                    alt={member.name}
                    className="w-full h-full object-cover"
                  />
                </div>
                <CardTitle className="text-xl">{member.name}</CardTitle>
                <CardDescription className="text-green-700 font-medium">
                  {member.role}
                </CardDescription>
              </CardHeader>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
};

// Contact Section
const ContactSection = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    message: ''
  });

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await axios.post(`${API}/contact`, formData);
      alert('Nachricht erfolgreich gesendet!');
      setFormData({ name: '', email: '', message: '' });
    } catch (error) {
      alert('Fehler beim Senden der Nachricht.');
    }
  };

  return (
    <section id="contact" className="py-20 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-16">
          <h2 className="text-4xl font-bold text-gray-900 mb-4">Kontakt</h2>
          <p className="text-xl text-gray-600">Lassen Sie uns über Ihr Projekt sprechen</p>
        </div>
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
          <div>
            <Card className="h-full">
              <CardHeader>
                <CardTitle>Kontaktformular</CardTitle>
              </CardHeader>
              <CardContent>
                <form onSubmit={handleSubmit} className="space-y-6">
                  <div>
                    <Input
                      type="text"
                      placeholder="Ihr Name"
                      value={formData.name}
                      onChange={(e) => setFormData({...formData, name: e.target.value})}
                      required
                    />
                  </div>
                  <div>
                    <Input
                      type="email"
                      placeholder="Ihre E-Mail"
                      value={formData.email}
                      onChange={(e) => setFormData({...formData, email: e.target.value})}
                      required
                    />
                  </div>
                  <div>
                    <Textarea
                      placeholder="Ihre Nachricht"
                      value={formData.message}
                      onChange={(e) => setFormData({...formData, message: e.target.value})}
                      rows={5}
                      required
                    />
                  </div>
                  <Button 
                    type="submit" 
                    className="w-full bg-green-700 hover:bg-green-800"
                  >
                    Nachricht senden
                  </Button>
                </form>
              </CardContent>
            </Card>
          </div>
          <div>
            <Card className="h-full">
              <CardHeader>
                <CardTitle>Kontaktinformationen</CardTitle>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="flex items-center space-x-3">
                  <MapPin className="w-5 h-5 text-green-700" />
                  <div>
                    <p className="font-medium">Adresse</p>
                    <p className="text-gray-600">Bahnhofstraße 123, 12345 Musterstadt</p>
                  </div>
                </div>
                <div className="flex items-center space-x-3">
                  <Phone className="w-5 h-5 text-green-700" />
                  <div>
                    <p className="font-medium">Telefon</p>
                    <p className="text-gray-600">+49 123 456 789</p>
                  </div>
                </div>
                <div className="flex items-center space-x-3">
                  <Mail className="w-5 h-5 text-green-700" />
                  <div>
                    <p className="font-medium">E-Mail</p>
                    <p className="text-gray-600">info@hohmann-bau.de</p>
                  </div>
                </div>
                <Separator />
                <div>
                  <p className="font-medium mb-4">Standortkarte</p>
                  <div className="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                    <p className="text-gray-500">Google Maps Integration</p>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </section>
  );
};

// Footer
const Footer = () => {
  return (
    <footer className="bg-gray-900 text-white py-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div>
            <h3 className="text-2xl font-bold mb-4">Hohmann Bau</h3>
            <p className="text-gray-400">Ihr zuverlässiger Partner für alle Bauvorhaben</p>
          </div>
          <div>
            <h4 className="text-lg font-semibold mb-4">Kontakt</h4>
            <div className="space-y-2 text-gray-400">
              <p>Bahnhofstraße 123</p>
              <p>12345 Musterstadt</p>
              <p>+49 123 456 789</p>
            </div>
          </div>
          <div>
            <h4 className="text-lg font-semibold mb-4">Social Media</h4>
            <div className="flex space-x-4">
              <Facebook className="w-6 h-6 text-gray-400 hover:text-white cursor-pointer" />
              <Instagram className="w-6 h-6 text-gray-400 hover:text-white cursor-pointer" />
              <Linkedin className="w-6 h-6 text-gray-400 hover:text-white cursor-pointer" />
            </div>
          </div>
        </div>
        <Separator className="my-8 bg-gray-700" />
        <div className="text-center text-gray-400">
          <p>&copy; 2024 Hohmann Bau. Alle Rechte vorbehalten.</p>
        </div>
      </div>
    </footer>
  );
};

// Main App Component
const Home = () => {
  const [activeSection, setActiveSection] = useState('home');

  useEffect(() => {
    const handleScroll = () => {
      const sections = ['home', 'services', 'projects', 'team', 'contact'];
      const currentSection = sections.find(section => {
        const element = document.getElementById(section);
        if (element) {
          const rect = element.getBoundingClientRect();
          return rect.top <= 100 && rect.bottom >= 100;
        }
        return false;
      });
      if (currentSection) {
        setActiveSection(currentSection);
      }
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <div className="min-h-screen">
      <Navigation activeSection={activeSection} setActiveSection={setActiveSection} />
      <HeroSection />
      <ServicesSection />
      <ProjectsSection />
      <TeamSection />
      <ContactSection />
      <Footer />
    </div>
  );
};

function App() {
  return (
    <div className="App">
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/admin" element={<AdminPanel />} />
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;