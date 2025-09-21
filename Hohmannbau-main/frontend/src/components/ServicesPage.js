import { useState, useEffect } from "react";
import axios from "axios";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Button } from "./ui/button";
import { Badge } from "./ui/badge";
import { Building, Hammer, Wrench, Users, ArrowRight } from "lucide-react";
import { useNavigate } from "react-router-dom";
import NewsSection from "./NewsSection";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const ServicesPage = () => {
  const [services, setServices] = useState([]);
  const [pageContent, setPageContent] = useState({
    title: "Unsere Leistungen",
    subtitle: "Umfassende Baulösungen aus einer Hand",
    description: "Von der ersten Idee bis zur schlüsselfertigen Übergabe begleiten wir Sie durch Ihr gesamtes Bauvorhaben. Unsere erfahrenen Fachkräfte garantieren höchste Qualität und termingerechte Ausführung."
  });
  const navigate = useNavigate();

  useEffect(() => {
    fetchServices();
    fetchPageContent();
  }, []);

  const fetchServices = async () => {
    try {
      const response = await axios.get(`${API}/services`);
      setServices(response.data);
    } catch (error) {
      // Fallback services
      setServices([
        {
          id: "1",
          title: "Hochbau",
          description: "Vom Einfamilienhaus bis zum Geschäftskomplex - wir realisieren Ihre Bauprojekte mit höchster Qualität und modernsten Baumethoden.",
          features: ["Neubau Wohn- und Geschäftsgebäude", "Schlüsselfertige Übergabe", "Energieeffiziente Bauweise", "Individuelle Architektenlösungen"],
          icon: "building",
          image: "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDQ2NDN8MHwxfHNlYXJjaHwxfHxidWlsZGluZ3xlbnwwfHx8fDE3NTgzNzgxMjV8MA&ixlib=rb-4.1.0&q=85"
        },
        {
          id: "2",
          title: "Tiefbau",
          description: "Fundamente, Kanalisationen und Infrastrukturprojekte - die solide Basis für jedes Bauvorhaben mit modernster Technik.",
          features: ["Fundament- und Kellerbau", "Kanalisation und Entwässerung", "Straßen- und Wegebau", "Erdarbeiten und Aushub"],
          icon: "hammer",
          image: "https://images.unsplash.com/photo-1535732759880-bbd5c7265e3f?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHw0fHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85"
        },
        {
          id: "3",
          title: "Sanierung",
          description: "Modernisierung und Renovierung bestehender Gebäude mit nachhaltigen und energieeffizienten Lösungen für die Zukunft.",
          features: ["Dachsanierung und -dämmung", "Fassadenmodernisierung", "Badsanierung komplett", "Energetische Sanierung"],
          icon: "wrench",
          image: "https://images.pexels.com/photos/302769/pexels-photo-302769.jpeg"
        },
        {
          id: "4",
          title: "Projektsteuerung",
          description: "Professionelle Begleitung Ihres Bauprojekts von der Planung bis zur schlüsselfertigen Übergabe durch erfahrene Bauleiter.",
          features: ["Bauplanung und -koordination", "Terminüberwachung", "Qualitätskontrolle", "Kostenmanagement"],
          icon: "users",
          image: "https://images.unsplash.com/photo-1541888946425-d81bb19240f5?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwzfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85"
        }
      ]);
    }
  };

  const fetchPageContent = async () => {
    try {
      const response = await axios.get(`${API}/content/services`);
      if (response.data) {
        setPageContent(response.data);
      }
    } catch (error) {
      console.log('Using default services content');
    }
  };

  const getIcon = (iconName) => {
    switch (iconName) {
      case 'building': return <Building className="w-12 h-12 text-green-700" />;
      case 'hammer': return <Hammer className="w-12 h-12 text-green-700" />;
      case 'wrench': return <Wrench className="w-12 h-12 text-green-700" />;
      case 'users': return <Users className="w-12 h-12 text-green-700" />;
      default: return <Building className="w-12 h-12 text-green-700" />;
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

      {/* Services Grid */}
      <section className="py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {services.map((service, index) => (
              <Card key={service.id} className="hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div className="relative h-64 overflow-hidden">
                  <img 
                    src={service.image} 
                    alt={service.title}
                    className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                  />
                  <div className="absolute top-4 left-4 bg-white p-3 rounded-full shadow-lg">
                    {getIcon(service.icon)}
                  </div>
                </div>
                <CardHeader>
                  <CardTitle className="text-2xl font-bold text-gray-900">{service.title}</CardTitle>
                  <CardDescription className="text-lg text-gray-600">
                    {service.description}
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <div className="space-y-3 mb-6">
                    {service.features.map((feature, idx) => (
                      <div key={idx} className="flex items-center">
                        <ArrowRight className="w-4 h-4 text-green-700 mr-3" />
                        <span className="text-gray-700">{feature}</span>
                      </div>
                    ))}
                  </div>
                  <Button 
                    className="w-full bg-green-700 hover:bg-green-800"
                    onClick={() => navigate('/kontakt')}
                  >
                    Beratung anfragen
                  </Button>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl font-bold text-gray-900 mb-6">
            Haben Sie Fragen zu unseren Leistungen?
          </h2>
          <p className="text-xl text-gray-600 mb-8">
            Lassen Sie sich von unseren Experten beraten und erhalten Sie ein kostenloses Angebot für Ihr Bauvorhaben.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button 
              className="bg-green-700 hover:bg-green-800 px-8 py-3 text-lg"
              onClick={() => navigate('/kontakt')}
            >
              Kostenlose Beratung
            </Button>
            <Button 
              variant="outline"
              className="border-green-700 text-green-700 hover:bg-green-700 hover:text-white px-8 py-3 text-lg"
              onClick={() => navigate('/angebot')}
            >
              Angebot anfordern
            </Button>
          </div>
        </div>
      </section>

      {/* News Section */}
      <NewsSection pageContext="services" maxItems={3} />
    </div>
  );
};

export default ServicesPage;