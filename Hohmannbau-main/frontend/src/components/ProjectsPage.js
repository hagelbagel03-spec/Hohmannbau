import { useState, useEffect } from "react";
import axios from "axios";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Button } from "./ui/button";
import { Badge } from "./ui/badge";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "./ui/dialog";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "./ui/select";
import { MapPin, Calendar, Building } from "lucide-react";
import { useNavigate } from "react-router-dom";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const ProjectsPage = () => {
  const [projects, setProjects] = useState([]);
  const [filteredProjects, setFilteredProjects] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState("all");
  const [pageContent, setPageContent] = useState({
    title: "Unsere Projekte",
    subtitle: "Referenzen aus verschiedenen Bereichen",
    description: "Entdecken Sie unsere erfolgreich abgeschlossenen Bauprojekte und lassen Sie sich von der Vielfalt und Qualität unserer Arbeit überzeugen."
  });
  const navigate = useNavigate();

  const categories = [
    { value: "all", label: "Alle Projekte" },
    { value: "Wohnbau", label: "Wohnbau" },
    { value: "Gewerbebau", label: "Gewerbebau" },
    { value: "Sanierung", label: "Sanierung" },
    { value: "Infrastruktur", label: "Infrastruktur" }
  ];

  useEffect(() => {
    fetchProjects();
    fetchPageContent();
  }, []);

  useEffect(() => {
    filterProjects();
  }, [projects, selectedCategory]);

  const fetchProjects = async () => {
    try {
      const response = await axios.get(`${API}/projects`);
      if (response.data && response.data.length > 0) {
        setProjects(response.data);
      } else {
        // Fallback projects with more details
        setProjects([
          {
            id: "1",
            title: "Wohnkomplex Musterstadt",
            category: "Wohnbau",
            image: "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDQ2NDN8MHwxfHNlYXJjaHwxfHxidWlsZGluZ3xlbnwwfHx8fDE3NTgzNzgxMjV8MA&ixlib=rb-4.1.0&q=85",
            description: "Moderner Wohnkomplex mit 120 Einheiten, energieeffizient und nachhaltig gebaut mit innovativer Architektur und hochwertiger Ausstattung.",
            location: "Musterstadt, Deutschland",
            year: "2023",
            area: "8.500 m²",
            duration: "18 Monate",
            client: "Musterstadt Wohnbau GmbH",
            features: ["120 Wohneinheiten", "Tiefgarage mit 80 Stellplätzen", "Gemeinschaftsgarten", "Energieeffizienzklasse A+"]
          },
          {
            id: "2",
            title: "Bürogebäude Zentrum",
            category: "Gewerbebau",
            image: "https://images.unsplash.com/photo-1488972685288-c3fd157d7c7a?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NTY2NzF8MHwxfHNlYXJjaHwxfHxhcmNoaXRlY3R1cmV8ZW58MHx8fHwxNzU4Mzc4MTMwfDA&ixlib=rb-4.1.0&q=85",
            description: "Hochmodernes Bürogebäude mit innovativer Architektur und nachhaltiger Gebäudetechnik im Herzen der Innenstadt.",
            location: "Zentrum, Deutschland", 
            year: "2022",
            area: "12.000 m²",
            duration: "24 Monate",
            client: "Zentrum Business Center AG",
            features: ["15 Stockwerke", "Moderne Klimaanlage", "Glasfassade", "Smart Building Technology"]
          },
          {
            id: "3",
            title: "Industriehallen Komplex",
            category: "Infrastruktur",
            image: "https://images.unsplash.com/photo-1534237710431-e2fc698436d0?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDQ2NDN8MHwxfHNlYXJjaHwzfHxidWlsZGluZ3xlbnwwfHx8fDE3NTgzNzgxMjV8MA&ixlib=rb-4.1.0&q=85",
            description: "Großflächige Industriehallen für Logistik und Produktion mit modernster Ausstattung und optimierten Arbeitsabläufen.",
            location: "Industriegebiet Nord",
            year: "2023",
            area: "25.000 m²", 
            duration: "15 Monate",
            client: "LogistikPro Deutschland GmbH",
            features: ["5 Produktionshallen", "Automatisierte Fördertechnik", "Bürotrakt", "LKW-Verladestationen"]
          },
          {
            id: "4",
            title: "Altbausanierung Historisch",
            category: "Sanierung",
            image: "https://images.pexels.com/photos/302769/pexels-photo-302769.jpeg",
            description: "Behutsame Sanierung eines denkmalgeschützten Gebäudes unter Erhaltung des historischen Charakters mit modernen Standards.",
            location: "Altstadt, Deutschland",
            year: "2022",
            area: "3.200 m²",
            duration: "20 Monate", 
            client: "Stadt Altstadt",
            features: ["Denkmalschutz", "Neue Haustechnik", "Barrierefreier Zugang", "Energetische Sanierung"]
          }
        ]);
      }
    } catch (error) {
      console.error('Error fetching projects:', error);
    }
  };

  const fetchPageContent = async () => {
    try {
      const response = await axios.get(`${API}/content/projects`);
      if (response.data) {
        setPageContent(response.data);
      }
    } catch (error) {
      console.log('Using default projects content');
    }
  };

  const filterProjects = () => {
    if (selectedCategory === "all") {
      setFilteredProjects(projects);
    } else {
      setFilteredProjects(projects.filter(project => project.category === selectedCategory));
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 pt-16">
      {/* Hero Section */}
      <section className="py-20 bg-gradient-to-r from-green-800 to-green-600 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl md:text-6xl font-bold mb-6">{pageContent.title}</h1>
          <p className="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
            {pageContent.subtitle}
          </p>
          <p className="text-lg opacity-90 max-w-4xl mx-auto">
            {pageContent.description}
          </p>
        </div>
      </section>

      {/* Filter Section */}
      <section className="py-8 bg-white border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col sm:flex-row justify-between items-center">
            <h2 className="text-2xl font-semibold text-gray-900 mb-4 sm:mb-0">
              {filteredProjects.length} Projekte
            </h2>
            <Select value={selectedCategory} onValueChange={setSelectedCategory}>
              <SelectTrigger className="w-full sm:w-64">
                <SelectValue placeholder="Kategorie wählen" />
              </SelectTrigger>
              <SelectContent>
                {categories.map((category) => (
                  <SelectItem key={category.value} value={category.value}>
                    {category.label}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        </div>
      </section>

      {/* Projects Grid */}
      <section className="py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {filteredProjects.map((project) => (
              <Dialog key={project.id}>
                <DialogTrigger asChild>
                  <Card className="cursor-pointer hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <div className="relative h-64 overflow-hidden">
                      <img 
                        src={project.image} 
                        alt={project.title}
                        className="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                      />
                      <Badge className="absolute top-4 left-4 bg-green-700">
                        {project.category}
                      </Badge>
                      <div className="absolute bottom-4 left-4 text-white">
                        <div className="flex items-center mb-1">
                          <MapPin className="w-4 h-4 mr-1" />
                          <span className="text-sm">{project.location}</span>
                        </div>
                        <div className="flex items-center">
                          <Calendar className="w-4 h-4 mr-1" />
                          <span className="text-sm">{project.year}</span>
                        </div>
                      </div>
                    </div>
                    <CardHeader>
                      <CardTitle className="text-xl">{project.title}</CardTitle>
                      <CardDescription className="line-clamp-2">
                        {project.description}
                      </CardDescription>
                    </CardHeader>
                  </Card>
                </DialogTrigger>
                <DialogContent className="max-w-4xl max-h-[90vh] overflow-y-auto">
                  <DialogHeader>
                    <DialogTitle className="text-2xl">{project.title}</DialogTitle>
                    <DialogDescription>
                      <Badge className="mb-4">{project.category}</Badge>
                    </DialogDescription>
                  </DialogHeader>
                  
                  <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                      <img 
                        src={project.image} 
                        alt={project.title}
                        className="w-full h-64 object-cover rounded-lg mb-4"
                      />
                      <div className="grid grid-cols-2 gap-4 text-sm">
                        <div>
                          <strong>Standort:</strong><br />
                          {project.location}
                        </div>
                        <div>
                          <strong>Jahr:</strong><br />
                          {project.year}
                        </div>
                        <div>
                          <strong>Fläche:</strong><br />
                          {project.area}
                        </div>
                        <div>
                          <strong>Bauzeit:</strong><br />
                          {project.duration}
                        </div>
                      </div>
                    </div>
                    
                    <div>
                      <h3 className="text-lg font-semibold mb-3">Projektbeschreibung</h3>
                      <p className="text-gray-700 mb-6">{project.description}</p>
                      
                      <h3 className="text-lg font-semibold mb-3">Auftraggeber</h3>
                      <p className="text-gray-700 mb-6">{project.client}</p>
                      
                      <h3 className="text-lg font-semibold mb-3">Besonderheiten</h3>
                      <ul className="space-y-2">
                        {project.features?.map((feature, idx) => (
                          <li key={idx} className="flex items-center">
                            <Building className="w-4 h-4 text-green-700 mr-2" />
                            <span className="text-gray-700">{feature}</span>
                          </li>
                        ))}
                      </ul>
                    </div>
                  </div>
                </DialogContent>
              </Dialog>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl font-bold text-gray-900 mb-6">
            Ihr Projekt könnte das nächste sein
          </h2>
          <p className="text-xl text-gray-600 mb-8">
            Lassen Sie uns gemeinsam Ihre Bauideen verwirklichen. Kontaktieren Sie uns für ein unverbindliches Beratungsgespräch.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button 
              className="bg-green-700 hover:bg-green-800 px-8 py-3 text-lg"
              onClick={() => navigate('/kontakt')}
            >
              Projekt besprechen
            </Button>
            <Button 
              variant="outline"
              className="border-green-700 text-green-700 hover:bg-green-700 hover:text-white px-8 py-3 text-lg"
              onClick={() => navigate('/angebot')}
            >
              Kostenvoranschlag
            </Button>
          </div>
        </div>
      </section>
    </div>
  );
};

export default ProjectsPage;