import React from 'react';
import { Navigate } from 'react-router-dom';

interface ProtectedRouteProps {
  children: React.ReactNode;
  isAuthenticated: boolean; // ou qualquer outro tipo que você precise
}

const ProtectedRoute: React.FC<ProtectedRouteProps> = ({ children, isAuthenticated }) => {
  if (!isAuthenticated) {
    return <Navigate to="/entrar" replace />;
  }
  return <>{children}</>;
};

export default ProtectedRoute;
