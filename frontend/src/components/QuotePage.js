import { useState } from "react";
import axios from "axios";
import { Button } from "./ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Input } from "./ui/input";
import { Textarea } from "./ui/textarea";
import { Label } from "./ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "./ui/select";
import { 
  ArrowLeft,
  FileText,
  Upload,
  Calculator,
  Clock,
  CheckCircle
} from "lucide-react";
import { useNavigate } from "react-router-dom";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const QuotePage = () => {
  const [quoteData, setQuoteData] = useState({
    name: '',
    email: '',
    phone: '',
    project_type: '',
    description: '',
    budget_range: '',
    timeline: ''
  });
  const [blueprintFile, setBlueprintFile] = useState(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isSubmitted, setIsSubmitted] = useState(false);
  const navigate = useNavigate();

  const projectTypes = [
    'Neubau Einfamilienhaus',
    'Neubau Mehrfamilienhaus',
    'Gewerbebau',
    'Umbau/Sanierung',
    'Anbau/Erweiterung',
    'Tiefbau',
    'Sonstiges'
  ];

  const budgetRanges = [
    'Bis 100.000 €',
    '100.000 - 250.000 €',
    '250.000 - 500.000 €',
    '500.000 - 1 Mio. €',
    'Über 1 Mio. €',
    'Noch nicht festgelegt'
  ];

  const timelines = [
    'Sofort',
    'In 1-3 Monaten',
    'In 3-6 Monaten',
    'In 6-12 Monaten',
    'Über 12 Monate',
    'Noch nicht festgelegt'
  ];

  const handleSubmit = async (e) => {
    e.preventDefault();
    setIsSubmitting(true);

    try {
      const formData = new FormData();
      
      // Alle Formularfelder hinzufügen
      Object.keys(quoteData).forEach(key => {
        formData.append(key, quoteData[key]);
      });
      
      if (blueprintFile) {
        formData.append('blueprint_file', blueprintFile);
      }

      await axios.post(`${API}/quote-request`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      setIsSubmitted(true);
    } catch (error) {
      alert('Fehler beim Senden der Angebotsanfrage.');
      console.error(error);
    } finally {
      setIsSubmitting(false);
    }
  };

  if (isSubmitted) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <Card className="max-w-md w-full">
          <CardHeader className="text-center">
            <CheckCircle className="w-16 h-16 text-green-600 mx-auto mb-4" />
            <CardTitle className="text-2xl text-green-800">Anfrage gesendet!</CardTitle>
            <CardDescription>
              Vielen Dank für Ihre Angebotsanfrage. Wir werden uns innerhalb von 24 Stunden bei Ihnen melden.
            </CardDescription>
          </CardHeader>
          <CardContent className="text-center">
            <Button 
              onClick={() => navigate('/')}
              className="bg-green-700 hover:bg-green-800"
            >
              Zurück zur Hauptseite
            </Button>
          </CardContent>
        </Card>
      </div>
    );
  }

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
            <div className="font-bold text-2xl text-green-800">Hohmann Bau - Angebotsanfrage</div>
          </div>
        </div>
      </div>

      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {/* Hero Section */}
        <div className="text-center mb-12">
          <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Kostenlose Angebotsanfrage
          </h1>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto">
            Teilen Sie uns Ihr Bauvorhaben mit und erhalten Sie ein unverbindliches Angebot. 
            Wir beraten Sie gerne und erstellen Ihnen ein maßgeschneidertes Angebot.
          </p>
        </div>

        {/* Vorteile */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
          <Card className="text-center">
            <CardHeader>
              <FileText className="w-8 h-8 text-green-700 mx-auto mb-2" />
              <CardTitle className="text-lg">Kostenlos</CardTitle>
            </CardHeader>
            <CardContent>
              <CardDescription>
                Unverbindliche Beratung und Kostenvoranschlag ohne versteckte Kosten.
              </CardDescription>
            </CardContent>
          </Card>
          
          <Card className="text-center">
            <CardHeader>
              <Clock className="w-8 h-8 text-green-700 mx-auto mb-2" />
              <CardTitle className="text-lg">Schnell</CardTitle>
            </CardHeader>
            <CardContent>
              <CardDescription>
                Antwort innerhalb von 24 Stunden, persönlicher Beratungstermin nach Vereinbarung.
              </CardDescription>
            </CardContent>
          </Card>
          
          <Card className="text-center">
            <CardHeader>
              <Calculator className="w-8 h-8 text-green-700 mx-auto mb-2" />
              <CardTitle className="text-lg">Transparent</CardTitle>
            </CardHeader>
            <CardContent>
              <CardDescription>
                Detaillierte Aufschlüsselung aller Kosten und Leistungen in unserem Angebot.
              </CardDescription>
            </CardContent>
          </Card>
        </div>

        {/* Angebotsformular */}
        <Card>
          <CardHeader>
            <CardTitle className="text-2xl">Ihr Bauprojekt</CardTitle>
            <CardDescription>
              Bitte füllen Sie alle Felder aus, damit wir Ihnen ein möglichst genaues Angebot erstellen können.
            </CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-6">
              {/* Persönliche Daten */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <Label htmlFor="name">Name *</Label>
                  <Input
                    id="name"
                    type="text"
                    value={quoteData.name}
                    onChange={(e) => setQuoteData({...quoteData, name: e.target.value})}
                    required
                  />
                </div>
                
                <div>
                  <Label htmlFor="email">E-Mail *</Label>
                  <Input
                    id="email"
                    type="email"
                    value={quoteData.email}
                    onChange={(e) => setQuoteData({...quoteData, email: e.target.value})}
                    required
                  />
                </div>
                
                <div>
                  <Label htmlFor="phone">Telefon</Label>
                  <Input
                    id="phone"
                    type="tel"
                    value={quoteData.phone}
                    onChange={(e) => setQuoteData({...quoteData, phone: e.target.value})}
                  />
                </div>
                
                <div>
                  <Label htmlFor="project_type">Projekttyp *</Label>
                  <Select onValueChange={(value) => setQuoteData({...quoteData, project_type: value})}>
                    <SelectTrigger>
                      <SelectValue placeholder="Projekttyp wählen" />
                    </SelectTrigger>
                    <SelectContent>
                      {projectTypes.map((type) => (
                        <SelectItem key={type} value={type}>{type}</SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              </div>

              {/* Projektbeschreibung */}
              <div>
                <Label htmlFor="description">Projektbeschreibung *</Label>
                <Textarea
                  id="description"
                  value={quoteData.description}
                  onChange={(e) => setQuoteData({...quoteData, description: e.target.value})}
                  rows={6}
                  placeholder="Beschreiben Sie Ihr Bauvorhaben so detailliert wie möglich. Welche Arbeiten sollen durchgeführt werden? Welche besonderen Anforderungen haben Sie?"
                  required
                />
              </div>

              {/* Budget und Zeitplan */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <Label htmlFor="budget_range">Budgetrahmen</Label>
                  <Select onValueChange={(value) => setQuoteData({...quoteData, budget_range: value})}>
                    <SelectTrigger>
                      <SelectValue placeholder="Budgetrahmen wählen" />
                    </SelectTrigger>
                    <SelectContent>
                      {budgetRanges.map((range) => (
                        <SelectItem key={range} value={range}>{range}</SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                
                <div>
                  <Label htmlFor="timeline">Gewünschter Zeitrahmen</Label>
                  <Select onValueChange={(value) => setQuoteData({...quoteData, timeline: value})}>
                    <SelectTrigger>
                      <SelectValue placeholder="Zeitrahmen wählen" />
                    </SelectTrigger>
                    <SelectContent>
                      {timelines.map((timeline) => (
                        <SelectItem key={timeline} value={timeline}>{timeline}</SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              </div>

              {/* Datei-Upload */}
              <div>
                <Label htmlFor="blueprint">Baupläne/Skizzen (PDF)</Label>
                <div className="mt-1">
                  <Input
                    id="blueprint"
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png"
                    onChange={(e) => setBlueprintFile(e.target.files[0])}
                    className="file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                  />
                  <p className="text-sm text-gray-600 mt-1">
                    Optional: Laden Sie Baupläne, Skizzen oder Fotos hoch (max. 10 MB)
                  </p>
                </div>
              </div>

              {/* Submit Button */}
              <div className="pt-6">
                <Button 
                  type="submit" 
                  className="w-full bg-green-700 hover:bg-green-800 py-3 text-lg"
                  disabled={isSubmitting}
                >
                  {isSubmitting ? (
                    'Anfrage wird gesendet...'
                  ) : (
                    <>
                      <Upload className="w-5 h-5 mr-2" />
                      Kostenlose Angebotsanfrage senden
                    </>
                  )}
                </Button>
              </div>

              <div className="text-sm text-gray-600 text-center">
                * Pflichtfelder. Ihre Daten werden vertraulich behandelt und nicht an Dritte weitergegeben.
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  );
};

export default QuotePage;