import Input from "../../../components/input";
import Button from "../../../components/button";

import { IoMail } from "react-icons/io5";
import { IoLockClosed } from "react-icons/io5";
import { IoPerson } from "react-icons/io5";
import { useNavigate } from "react-router-dom";
import { useState } from "react";
import apiUtils from "../../../utils/apiUtils";
import api from "../../../services/api";

const Register = () => {
  const navigate = useNavigate();
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [errorMessage, setErrorMessage] = useState('');


  const validateFields = () => {
    if (!name || !email || !password) {
      setErrorMessage("Por favor, preencha todos os campos.");
      return false;
    }
    setErrorMessage('');
    return true;
  };

  const onSubmit = async () => {
    if (!validateFields()) return;

    try {
      const response = await apiUtils('/auth/signup', 'post', { email, password, name });

      if (response?.token) {
        localStorage.setItem('authToken', response.token);
        api.defaults.headers.common['Authorization'] = response.token;
        navigate("/");
      }
    } catch (error: any) {
      const generalMessage = error?.response?.data?.message || "";
      const fullMessage = `Erro ao tentar realizar o registro. ${generalMessage}`;
    
      setErrorMessage(fullMessage);
    }
  };
  
  return (
    <div className="h-[90vh] flex justify-center items-center flex-1 z-0">
      <div className="md:w-[500px] w-full md:h-auto h-full bg-white flex flex-col md:justify-center justify-start items-center rounded-none md:rounded-2xl py-12 Z-0">
        <span className="font-semibold text-3xl mb-6">Cadastre-se</span>
        {errorMessage && <p className="error mb-6">{errorMessage}</p>}
        <Input
          id="name"
          placeholder="Digite seu nome"
          icon={<IoPerson className="w-5 h-5 text-gray-500" />}
          type="text"
          value={name}
          onChange={(e) => setName(e.target.value)}
        />
        <Input
          id="email"
          placeholder="Digite seu email"
          icon={<IoMail className="w-5 h-5 text-gray-500" />}
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
        <Input
          id="password"
          placeholder="Digite sua senha"
          icon={<IoLockClosed className="w-5 h-5 text-gray-500" />}
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />
        <Button title="Cadastrar" onClick={onSubmit} />
      </div>
    </div>
  );
};

export default Register;
