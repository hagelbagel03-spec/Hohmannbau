import { useState, useEffect } from "react";
import axios from "axios";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Button } from "./ui/button";
import { Input } from "./ui/input";
import { Textarea } from "./ui/textarea";
import { Label } from "./ui/label";
import { MapPin, Phone, Mail, Clock, Send, CheckCircle } from "lucide-react";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const ContactPage = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    message: ''
  });
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isSubmitted, setIsSubmitted] = useState(false);
  const [contactInfo, setContactInfo] = useState({
    address: "Bahnhofstraße 123, 12345 Musterstadt",
    phone: "+49 123 456 789",
    email: "info@hohmann-bau.de",
    opening_hours: "Mo-Fr: 08:00-17:00 Uhr"
  });
  const [pageContent, setPageContent] = useState({
    title: "Kontakt",
    subtitle: "Lassen Sie uns über Ihr Projekt sprechen",
    description: "Haben Sie Fragen zu unseren Leistungen oder möchten Sie ein Projekt mit uns besprechen? Wir freuen uns auf Ihre Nachricht und melden uns schnellstmöglich bei Ihnen."
  });

  useEffect(() => {
    fetchContactInfo();
    fetchPageContent();
  }, []);

  const fetchContactInfo = async () => {
    try {
      const response = await axios.get(`${API}/contact-info`);
      if (response.data) {
        setContactInfo(response.data);
      }
    } catch (error) {
      console.log('Using default contact info');
    }
  };

  const fetchPageContent = async () => {
    try {
      const response = await axios.get(`${API}/content/contact`);
      if (response.data) {
        setPageContent(response.data);
      }
    } catch (error) {
      console.log('Using default contact content');
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setIsSubmitting(true);

    try {
      await axios.post(`${API}/contact`, formData);
      setIsSubmitted(true);
      setFormData({ name: '', email: '', message: '' });
    } catch (error) {
      alert('Fehler beim Senden der Nachricht. Bitte versuchen Sie es später erneut.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const resetForm = () => {
    setIsSubmitted(false);
    setFormData({ name: '', email: '', message: '' });
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

      {/* Contact Content */}
      <section className="py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {/* Contact Form */}
            <div>
              <Card className="h-full">
                <CardHeader>
                  <CardTitle className="text-2xl">
                    {isSubmitted ? 'Nachricht gesendet!' : 'Kontaktformular'}
                  </CardTitle>
                  <CardDescription>
                    {isSubmitted 
                      ? 'Vielen Dank für Ihre Nachricht. Wir melden uns innerhalb von 24 Stunden bei Ihnen.'
                      : 'Schreiben Sie uns eine Nachricht und wir melden uns umgehend bei Ihnen.'
                    }
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  {isSubmitted ? (
                    <div className="text-center py-8">
                      <CheckCircle className="w-16 h-16 text-green-600 mx-auto mb-4" />
                      <p className="text-lg text-gray-700 mb-6">
                        Ihre Nachricht wurde erfolgreich gesendet!
                      </p>
                      <Button onClick={resetForm} variant="outline">
                        Neue Nachricht senden
                      </Button>
                    </div>
                  ) : (
                    <form onSubmit={handleSubmit} className="space-y-6">
                      <div>
                        <Label htmlFor="name">Name *</Label>
                        <Input
                          id="name"
                          type="text"
                          value={formData.name}
                          onChange={(e) => setFormData({...formData, name: e.target.value})}
                          required
                          placeholder="Ihr vollständiger Name"
                        />
                      </div>
                      <div>
                        <Label htmlFor="email">E-Mail *</Label>
                        <Input
                          id="email"
                          type="email"
                          value={formData.email}
                          onChange={(e) => setFormData({...formData, email: e.target.value})}
                          required
                          placeholder="ihre.email@beispiel.de"
                        />
                      </div>
                      <div>
                        <Label htmlFor="message">Nachricht *</Label>
                        <Textarea
                          id="message"
                          value={formData.message}
                          onChange={(e) => setFormData({...formData, message: e.target.value})}
                          rows={6}
                          required
                          placeholder="Beschreiben Sie Ihr Anliegen oder Projekt so detailliert wie möglich..."
                        />
                      </div>
                      <Button 
                        type="submit" 
                        className="w-full bg-green-700 hover:bg-green-800 py-3 text-lg"
                        disabled={isSubmitting}
                      >
                        {isSubmitting ? (
                          'Wird gesendet...'
                        ) : (
                          <>
                            <Send className="w-5 h-5 mr-2" />
                            Nachricht senden
                          </>
                        )}
                      </Button>
                      <p className="text-sm text-gray-600 text-center">
                        * Pflichtfelder. Ihre Daten werden vertraulich behandelt.
                      </p>
                    </form>
                  )}
                </CardContent>
              </Card>
            </div>

            {/* Contact Information */}
            <div className="space-y-6">
              <Card>
                <CardHeader>
                  <CardTitle className="text-2xl">Kontaktinformationen</CardTitle>
                  <CardDescription>
                    So erreichen Sie uns direkt
                  </CardDescription>
                </CardHeader>
                <CardContent className="space-y-6">
                  <div className="flex items-start space-x-4">
                    <MapPin className="w-6 h-6 text-green-700 mt-1" />
                    <div>
                      <h3 className="font-semibold text-lg">Adresse</h3>
                      <p className="text-gray-600">{contactInfo.address}</p>
                    </div>
                  </div>
                  
                  <div className="flex items-start space-x-4">
                    <Phone className="w-6 h-6 text-green-700 mt-1" />
                    <div>
                      <h3 className="font-semibold text-lg">Telefon</h3>
                      <a href={`tel:${contactInfo.phone}`} className="text-green-700 hover:underline">
                        {contactInfo.phone}
                      </a>
                    </div>
                  </div>
                  
                  <div className="flex items-start space-x-4">
                    <Mail className="w-6 h-6 text-green-700 mt-1" />
                    <div>
                      <h3 className="font-semibold text-lg">E-Mail</h3>
                      <a href={`mailto:${contactInfo.email}`} className="text-green-700 hover:underline">
                        {contactInfo.email}
                      </a>
                    </div>
                  </div>
                  
                  <div className="flex items-start space-x-4">
                    <Clock className="w-6 h-6 text-green-700 mt-1" />
                    <div>
                      <h3 className="font-semibold text-lg">Öffnungszeiten</h3>
                      <p className="text-gray-600">{contactInfo.opening_hours}</p>
                      <p className="text-sm text-gray-500 mt-1">
                        Termine nach Vereinbarung auch außerhalb der Öffnungszeiten möglich
                      </p>
                    </div>
                  </div>
                </CardContent>
              </Card>

              {/* Map Placeholder */}
              <Card>
                <CardHeader>
                  <CardTitle>Standort</CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                    <div className="text-center">
                      <MapPin className="w-12 h-12 text-gray-400 mx-auto mb-2" />
                      <p className="text-gray-500">Google Maps Integration</p>
                      <p className="text-sm text-gray-400 mt-1">
                        Interaktive Karte folgt in Kürze
                      </p>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </section>

      {/* Additional Contact Options */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl font-bold text-gray-900 mb-6">Weitere Kontaktmöglichkeiten</h2>
            <p className="text-xl text-gray-600">Wählen Sie den für Sie passenden Weg</p>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <Card className="text-center hover:shadow-lg transition-shadow duration-300">
              <CardHeader>
                <div className="text-4xl mb-4">📞</div>
                <CardTitle>Sofortberatung</CardTitle>
              </CardHeader>
              <CardContent>
                <CardDescription className="mb-4">
                  Für dringende Fragen oder eine schnelle Erstberatung rufen Sie uns direkt an.
                </CardDescription>
                <Button variant="outline" className="w-full">
                  Jetzt anrufen
                </Button>
              </CardContent>
            </Card>
            
            <Card className="text-center hover:shadow-lg transition-shadow duration-300">
              <CardHeader>
                <div className="text-4xl mb-4">💰</div>
                <CardTitle>Kostenvoranschlag</CardTitle>
              </CardHeader>
              <CardContent>
                <CardDescription className="mb-4">
                  Erhalten Sie ein detailliertes und kostenloses Angebot für Ihr Bauvorhaben.
                </CardDescription>
                <Button className="w-full bg-green-700 hover:bg-green-800">
                  Angebot anfordern
                </Button>
              </CardContent>
            </Card>
            
            <Card className="text-center hover:shadow-lg transition-shadow duration-300">
              <CardHeader>
                <div className="text-4xl mb-4">🏠</div>
                <CardTitle>Vor-Ort-Termin</CardTitle>
              </CardHeader>
              <CardContent>
                <CardDescription className="mb-4">
                  Vereinbaren Sie einen unverbindlichen Beratungstermin vor Ort.
                </CardDescription>
                <Button variant="outline" className="w-full">
                  Termin vereinbaren
                </Button>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>
    </div>
  );
};

export default ContactPage;