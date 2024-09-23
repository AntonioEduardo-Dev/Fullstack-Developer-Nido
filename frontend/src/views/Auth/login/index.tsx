import { useState, useContext } from "react";
import Input from "../../../components/input";
import Button from "../../../components/button";
import { IoMail, IoLockClosed } from "react-icons/io5";
import { AuthContext } from "../../../context/AuthContext";
import { AxiosError } from "axios";

const Login = () => {
  const { login } = useContext(AuthContext);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [errorMessage, setErrorMessage] = useState<string[]>([]); 
  
  const validateFields = () => {
    if (!email || !password) {
      setErrorMessage(["Por favor, preencha todos os campos."]);
      return false;
    }
    setErrorMessage([]);
    return true;
  };

  const onSubmit = async () => {
    if (!validateFields()) return;

    try {
      // Aqui, chama a função de login do contexto, passando os dados
      await login(email, password);
      setTimeout(() => {
        window.location.href = "/";
      }, (1.5 * 1000) );
    } catch (error) {
      if (error instanceof AxiosError && error.response) {
        const errorResponse = error.response;
        
        const messageResponse = (errorResponse?.data?.message || errorResponse?.data?.error) || 'Erro desconhecido';
        setErrorMessage([messageResponse]);
      } else {
        setErrorMessage(["Erro ao tentar criar a conta"]);
      }
    }
  };

  return (
    <div className="h-[90vh] flex justify-center items-center flex-1 z-0">
      <div className="md:w-[500px] w-full md:h-auto h-full bg-white flex flex-col md:justify-center justify-start items-center rounded-none md:rounded-2xl py-12 z-0">
        <span className="font-semibold text-3xl mb-6">Entrar</span>
        {errorMessage.length > 0 && (
          <ul className="error mb-6 bg-indigo-400 py-2 px-3 md:rounded-2xl text-white">
            {errorMessage.map((error, index) => (
              <li key={index}>{error}</li>
            ))}
          </ul>
        )}
        <Input
          id="email"
          placeholder="Digite seu email*"
          icon={<IoMail className="w-5 h-5 text-gray-500" />}
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)} // Atualiza o estado do email
        />
        <Input
          id="password"
          placeholder="Digite sua senha*"
          icon={<IoLockClosed className="w-5 h-5 text-gray-500" />}
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)} // Atualiza o estado da senha
        />
        <Button title="Entrar" onClick={onSubmit} /> {/* Chama a função onSubmit */}
      </div>
    </div>
  );
};

export default Login;
