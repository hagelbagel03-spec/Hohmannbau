import { useState, useEffect } from "react";
import axios from "axios";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Button } from "./ui/button";
import { Input } from "./ui/input";
import { Textarea } from "./ui/textarea";
import { Badge } from "./ui/badge";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "./ui/dialog";
import { 
  HelpCircle, 
  MessageCircle, 
  Phone, 
  Mail, 
  Search,
  ChevronRight,
  X
} from "lucide-react";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const HelpSupport = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [helpArticles, setHelpArticles] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');
  const [supportTicket, setSupportTicket] = useState({
    name: '',
    email: '',
    subject: '',
    message: ''
  });
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    if (isOpen) {
      fetchHelpArticles();
    }
  }, [isOpen]);

  const fetchHelpArticles = async () => {
    try {
      const response = await axios.get(`${API}/help-articles`);
      setHelpArticles(response.data);
    } catch (error) {
      // Fallback help articles
      setHelpArticles([
        {
          id: "1",
          title: "Wie kann ich ein Angebot anfordern?",
          content: "Besuchen Sie unsere Angebotsseite und füllen Sie das Formular aus. Sie können auch Baupläne hochladen.",
          category: "Angebote",
          is_active: true
        },
        {
          id: "2", 
          title: "Welche Unterlagen benötige ich für ein Bauvorhaben?",
          content: "Für ein Bauvorhaben benötigen Sie: Bauplan, Baugenehmigung, Grundbuchauszug und Finanzierungsnachweis.",
          category: "Planung",
          is_active: true
        },
        {
          id: "3",
          title: "Wie lange dauert ein typisches Bauvorhaben?",
          content: "Die Dauer hängt vom Projektumfang ab. Ein Einfamilienhaus benötigt ca. 6-12 Monate, gewerbliche Projekte entsprechend länger.",
          category: "Zeitplanung",
          is_active: true
        },
        {
          id: "4",
          title: "Bieten Sie Notdienste an?",
          content: "Ja, wir bieten 24/7 Notdienste für dringende Reparaturen und Sicherungsmaßnahmen an.",
          category: "Service",
          is_active: true
        }
      ]);
    }
  };

  const submitSupportTicket = async (e) => {
    e.preventDefault();
    setIsSubmitting(true);

    try {
      await axios.post(`${API}/support-tickets`, supportTicket);
      alert('Support-Anfrage erfolgreich gesendet! Wir melden uns innerhalb von 24 Stunden bei Ihnen.');
      setSupportTicket({ name: '', email: '', subject: '', message: '' });
    } catch (error) {
      alert('Fehler beim Senden der Support-Anfrage.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const filteredArticles = helpArticles.filter(article =>
    article.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
    article.content.toLowerCase().includes(searchTerm.toLowerCase()) ||
    article.category.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <>
      {/* Help Button - Fixed Position */}
      <div className="fixed bottom-6 left-6 z-50">
        <Button
          onClick={() => setIsOpen(true)}
          className="bg-green-700 hover:bg-green-800 text-white rounded-full w-14 h-14 shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center"
          title="Hilfe & Support"
        >
          <HelpCircle className="w-6 h-6" />
        </Button>
      </div>

      {/* Help Dialog */}
      <Dialog open={isOpen} onOpenChange={setIsOpen}>
        <DialogContent className="max-w-4xl max-h-[90vh] overflow-hidden">
          <DialogHeader>
            <DialogTitle className="text-2xl flex items-center">
              <HelpCircle className="w-6 h-6 mr-2 text-green-700" />
              Hilfe & Support
            </DialogTitle>
            <DialogDescription>
              Finden Sie schnell Antworten auf Ihre Fragen oder kontaktieren Sie unser Support-Team.
            </DialogDescription>
          </DialogHeader>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 overflow-y-auto max-h-[70vh]">
            {/* FAQ Section */}
            <div>
              <div className="mb-4">
                <h3 className="text-lg font-semibold mb-3">Häufig gestellte Fragen</h3>
                <div className="relative">
                  <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
                  <Input
                    placeholder="Fragen durchsuchen..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="pl-10"
                  />
                </div>
              </div>

              <div className="space-y-3 max-h-96 overflow-y-auto">
                {filteredArticles.map((article) => (
                  <Dialog key={article.id}>
                    <DialogTrigger asChild>
                      <Card className="cursor-pointer hover:shadow-md transition-shadow">
                        <CardHeader className="py-3">
                          <div className="flex justify-between items-start">
                            <div>
                              <Badge variant="secondary" className="mb-2">
                                {article.category}
                              </Badge>
                              <CardTitle className="text-sm">{article.title}</CardTitle>
                            </div>
                            <ChevronRight className="w-4 h-4 text-gray-400" />
                          </div>
                        </CardHeader>
                      </Card>
                    </DialogTrigger>
                    <DialogContent>
                      <DialogHeader>
                        <DialogTitle>{article.title}</DialogTitle>
                        <Badge variant="secondary" className="w-fit">
                          {article.category}
                        </Badge>
                      </DialogHeader>
                      <div className="mt-4">
                        <p className="text-gray-700 leading-relaxed">{article.content}</p>
                      </div>
                    </DialogContent>
                  </Dialog>
                ))}
                
                {filteredArticles.length === 0 && (
                  <div className="text-center py-8 text-gray-500">
                    Keine passenden Artikel gefunden.
                  </div>
                )}
              </div>
            </div>

            {/* Contact Support Section */}
            <div>
              <h3 className="text-lg font-semibold mb-4">Support kontaktieren</h3>
              
              {/* Quick Contact Options */}
              <div className="grid grid-cols-1 gap-3 mb-6">
                <Card className="cursor-pointer hover:shadow-md transition-shadow">
                  <CardContent className="p-4 flex items-center">
                    <Phone className="w-5 h-5 text-green-700 mr-3" />
                    <div>
                      <p className="font-medium">Telefon-Support</p>
                      <p className="text-sm text-gray-600">+49 123 456 789</p>
                    </div>
                  </CardContent>
                </Card>
                
                <Card className="cursor-pointer hover:shadow-md transition-shadow">
                  <CardContent className="p-4 flex items-center">
                    <Mail className="w-5 h-5 text-green-700 mr-3" />
                    <div>
                      <p className="font-medium">E-Mail Support</p>
                      <p className="text-sm text-gray-600">support@hohmann-bau.de</p>
                    </div>
                  </CardContent>
                </Card>
              </div>

              {/* Support Ticket Form */}
              <Card>
                <CardHeader>
                  <CardTitle className="text-base flex items-center">
                    <MessageCircle className="w-4 h-4 mr-2" />
                    Support-Ticket erstellen
                  </CardTitle>
                  <CardDescription>
                    Beschreiben Sie Ihr Anliegen und wir melden uns bei Ihnen.
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <form onSubmit={submitSupportTicket} className="space-y-4">
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                      <Input
                        placeholder="Ihr Name"
                        value={supportTicket.name}
                        onChange={(e) => setSupportTicket({...supportTicket, name: e.target.value})}
                        required
                      />
                      <Input
                        type="email"
                        placeholder="Ihre E-Mail"
                        value={supportTicket.email}
                        onChange={(e) => setSupportTicket({...supportTicket, email: e.target.value})}
                        required
                      />
                    </div>
                    <Input
                      placeholder="Betreff"
                      value={supportTicket.subject}
                      onChange={(e) => setSupportTicket({...supportTicket, subject: e.target.value})}
                      required
                    />
                    <Textarea
                      placeholder="Beschreiben Sie Ihr Problem oder Ihre Frage..."
                      value={supportTicket.message}
                      onChange={(e) => setSupportTicket({...supportTicket, message: e.target.value})}
                      rows={4}
                      required
                    />
                    <Button 
                      type="submit" 
                      className="w-full bg-green-700 hover:bg-green-800"
                      disabled={isSubmitting}
                    >
                      {isSubmitting ? 'Wird gesendet...' : 'Support-Ticket senden'}
                    </Button>
                  </form>
                </CardContent>
              </Card>
            </div>
          </div>
        </DialogContent>
      </Dialog>
    </>
  );
};

export default HelpSupport;