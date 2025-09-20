import { useState, useEffect } from "react";
import axios from "axios";
import { Button } from "./ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Input } from "./ui/input";
import { Textarea } from "./ui/textarea";
import { Label } from "./ui/label";
import { Badge } from "./ui/badge";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "./ui/dialog";
import { 
  Briefcase, 
  MapPin, 
  Clock, 
  Upload,
  ArrowLeft,
  Building,
  Users,
  Zap
} from "lucide-react";
import { useNavigate } from "react-router-dom";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const CareerPage = () => {
  const [jobs, setJobs] = useState([]);
  const [selectedJob, setSelectedJob] = useState(null);
  const [applicationData, setApplicationData] = useState({
    name: '',
    email: '',
    phone: '',
    cover_letter: ''
  });
  const [cvFile, setCvFile] = useState(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const navigate = useNavigate();

  // Beispiel-Jobs (da wir noch keine Job-Erstellung haben)
  const sampleJobs = [
    {
      id: "1",
      title: "Bauleiter (m/w/d)",
      description: "Wir suchen einen erfahrenen Bauleiter zur Leitung unserer Hochbauprojekte. Sie übernehmen die Koordination aller Gewerke und stellen die termingerechte und qualitätsorientierte Ausführung sicher.",
      requirements: "• Abgeschlossenes Studium im Bauwesen oder vergleichbare Ausbildung\n• Mindestens 3 Jahre Berufserfahrung als Bauleiter\n• Führerschein Klasse B\n• Teamfähigkeit und Kommunikationsstärke",
      location: "Musterstadt",
      employment_type: "Vollzeit",
      is_active: true,
      created_at: new Date().toISOString()
    },
    {
      id: "2",
      title: "Maurergeselle (m/w/d)",
      description: "Für unsere vielfältigen Bauprojekte suchen wir einen zuverlässigen Maurergeselle. Sie arbeiten in einem eingespielten Team und bringen Ihre handwerklichen Fähigkeiten in spannenden Projekten ein.",
      requirements: "• Abgeschlossene Ausbildung als Maurer\n• Berufserfahrung von Vorteil\n• Zuverlässigkeit und Teamgeist\n• Körperliche Belastbarkeit",
      location: "Musterstadt und Umgebung",
      employment_type: "Vollzeit",
      is_active: true,
      created_at: new Date().toISOString()
    },
    {
      id: "3",
      title: "Projektmanager Bau (m/w/d)",
      description: "Als Projektmanager betreuen Sie unsere Bauprojekte von der Angebotsphase bis zur Übergabe. Sie koordinieren alle Beteiligten und sorgen für einen reibungslosen Projektablauf.",
      requirements: "• Studium im Bereich Bauingenieurwesen oder Architektur\n• Erfahrung im Projektmanagement\n• MS Office und CAD-Kenntnisse\n• Organisationstalent und Durchsetzungsvermögen",
      location: "Musterstadt",
      employment_type: "Vollzeit",
      is_active: true,
      created_at: new Date().toISOString()
    }
  ];

  useEffect(() => {
    // In der finalen Version würden wir hier Jobs vom Backend laden
    setJobs(sampleJobs);
  }, []);

  const handleApplication = async (e) => {
    e.preventDefault();
    setIsSubmitting(true);

    try {
      const formData = new FormData();
      formData.append('job_id', selectedJob.id);
      formData.append('name', applicationData.name);
      formData.append('email', applicationData.email);
      formData.append('phone', applicationData.phone);
      formData.append('cover_letter', applicationData.cover_letter);
      
      if (cvFile) {
        formData.append('cv_file', cvFile);
      }

      await axios.post(`${API}/applications`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      alert('Bewerbung erfolgreich eingereicht!');
      setApplicationData({ name: '', email: '', phone: '', cover_letter: '' });
      setCvFile(null);
      setSelectedJob(null);
    } catch (error) {
      alert('Fehler beim Einreichen der Bewerbung.');
      console.error(error);
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-16">
            <div className="flex items-center space-x-4">
              <Button 
                variant="ghost" 
                onClick={() => navigate('/')}
                className="text-green-700 hover:text-green-800"
              >
                <ArrowLeft className="w-4 h-4 mr-2" />
                Zurück zur Hauptseite
              </Button>
            </div>
            <div className="font-bold text-2xl text-green-800">Hohmann Bau - Karriere</div>
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {/* Hero Section */}
        <div className="text-center mb-16">
          <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Ihre Karriere bei Hohmann Bau
          </h1>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto">
            Werden Sie Teil unseres Teams und gestalten Sie mit uns die Zukunft des Bauens. 
            Wir bieten spannende Projekte, faire Bezahlung und ein kollegiales Arbeitsumfeld.
          </p>
        </div>

        {/* Warum Hohmann Bau */}
        <div className="mb-16">
          <h2 className="text-3xl font-bold text-gray-900 text-center mb-12">
            Warum Hohmann Bau?
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <Card className="text-center">
              <CardHeader>
                <Building className="w-12 h-12 text-green-700 mx-auto mb-4" />
                <CardTitle>Vielfältige Projekte</CardTitle>
              </CardHeader>
              <CardContent>
                <CardDescription>
                  Von Wohnbau bis Infrastruktur - arbeiten Sie an spannenden und abwechslungsreichen Bauprojekten.
                </CardDescription>
              </CardContent>
            </Card>
            
            <Card className="text-center">
              <CardHeader>
                <Users className="w-12 h-12 text-green-700 mx-auto mb-4" />
                <CardTitle>Starkes Team</CardTitle>
              </CardHeader>
              <CardContent>
                <CardDescription>
                  Werden Sie Teil eines erfahrenen und kollegialen Teams, das gemeinsam Großes erreicht.
                </CardDescription>
              </CardContent>
            </Card>
            
            <Card className="text-center">
              <CardHeader>
                <Zap className="w-12 h-12 text-green-700 mx-auto mb-4" />
                <CardTitle>Weiterentwicklung</CardTitle>
              </CardHeader>
              <CardContent>
                <CardDescription>
                  Nutzen Sie unsere Weiterbildungsmöglichkeiten und entwickeln Sie sich beruflich weiter.
                </CardDescription>
              </CardContent>
            </Card>
          </div>
        </div>

        {/* Stellenausschreibungen */}
        <div>
          <h2 className="text-3xl font-bold text-gray-900 text-center mb-12">
            Aktuelle Stellenausschreibungen
          </h2>
          
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {jobs.map((job) => (
              <Card key={job.id} className="hover:shadow-lg transition-shadow duration-300">
                <CardHeader>
                  <div className="flex justify-between items-start mb-2">
                    <Badge variant="secondary">{job.employment_type}</Badge>
                  </div>
                  <CardTitle className="text-xl">{job.title}</CardTitle>
                  <div className="flex items-center text-sm text-gray-600 space-x-4">
                    <div className="flex items-center">
                      <MapPin className="w-4 h-4 mr-1" />
                      {job.location}
                    </div>
                    <div className="flex items-center">
                      <Clock className="w-4 h-4 mr-1" />
                      {job.employment_type}
                    </div>
                  </div>
                </CardHeader>
                <CardContent>
                  <CardDescription className="mb-4 line-clamp-3">
                    {job.description}
                  </CardDescription>
                  <Dialog>
                    <DialogTrigger asChild>
                      <Button 
                        className="w-full bg-green-700 hover:bg-green-800"
                        onClick={() => setSelectedJob(job)}
                      >
                        <Briefcase className="w-4 h-4 mr-2" />
                        Jetzt bewerben
                      </Button>
                    </DialogTrigger>
                    <DialogContent className="max-w-4xl max-h-[90vh] overflow-y-auto">
                      <DialogHeader>
                        <DialogTitle className="text-2xl">{job.title}</DialogTitle>
                        <DialogDescription className="flex items-center space-x-4 text-base">
                          <div className="flex items-center">
                            <MapPin className="w-4 h-4 mr-1" />
                            {job.location}
                          </div>
                          <Badge variant="secondary">{job.employment_type}</Badge>
                        </DialogDescription>
                      </DialogHeader>
                      
                      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {/* Job Details */}
                        <div className="space-y-6">
                          <div>
                            <h3 className="text-lg font-semibold mb-3">Stellenbeschreibung</h3>
                            <p className="text-gray-700 whitespace-pre-line">{job.description}</p>
                          </div>
                          
                          <div>
                            <h3 className="text-lg font-semibold mb-3">Anforderungen</h3>
                            <p className="text-gray-700 whitespace-pre-line">{job.requirements}</p>
                          </div>
                        </div>
                        
                        {/* Application Form */}
                        <div>
                          <h3 className="text-lg font-semibold mb-4">Bewerbung einreichen</h3>
                          <form onSubmit={handleApplication} className="space-y-4">
                            <div>
                              <Label htmlFor="name">Name *</Label>
                              <Input
                                id="name"
                                type="text"
                                value={applicationData.name}
                                onChange={(e) => setApplicationData({...applicationData, name: e.target.value})}
                                required
                              />
                            </div>
                            
                            <div>
                              <Label htmlFor="email">E-Mail *</Label>
                              <Input
                                id="email"
                                type="email"
                                value={applicationData.email}
                                onChange={(e) => setApplicationData({...applicationData, email: e.target.value})}
                                required
                              />
                            </div>
                            
                            <div>
                              <Label htmlFor="phone">Telefon</Label>
                              <Input
                                id="phone"
                                type="tel"
                                value={applicationData.phone}
                                onChange={(e) => setApplicationData({...applicationData, phone: e.target.value})}
                              />
                            </div>
                            
                            <div>
                              <Label htmlFor="cover_letter">Anschreiben *</Label>
                              <Textarea
                                id="cover_letter"
                                value={applicationData.cover_letter}
                                onChange={(e) => setApplicationData({...applicationData, cover_letter: e.target.value})}
                                rows={6}
                                placeholder="Erzählen Sie uns, warum Sie der richtige Kandidat für diese Position sind..."
                                required
                              />
                            </div>
                            
                            <div>
                              <Label htmlFor="cv">Lebenslauf (PDF)</Label>
                              <div className="mt-1">
                                <Input
                                  id="cv"
                                  type="file"
                                  accept=".pdf"
                                  onChange={(e) => setCvFile(e.target.files[0])}
                                  className="file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                                />
                              </div>
                            </div>
                            
                            <Button 
                              type="submit" 
                              className="w-full bg-green-700 hover:bg-green-800"
                              disabled={isSubmitting}
                            >
                              {isSubmitting ? (
                                'Bewerbung wird eingereicht...'
                              ) : (
                                <>
                                  <Upload className="w-4 h-4 mr-2" />
                                  Bewerbung einreichen
                                </>
                              )}
                            </Button>
                          </form>
                        </div>
                      </div>
                    </DialogContent>
                  </Dialog>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
        
        {jobs.length === 0 && (
          <div className="text-center py-12">
            <Briefcase className="w-16 h-16 text-gray-400 mx-auto mb-4" />
            <h3 className="text-lg font-medium text-gray-900 mb-2">
              Derzeit keine offenen Stellen
            </h3>
            <p className="text-gray-600">
              Schauen Sie bald wieder vorbei oder senden Sie uns eine Initiativbewerbung.
            </p>
          </div>
        )}
      </div>
    </div>
  );
};

export default CareerPage;