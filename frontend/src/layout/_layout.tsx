import React, { ReactNode } from "react";
import Nav from "../components/nav";

interface LayoutProps {
  children: ReactNode;
}

const LayoutDefault: React.FC<LayoutProps> = ({ children }) => {
  return (
    <>
      <header className="bg-white">
        <Nav />
      </header>
      {children}
    </>
  );
};

export default LayoutDefault;
