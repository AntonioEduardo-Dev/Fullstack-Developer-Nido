import React, { createContext, useState, ReactNode } from 'react';
import api from '../services/api';

export const AuthContext = createContext<any>(null);

interface AuthProviderProps {
  children: ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  const [user, setUser] = useState<any>(null);
  const [token, setToken] = useState<string | null>(null);

  const login = async (email: string, password: string): Promise<void> => {
    try {
      const response = await api.post('/auth/signin', { email, password });
      const { token, expiration } = response.data;

      localStorage.setItem('authToken', token);
      localStorage.setItem('tokenExpiration', expiration.toString());

      setUser(response.data.user);
      setToken(token);

      api.defaults.headers.common['Authorization'] = `${token}`;
    } catch (err : any) {
      console.error(err.message || 'Erro ao fazer login');
    }
  };

  const logout = () => {
    setUser(null);
    setToken(null);
    localStorage.removeItem('authToken');
    localStorage.removeItem('tokenExpiration');
    delete api.defaults.headers.common['Authorization'];
  };

  return (
    <AuthContext.Provider value={{ user, token, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
