import { useNavigate } from "react-router-dom";
import { Separator } from "./ui/separator";
import { Facebook, Instagram, Linkedin } from "lucide-react";

const Footer = () => {
  const navigate = useNavigate();

  return (
    <footer className="bg-gray-900 text-white py-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <h3 className="text-2xl font-bold mb-4">Hohmann Bau</h3>
            <p className="text-gray-400 mb-4">Ihr zuverlässiger Partner für alle Bauvorhaben</p>
            <p className="text-sm text-gray-500">
              © 2024 Hohmann Bau GmbH. Alle Rechte vorbehalten.
            </p>
          </div>
          
          <div>
            <h4 className="text-lg font-semibold mb-4">Navigation</h4>
            <div className="space-y-2 text-gray-400">
              <button onClick={() => navigate('/')} className="block hover:text-white transition-colors">
                Home
              </button>
              <button onClick={() => navigate('/leistungen')} className="block hover:text-white transition-colors">
                Leistungen
              </button>
              <button onClick={() => navigate('/projekte')} className="block hover:text-white transition-colors">
                Projekte
              </button>
              <button onClick={() => navigate('/team')} className="block hover:text-white transition-colors">
                Team
              </button>
              <button onClick={() => navigate('/kontakt')} className="block hover:text-white transition-colors">
                Kontakt
              </button>
            </div>
          </div>
          
          <div>
            <h4 className="text-lg font-semibold mb-4">Services</h4>
            <div className="space-y-2 text-gray-400">
              <button onClick={() => navigate('/karriere')} className="block hover:text-white transition-colors">
                Karriere
              </button>
              <button onClick={() => navigate('/angebot')} className="block hover:text-white transition-colors">
                Angebot anfordern
              </button>
              <button onClick={() => navigate('/admin')} className="block hover:text-white transition-colors">
                Admin-Panel
              </button>
              <p>Notdienst 24/7</p>
              <p>Kostenlose Beratung</p>
            </div>
          </div>
          
          <div>
            <h4 className="text-lg font-semibold mb-4">Kontakt</h4>
            <div className="space-y-2 text-gray-400">
              <p>Bahnhofstraße 123</p>
              <p>12345 Musterstadt</p>
              <p>+49 123 456 789</p>
              <p>info@hohmann-bau.de</p>
            </div>
            <div className="flex space-x-4 mt-4">
              <Facebook className="w-6 h-6 text-gray-400 hover:text-white cursor-pointer transition-colors" />
              <Instagram className="w-6 h-6 text-gray-400 hover:text-white cursor-pointer transition-colors" />
              <Linkedin className="w-6 h-6 text-gray-400 hover:text-white cursor-pointer transition-colors" />
            </div>
          </div>
        </div>
        
        <Separator className="my-8 bg-gray-700" />
        
        <div className="text-center text-gray-400 text-sm">
          <div className="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-8">
            <span>Impressum</span>
            <span>Datenschutz</span>
            <span>AGB</span>
            <span>Sitemap</span>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;