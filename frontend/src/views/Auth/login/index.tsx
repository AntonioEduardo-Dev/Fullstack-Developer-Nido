import { useState, useContext, useEffect } from "react";
import Input from "../../../components/input";
import Button from "../../../components/button";
import { IoMail, IoLockClosed } from "react-icons/io5";
import { AuthContext } from "../../../context/AuthContext";
import { useNavigate } from "react-router-dom"; // Importe useNavigate

const Login = () => {
  const navigate = useNavigate();
  const { login } = useContext(AuthContext);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const token = localStorage.getItem('authToken');

  useEffect(() => {
    if(!!token){
      navigate("/");
    }
  }, []);

  const onSubmit = async () => {
    try {
      if (!email || !password) {
        alert("Por favor, preencha todos os campos.");
        return;
      }

      // Aqui, chama a função de login do contexto, passando os dados
      await login(email, password);
      navigate("/"); // Redireciona para a home após login bem-sucedido
    } catch (error) {
      console.log(error);
      alert("Erro ao tentar fazer login");
    }
  };

  return (
    <div className="h-[90vh] flex justify-center items-center flex-1 z-0">
      <div className="md:w-[500px] w-full md:h-auto h-full bg-white flex flex-col md:justify-center justify-start items-center rounded-none md:rounded-2xl py-12 z-0">
        <span className="font-semibold text-3xl mb-6">Login</span>
        <Input
          id="email"
          placeholder="Digite seu email"
          icon={<IoMail className="w-5 h-5 text-gray-500" />}
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)} // Atualiza o estado do email
        />
        <Input
          id="password"
          placeholder="Digite sua senha"
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
