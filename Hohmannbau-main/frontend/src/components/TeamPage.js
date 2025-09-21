import { useState, useEffect } from "react";
import axios from "axios";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Button } from "./ui/button";
import { Badge } from "./ui/badge";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "./ui/dialog";
import { Mail, Phone, Linkedin, MapPin } from "lucide-react";
import { useNavigate } from "react-router-dom";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const TeamPage = () => {
  const [teamMembers, setTeamMembers] = useState([]);
  const [pageContent, setPageContent] = useState({
    title: "Unser Team",
    subtitle: "Erfahrene Fachkräfte für Ihr Projekt",
    description: "Lernen Sie die Menschen kennen, die hinter unseren erfolgreichen Bauprojekten stehen. Unser erfahrenes Team aus Ingenieuren, Architekten und Baupleitern bringt jahrzehntelange Expertise mit."
  });
  const navigate = useNavigate();

  useEffect(() => {
    fetchTeamMembers();
    fetchPageContent();
  }, []);

  const fetchTeamMembers = async () => {
    try {
      const response = await axios.get(`${API}/team`);
      if (response.data && response.data.length > 0) {
        setTeamMembers(response.data);
      } else {
        // Fallback team members with more details
        setTeamMembers([
          {
            id: "1",
            name: "Klaus Hohmann",
            role: "Geschäftsführer",
            image: "https://images.unsplash.com/photo-1694521787162-5373b598945c?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwxfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85",
            bio: "Mit über 25 Jahren Erfahrung im Baugewerbe führt Klaus Hohmann das Unternehmen seit der Gründung. Seine Vision von nachhaltigem und qualitätsorientiertem Bauen prägt alle Projekte.",
            expertise: ["Unternehmensführung", "Strategische Planung", "Kundenkontakt", "Qualitätsmanagement"],
            email: "k.hohmann@hohmann-bau.de",
            phone: "+49 123 456 789",
            experience: "25+ Jahre",
            education: "Diplom-Bauingenieur TU München"
          },
          {
            id: "2",
            name: "Maria Schneider",
            role: "Projektleiterin",
            image: "https://images.unsplash.com/photo-1541888946425-d81bb19240f5?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwzfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85",
            bio: "Als erfahrene Projektleiterin koordiniert Maria Schneider komplexe Bauvorhaben und sorgt für die termingerechte und budgetkonforme Umsetzung aller Projekte.",
            expertise: ["Projektmanagement", "Terminplanung", "Kostencontrolling", "Teamführung"],
            email: "m.schneider@hohmann-bau.de", 
            phone: "+49 123 456 790",
            experience: "15+ Jahre",
            education: "Master Bauingenieurwesen, Projektmanagement-Zertifikat"
          },
          {
            id: "3",
            name: "Thomas Weber",
            role: "Bauleiter",
            image: "https://images.unsplash.com/photo-1535732759880-bbd5c7265e3f?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHw0fHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85",
            bio: "Thomas Weber überwacht die Ausführung vor Ort und ist Ansprechpartner für alle handwerklichen und technischen Fragen während der Bauphase.",
            expertise: ["Bauüberwachung", "Qualitätskontrolle", "Sicherheitsmanagement", "Gewerkekoordination"],
            email: "t.weber@hohmann-bau.de",
            phone: "+49 123 456 791", 
            experience: "18+ Jahre",
            education: "Meister Maurerhandwerk, Weiterbildung Baubetriebswirtschaft"
          },
          {
            id: "4",
            name: "Sandra Mueller",
            role: "Architektin",
            image: "https://images.unsplash.com/photo-1694521787162-5373b598945c?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwxfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85",
            bio: "Sandra Mueller entwickelt kreative und funktionale Architektenlösungen, die sowohl ästhetischen als auch praktischen Anforderungen gerecht werden.",
            expertise: ["Architekturplanung", "3D-Visualisierung", "Baurecht", "Nachhaltiges Bauen"],
            email: "s.mueller@hohmann-bau.de",
            phone: "+49 123 456 792",
            experience: "12+ Jahre", 
            education: "Master Architektur, Spezialisierung nachhaltiges Bauen"
          },
          {
            id: "5",
            name: "Michael Bauer",
            role: "Kalkulator",
            image: "https://images.unsplash.com/photo-1541888946425-d81bb19240f5?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwzfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85",
            bio: "Michael Bauer erstellt präzise Kostenkalkulationen und Angebote, die unseren Kunden Transparenz und Planungssicherheit bieten.",
            expertise: ["Kostenkalkulation", "Ausschreibungswesen", "Materialplanung", "Angebotserstellung"],
            email: "m.bauer@hohmann-bau.de",
            phone: "+49 123 456 793",
            experience: "10+ Jahre",
            education: "Bachelor Bauingenieurwesen, Zertifikat Baukalkulation"
          }
        ]);
      }
    } catch (error) {
      console.error('Error fetching team members:', error);
    }
  };

  const fetchPageContent = async () => {
    try {
      const response = await axios.get(`${API}/content/team`);
      if (response.data) {
        setPageContent(response.data);
      }
    } catch (error) {
      console.log('Using default team content');
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

      {/* Team Grid */}
      <section className="py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {teamMembers.map((member) => (
              <Dialog key={member.id}>
                <DialogTrigger asChild>
                  <Card className="text-center hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                    <CardHeader>
                      <div className="w-48 h-48 mx-auto mb-4 overflow-hidden rounded-full">
                        <img 
                          src={member.image} 
                          alt={member.name}
                          className="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                        />
                      </div>
                      <CardTitle className="text-2xl">{member.name}</CardTitle>
                      <CardDescription className="text-green-700 font-medium text-lg">
                        {member.role}
                      </CardDescription>
                      {member.experience && (
                        <Badge variant="secondary" className="mx-auto">
                          {member.experience} Erfahrung
                        </Badge>
                      )}
                    </CardHeader>
                    <CardContent>
                      <p className="text-gray-600 line-clamp-3">{member.bio}</p>
                    </CardContent>
                  </Card>
                </DialogTrigger>
                <DialogContent className="max-w-3xl max-h-[90vh] overflow-y-auto">
                  <DialogHeader>
                    <DialogTitle className="text-2xl">{member.name}</DialogTitle>
                    <DialogDescription className="text-green-700 font-medium text-lg">
                      {member.role}
                    </DialogDescription>
                  </DialogHeader>
                  
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="text-center">
                      <div className="w-64 h-64 mx-auto mb-4 overflow-hidden rounded-lg">
                        <img 
                          src={member.image} 
                          alt={member.name}
                          className="w-full h-full object-cover"
                        />
                      </div>
                      <div className="space-y-3">
                        {member.email && (
                          <div className="flex items-center justify-center">
                            <Mail className="w-4 h-4 mr-2 text-green-700" />
                            <a href={`mailto:${member.email}`} className="text-green-700 hover:underline">
                              {member.email}
                            </a>
                          </div>
                        )}
                        {member.phone && (
                          <div className="flex items-center justify-center">
                            <Phone className="w-4 h-4 mr-2 text-green-700" />
                            <a href={`tel:${member.phone}`} className="text-green-700 hover:underline">
                              {member.phone}
                            </a>
                          </div>
                        )}
                      </div>
                    </div>
                    
                    <div className="space-y-4">
                      <div>
                        <h3 className="text-lg font-semibold mb-2">Über {member.name.split(' ')[0]}</h3>
                        <p className="text-gray-700">{member.bio}</p>
                      </div>
                      
                      {member.education && (
                        <div>
                          <h3 className="text-lg font-semibold mb-2">Ausbildung</h3>
                          <p className="text-gray-700">{member.education}</p>
                        </div>
                      )}
                      
                      {member.expertise && member.expertise.length > 0 && (
                        <div>
                          <h3 className="text-lg font-semibold mb-2">Fachbereiche</h3>
                          <div className="space-y-2">
                            {member.expertise.map((skill, idx) => (
                              <div key={idx} className="flex items-center">
                                <div className="w-2 h-2 bg-green-700 rounded-full mr-3"></div>
                                <span className="text-gray-700">{skill}</span>
                              </div>
                            ))}
                          </div>
                        </div>
                      )}
                    </div>
                  </div>
                </DialogContent>
              </Dialog>
            ))}
          </div>
        </div>
      </section>

      {/* Company Culture Section */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-gray-900 mb-6">Unsere Unternehmenskultur</h2>
            <p className="text-xl text-gray-600 max-w-3xl mx-auto">
              Bei Hohmann Bau steht der Mensch im Mittelpunkt. Wir fördern Teamwork, 
              kontinuierliche Weiterbildung und eine positive Arbeitsatmosphäre.
            </p>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <Card className="text-center">
              <CardHeader>
                <div className="text-4xl mb-4">🤝</div>
                <CardTitle>Teamwork</CardTitle>
              </CardHeader>
              <CardContent>
                <CardDescription>
                  Gemeinsam erreichen wir mehr. Unsere interdisziplinären Teams 
                  arbeiten eng zusammen für beste Ergebnisse.
                </CardDescription>
              </CardContent>
            </Card>
            
            <Card className="text-center">
              <CardHeader>
                <div className="text-4xl mb-4">📚</div>
                <CardTitle>Weiterbildung</CardTitle>
              </CardHeader>
              <CardContent>
                <CardDescription>
                  Wir investieren in die Weiterentwicklung unserer Mitarbeiter 
                  durch regelmäßige Schulungen und Fortbildungen.
                </CardDescription>
              </CardContent>
            </Card>
            
            <Card className="text-center">
              <CardHeader>
                <div className="text-4xl mb-4">🎯</div>
                <CardTitle>Innovation</CardTitle>
              </CardHeader>
              <CardContent>
                <CardDescription>
                  Neue Technologien und moderne Baumethoden helfen uns, 
                  immer die besten Lösungen für unsere Kunden zu finden.
                </CardDescription>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-gray-50">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl font-bold text-gray-900 mb-6">
            Werden Sie Teil unseres Teams
          </h2>
          <p className="text-xl text-gray-600 mb-8">
            Wir sind immer auf der Suche nach talentierten und motivierten Fachkräften, 
            die unsere Vision von qualitätsorientiertem Bauen teilen.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button 
              className="bg-green-700 hover:bg-green-800 px-8 py-3 text-lg"
              onClick={() => navigate('/karriere')}
            >
              Karriere-Möglichkeiten
            </Button>
            <Button 
              variant="outline"
              className="border-green-700 text-green-700 hover:bg-green-700 hover:text-white px-8 py-3 text-lg"
              onClick={() => navigate('/kontakt')}
            >
              Initiativbewerbung
            </Button>
          </div>
        </div>
      </section>
    </div>
  );
};

export default TeamPage;