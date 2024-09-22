import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8080/',
  responseType: 'json',
  withCredentials: false,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Interceptor para adicionar o token JWT a cada requisição
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('authToken');
    const expiration = localStorage.getItem('tokenExpiration');

    // Verifica se o token está expirado
    if (expiration && new Date().getTime() > parseInt(expiration, 10)) {
      localStorage.removeItem('authToken');
      localStorage.removeItem('tokenExpiration');

      return Promise.reject(new Error('Credenciais expiradas.'));
    }

    if (token) {
      config.headers.Authorization = `${token}`;
    }

    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default api;
