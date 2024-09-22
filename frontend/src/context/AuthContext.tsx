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
    const response = await api.post('/auth/signin', { email, password });
    const { token } = response.data;

    localStorage.setItem('authToken', token);

    setUser(response.data.user);
    setToken(token);

    api.defaults.headers.common['Authorization'] = `${token}`;
  };

  const logout = () => {
    setUser(null);
    setToken(null);
    localStorage.removeItem('authToken');
    delete api.defaults.headers.common['Authorization'];
  };

  return (
    <AuthContext.Provider value={{ user, token, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
