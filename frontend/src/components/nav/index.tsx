import { useContext, useState } from "react";
import { NavProps } from "../../interface/navInterface";
import { IoMenu } from "react-icons/io5";
import { IoClose } from "react-icons/io5";
import { IoBookmarks } from "react-icons/io5";
import { useNavigate } from "react-router-dom";
import { AuthContext } from "../../context/AuthContext";

const Nav: React.FC<NavProps> = () => {
  const navigate = useNavigate();
  const { logout } = useContext(AuthContext);
  const [menuClass, setMenuClass] = useState("");
  const [iconName, setIconName] = useState("menu");
  const token = localStorage.getItem('authToken');

  const toggleMenu = () => {
    setIconName(iconName === "menu" ? "close" : "menu");
    setMenuClass(menuClass ? "" : "top-[7%]");
  };

  const logOut = () => {
    logout();
    navigate("/entrar"); // Redireciona para a home após login bem-sucedido
  };

  const login = () => {
    navigate("/entrar"); // Redireciona para a home após login bem-sucedido
  };

  return (
    <nav className="flex justify-between items-center w-[92%] mx-auto py-3">
      <div>
        <IoBookmarks className="cursor-pointer" size={36} color="#fbc2eb" />
      </div>
      <div
        className={`nav-links duration-500 md:static absolute bg-white md:min-h-fit min-h-[100vh] left-0 top-[-100%] md:w-auto z-10 w-full flex items-center px-5 ${menuClass}`}>
        <ul className="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
          <li>
            <a className="hover:text-gray-500" href="#">
              Dicionario
            </a>
          </li>
        </ul>
      </div>
      <div className="flex items-center gap-6">
        <button className="bg-[#a6c1ee] text-white px-5 py-2 rounded-full hover:bg-[#87acec]"
            onClick={ !!token ? logOut : login}>
          { !!token ? "Sair" : "Entrar"}
        </button>
        <div>
          {iconName === "menu" ? (
            <IoMenu
              className="cursor-pointer md:hidden"
              onClick={toggleMenu}
              size={32}
            />
          ) : (
            <IoClose
              className="cursor-pointer md:hidden"
              onClick={toggleMenu}
              size={32}
            />
          )}
        </div>
      </div>
    </nav>
  );
};

export default Nav;
