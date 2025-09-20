import { useState, useEffect } from "react";
import axios from "axios";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Button } from "./ui/button";
import { Badge } from "./ui/badge";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "./ui/dialog";
import { Calendar, User, ArrowRight } from "lucide-react";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const NewsSection = ({ pageContext = "general", maxItems = 3 }) => {
  const [news, setNews] = useState([]);

  useEffect(() => {
    fetchNews();
  }, []);

  const fetchNews = async () => {
    try {
      const response = await axios.get(`${API}/news?page=${pageContext}&limit=${maxItems}`);
      if (response.data && response.data.length > 0) {
        setNews(response.data);
      } else {
        // Fallback news based on page context
        const fallbackNews = getFallbackNews(pageContext);
        setNews(fallbackNews.slice(0, maxItems));
      }
    } catch (error) {
      const fallbackNews = getFallbackNews(pageContext);
      setNews(fallbackNews.slice(0, maxItems));
    }
  };

  const getFallbackNews = (context) => {
    const allNews = [
      {
        id: "1",
        title: "Neues Wohnbauprojekt in Musterstadt gestartet",
        excerpt: "Wir freuen uns, den Baubeginn für unser neues Wohnbauprojekt mit 150 modernen Einheiten bekannt zu geben.",
        content: "Das neue Wohnbauprojekt in Musterstadt umfasst 150 moderne Wohneinheiten auf einem 12.000 m² großen Gelände. Mit innovativer Architektur und nachhaltiger Bauweise setzen wir neue Maßstäbe im sozialen Wohnungsbau.",
        author: "Klaus Hohmann",
        image_url: "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDQ2NDN8MHwxfHNlYXJjaHwxfHxidWlsZGluZ3xlbnwwfHx8fDE3NTgzNzgxMjV8MA&ixlib=rb-4.1.0&q=85",
        category: "Projekte",
        created_at: "2024-01-15T10:00:00Z",
        is_published: true
      },
      {
        id: "2",
        title: "Hohmann Bau erhält Auszeichnung für Nachhaltigkeit",
        excerpt: "Unser Engagement für umweltfreundliches Bauen wurde mit dem Green Building Award ausgezeichnet.",
        content: "Die Deutsche Gesellschaft für Nachhaltiges Bauen hat Hohmann Bau mit dem prestigeträchtigen Green Building Award ausgezeichnet. Diese Ehrung würdigt unser kontinuierliches Engagement für umweltfreundliche Bautechniken und Materialien.",
        author: "Maria Schneider",
        image_url: "https://images.unsplash.com/photo-1488972685288-c3fd157d7c7a?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NTY2NzF8MHwxfHNlYXJjaHwxfHxhcmNoaXRlY3R1cmV8ZW58MHx8fHwxNzU4Mzc4MTMwfDA&ixlib=rb-4.1.0&q=85",
        category: "Unternehmen",
        created_at: "2024-01-10T14:30:00Z",
        is_published: true
      },
      {
        id: "3",
        title: "Neue Mitarbeiter verstärken unser Team",
        excerpt: "Wir begrüßen fünf neue Fachkräfte in unserem Team und erweitern damit unsere Kapazitäten.",
        content: "Unser Team wächst weiter: Fünf erfahrene Fachkräfte verstärken ab sofort unser Team in den Bereichen Projektmanagement, Bauleitung und Architektur. Mit dieser Erweiterung können wir noch mehr Projekte parallel bearbeiten.",
        author: "Thomas Weber",
        image_url: "https://images.unsplash.com/photo-1541888946425-d81bb19240f5?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwzfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85",
        category: "Team",
        created_at: "2024-01-05T09:15:00Z",
        is_published: true
      },
      {
        id: "4",
        title: "Digitalisierung im Bauwesen: Neue Software im Einsatz",
        excerpt: "Mit modernster BIM-Software optimieren wir unsere Planungs- und Bauprozesse für noch bessere Ergebnisse.",
        content: "Hohmann Bau setzt auf Digitalisierung: Die Einführung von Building Information Modeling (BIM) Software revolutioniert unsere Arbeitsweise. Präzisere Planung, bessere Koordination und transparente Kostenkontrolle sind die Vorteile.",
        author: "Sandra Mueller",
        image_url: "https://images.unsplash.com/photo-1535732759880-bbd5c7265e3f?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHw0fHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85",
        category: "Innovation",
        created_at: "2023-12-28T16:45:00Z",
        is_published: true
      }
    ];

    // Filter news based on page context
    switch (context) {
      case 'services':
        return allNews.filter(item => ['Innovation', 'Unternehmen'].includes(item.category));
      case 'projects':
        return allNews.filter(item => ['Projekte', 'Innovation'].includes(item.category));
      case 'team':
        return allNews.filter(item => ['Team', 'Unternehmen'].includes(item.category));
      case 'contact':
        return allNews.filter(item => ['Unternehmen', 'Team'].includes(item.category));
      default:
        return allNews;
    }
  };

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('de-DE', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  if (news.length === 0) return null;

  return (
    <section className="py-16 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <h2 className="text-3xl font-bold text-gray-900 mb-4">Aktuelles & News</h2>
          <p className="text-lg text-gray-600">Bleiben Sie über unsere neuesten Projekte und Entwicklungen informiert</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {news.map((article) => (
            <Dialog key={article.id}>
              <DialogTrigger asChild>
                <Card className="cursor-pointer hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                  <div className="relative h-48 overflow-hidden">
                    <img 
                      src={article.image_url} 
                      alt={article.title}
                      className="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                    />
                    <Badge className="absolute top-4 left-4 bg-green-700">
                      {article.category}
                    </Badge>
                  </div>
                  <CardHeader>
                    <CardTitle className="text-lg line-clamp-2">{article.title}</CardTitle>
                    <CardDescription className="line-clamp-3">
                      {article.excerpt}
                    </CardDescription>
                    <div className="flex items-center justify-between text-sm text-gray-500 pt-2">
                      <div className="flex items-center">
                        <User className="w-4 h-4 mr-1" />
                        {article.author}
                      </div>
                      <div className="flex items-center">
                        <Calendar className="w-4 h-4 mr-1" />
                        {formatDate(article.created_at)}
                      </div>
                    </div>
                  </CardHeader>
                </Card>
              </DialogTrigger>
              <DialogContent className="max-w-3xl max-h-[90vh] overflow-y-auto">
                <DialogHeader>
                  <DialogTitle className="text-2xl">{article.title}</DialogTitle>
                  <DialogDescription>
                    <div className="flex items-center space-x-4 mt-2">
                      <Badge>{article.category}</Badge>
                      <div className="flex items-center text-sm">
                        <User className="w-4 h-4 mr-1" />
                        {article.author}
                      </div>
                      <div className="flex items-center text-sm">
                        <Calendar className="w-4 h-4 mr-1" />
                        {formatDate(article.created_at)}
                      </div>
                    </div>
                  </DialogDescription>
                </DialogHeader>
                
                <div className="mt-6">
                  <img 
                    src={article.image_url} 
                    alt={article.title}
                    className="w-full h-64 object-cover rounded-lg mb-6"
                  />
                  <div className="prose prose-gray max-w-none">
                    <p className="text-gray-700 leading-relaxed whitespace-pre-line">
                      {article.content}
                    </p>
                  </div>
                </div>
              </DialogContent>
            </Dialog>
          ))}
        </div>

        <div className="text-center mt-12">
          <Button variant="outline" className="border-green-700 text-green-700 hover:bg-green-700 hover:text-white">
            Alle News anzeigen
            <ArrowRight className="w-4 h-4 ml-2" />
          </Button>
        </div>
      </div>
    </section>
  );
};

export default NewsSection;