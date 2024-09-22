import { Routes, Route, BrowserRouter as Router } from 'react-router-dom';
import Login from '../views/Auth/login';
import Register from '../views/Auth/register';
import Home from '../views/home';
import ProtectedRoute from './ProtectedRoute';
import LayoutDefault from '../layout/_layout';

const AppRoutes = () => {
  const token = localStorage.getItem('authToken');
  return (
    <Router>
      <LayoutDefault>
        <Routes>
          <Route path="/entrar" element={<Login />} />
          <Route path="/cadastre-se" element={<Register />} />
          <Route 
            path="/" 
            element={
              <ProtectedRoute isAuthenticated={!!token}>
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
