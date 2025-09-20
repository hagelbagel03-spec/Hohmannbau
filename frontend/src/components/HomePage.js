import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { Button } from "./ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Calculator, ArrowRight } from "lucide-react";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const HomePage = () => {
  const [homeContent, setHomeContent] = useState({
    hero_title: "Bauen mit Vertrauen",
    hero_subtitle: "Ihr zuverlässiger Partner für Hochbau, Tiefbau und Sanierungen",
    hero_image: "https://images.unsplash.com/photo-1599995903128-531fc7fb694b?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwyfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85"
  });
  const [features, setFeatures] = useState([]);
  const navigate = useNavigate();

  useEffect(() => {
    fetchHomeContent();
    fetchFeatures();
  }, []);

  const fetchHomeContent = async () => {
    try {
      const response = await axios.get(`${API}/content/home`);
      if (response.data) {
        setHomeContent(response.data);
      }
    } catch (error) {
      console.log('Using default home content');
    }
  };

  const fetchFeatures = async () => {
    try {
      const response = await axios.get(`${API}/features`);
      setFeatures(response.data);
    } catch (error) {
      // Fallback features
      setFeatures([
        {
          id: "1",
          title: "25+ Jahre Erfahrung",
          description: "Über zwei Jahrzehnte Expertise im Baugewerbe mit hunderten erfolgreich abgeschlossenen Projekten.",
          icon: "🏗️"
        },
        {
          id: "2", 
          title: "Qualität & Zuverlässigkeit",
          description: "Höchste Qualitätsstandards und termingerechte Ausführung aller Bauvorhaben.",
          icon: "✅"
        },
        {
          id: "3",
          title: "Rundum-Service",
          description: "Von der Planung bis zur Übergabe - alles aus einer Hand für Ihr Bauprojekt.",
          icon: "🔧"
        }
      ]);
    }
  };

  return (
    <div className="min-h-screen">
      {/* Hero Section */}
      <section className="relative min-h-screen flex items-center justify-center">
        <div 
          className="absolute inset-0 bg-cover bg-center bg-no-repeat"
          style={{
            backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('${homeContent.hero_image}')`
          }}
        />
        <div className="relative z-10 text-center text-white max-w-4xl mx-auto px-4">
          <h1 className="text-5xl md:text-7xl font-bold mb-6 leading-tight">
            {homeContent.hero_title}
          </h1>
          <p className="text-xl md:text-2xl mb-8 text-gray-200">
            {homeContent.hero_subtitle}
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button 
              className="bg-green-700 hover:bg-green-800 text-white px-8 py-3 text-lg font-medium rounded-lg transition-all duration-300 transform hover:scale-105"
              onClick={() => navigate('/kontakt')}
            >
              Kontakt aufnehmen
            </Button>
            <Button 
              variant="outline"
              className="border-white text-white hover:bg-white hover:text-green-800 px-8 py-3 text-lg font-medium rounded-lg transition-all duration-300"
              onClick={() => navigate('/angebot')}
            >
              <Calculator className="w-5 h-5 mr-2" />
              Angebot anfordern
            </Button>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">Warum Hohmann Bau?</h2>
            <p className="text-xl text-gray-600">Vertrauen Sie auf unsere Expertise und Erfahrung</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {features.map((feature) => (
              <Card key={feature.id} className="text-center hover:shadow-lg transition-shadow duration-300">
                <CardHeader>
                  <div className="text-4xl mb-4">{feature.icon}</div>
                  <CardTitle className="text-xl font-bold text-gray-900">{feature.title}</CardTitle>
                </CardHeader>
                <CardContent>
                  <CardDescription className="text-gray-600">
                    {feature.description}
                  </CardDescription>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Quick Links Section */}
      <section className="py-20 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 mb-4">Unsere Bereiche</h2>
            <p className="text-xl text-gray-600">Entdecken Sie unser vollständiges Leistungsspektrum</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <Card className="cursor-pointer hover:shadow-lg transition-all duration-300 transform hover:scale-105" onClick={() => navigate('/leistungen')}>
              <CardHeader className="text-center">
                <div className="text-3xl mb-4">🏗️</div>
                <CardTitle>Leistungen</CardTitle>
              </CardHeader>
              <CardContent className="text-center">
                <CardDescription>Hochbau, Tiefbau, Sanierung und mehr</CardDescription>
                <ArrowRight className="w-5 h-5 mx-auto mt-4 text-green-700" />
              </CardContent>
            </Card>

            <Card className="cursor-pointer hover:shadow-lg transition-all duration-300 transform hover:scale-105" onClick={() => navigate('/projekte')}>
              <CardHeader className="text-center">
                <div className="text-3xl mb-4">🏢</div>
                <CardTitle>Projekte</CardTitle>
              </CardHeader>
              <CardContent className="text-center">
                <CardDescription>Unsere Referenzen und aktuellen Bauvorhaben</CardDescription>
                <ArrowRight className="w-5 h-5 mx-auto mt-4 text-green-700" />
              </CardContent>
            </Card>

            <Card className="cursor-pointer hover:shadow-lg transition-all duration-300 transform hover:scale-105" onClick={() => navigate('/team')}>
              <CardHeader className="text-center">
                <div className="text-3xl mb-4">👥</div>
                <CardTitle>Team</CardTitle>
              </CardHeader>
              <CardContent className="text-center">
                <CardDescription>Lernen Sie unsere Experten kennen</CardDescription>
                <ArrowRight className="w-5 h-5 mx-auto mt-4 text-green-700" />
              </CardContent>
            </Card>

            <Card className="cursor-pointer hover:shadow-lg transition-all duration-300 transform hover:scale-105" onClick={() => navigate('/karriere')}>
              <CardHeader className="text-center">
                <div className="text-3xl mb-4">💼</div>
                <CardTitle>Karriere</CardTitle>
              </CardHeader>
              <CardContent className="text-center">
                <CardDescription>Werden Sie Teil unseres Teams</CardDescription>
                <ArrowRight className="w-5 h-5 mx-auto mt-4 text-green-700" />
              </CardContent>
            </Card>
          </div>
        </div>
      </section>
    </div>
  );
};

export default HomePage;