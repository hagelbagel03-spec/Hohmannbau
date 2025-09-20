import { BrowserRouter, Routes, Route } from "react-router-dom";
import Navigation from "./components/Navigation";
import Footer from "./components/Footer";
import HomePage from "./components/HomePage";
import ServicesPage from "./components/ServicesPage";
import ProjectsPage from "./components/ProjectsPage";
import TeamPage from "./components/TeamPage";
import ContactPage from "./components/ContactPage";
import AdminPanel from "./components/AdminPanel";
import CareerPage from "./components/CareerPage";
import QuotePage from "./components/QuotePage";
import "./App.css";

function App() {
  return (
    <div className="App">
      <BrowserRouter>
        <Routes>
          {/* Public Routes with Navigation and Footer */}
          <Route path="/" element={
            <>
              <Navigation />
              <HomePage />
              <Footer />
            </>
          } />
          <Route path="/leistungen" element={
            <>
              <Navigation />
              <ServicesPage />
              <Footer />
            </>
          } />
          <Route path="/projekte" element={
            <>
              <Navigation />
              <ProjectsPage />
              <Footer />
            </>
          } />
          <Route path="/team" element={
            <>
              <Navigation />
              <TeamPage />
              <Footer />
            </>
          } />
          <Route path="/kontakt" element={
            <>
              <Navigation />
              <ContactPage />
              <Footer />
            </>
          } />
          <Route path="/karriere" element={
            <>
              <Navigation />
              <CareerPage />
              <Footer />
            </>
          } />
          <Route path="/angebot" element={
            <>
              <Navigation />
              <QuotePage />
              <Footer />
            </>
          } />
          
          {/* Admin Route without Navigation and Footer */}
          <Route path="/admin" element={<AdminPanel />} />
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;