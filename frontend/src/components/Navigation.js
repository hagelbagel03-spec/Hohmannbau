import { useNavigate, useLocation } from "react-router-dom";
import { Button } from "./ui/button";
import { Briefcase, Calculator } from "lucide-react";

const Navigation = () => {
  const navigate = useNavigate();
  const location = useLocation();

  const navItems = [
    { path: '/', label: 'Home' },
    { path: '/leistungen', label: 'Leistungen' },
    { path: '/projekte', label: 'Projekte' },
    { path: '/team', label: 'Team' },
    { path: '/kontakt', label: 'Kontakt' }
  ];

  const isActive = (path) => {
    if (path === '/' && location.pathname === '/') return true;
    if (path !== '/' && location.pathname.startsWith(path)) return true;
    return false;
  };

  return (
    <nav className="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-md z-50 border-b border-gray-200">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          <div 
            className="font-bold text-2xl text-green-800 cursor-pointer" 
            onClick={() => navigate('/')}
          >
            Hohmann Bau
          </div>
          <div className="hidden md:flex items-center space-x-8">
            {navItems.map((item) => (
              <button
                key={item.path}
                onClick={() => navigate(item.path)}
                className={`text-sm font-medium transition-colors hover:text-green-700 ${
                  isActive(item.path) ? 'text-green-700 border-b-2 border-green-700 pb-1' : 'text-gray-600'
                }`}
              >
                {item.label}
              </button>
            ))}
            <button 
              onClick={() => navigate('/karriere')}
              className={`text-sm font-medium transition-colors hover:text-green-700 flex items-center ${
                location.pathname === '/karriere' ? 'text-green-700' : 'text-gray-600'
              }`}
            >
              <Briefcase className="w-4 h-4 mr-1" />
              Karriere
            </button>
            <Button
              onClick={() => navigate('/angebot')}
              className="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center"
            >
              <Calculator className="w-4 h-4 mr-1" />
              Angebot anfordern
            </Button>
          </div>
          
          {/* Mobile Menu Button */}
          <div className="md:hidden">
            <Button
              variant="ghost"
              className="text-gray-600"
              onClick={() => {
                // TODO: Implement mobile menu
                console.log('Mobile menu not implemented yet');
              }}
            >
              ☰
            </Button>
          </div>
        </div>
      </div>
    </nav>
  );
};

export default Navigation;