// src/utils/apiUtils.js
import api from '../services/api';

const apiUtils = async (url : string, method : string, body : {}) => {
  try {
    let response;
    
    switch (method.toLowerCase()) {
      case 'get':
        response = await api.get(url, { params: body });
        break;
      case 'post':
        response = await api.post(url, body);
        break;
      case 'put':
        response = await api.put(url, body);
        break;
      case 'delete':
        response = await api.delete(url);
        break;
      default:
        throw new Error('Método não suportado');
    }

    return response.data;
  } catch (error : any) {
    console.error('Erro na chamada da API:', error.message);
    throw error; // Re-throw para que você possa tratar no componente
  }
};

export default apiUtils;
