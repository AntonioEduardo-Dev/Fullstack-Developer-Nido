import { Routes, Route, BrowserRouter as Router } from 'react-router-dom';
import Login from '../views/Auth/login';
import Register from '../views/Auth/register';
import Home from '../views/home';
import ProtectedRoute from './ProtectedRoute';
import { useState } from 'react';
import LayoutDefault from '../layout/_layout';

const AppRoutes = () => {
  const [user, setUser] = useState(null); // Definindo o estado do usuário

  return (
    <Router>
      <LayoutDefault>
        <Routes>
          <Route path="/entrar" element={<Login />} />
          <Route path="/cadastre-se" element={<Register />} />
          <Route 
            path="/" 
            element={
              <ProtectedRoute isAuthenticated={!!user}>
                <Home />
              </ProtectedRoute>
            } 
          />
        </Routes>
      </LayoutDefault>
    </Router>
  );
};

export default AppRoutes;
