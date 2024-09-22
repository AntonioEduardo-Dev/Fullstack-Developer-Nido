import { ButtonProps } from "../../interface/buttonInterface";

const Button: React.FC<ButtonProps> = ({ title, onClick, disabled }) => {
  return (
    <div className="relative mb-6 w-[80%]">
      <button
        type="submit"
        className="text-white w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 "
        onClick={onClick}
        disabled={disabled}>
        {title}
      </button>
    </div>
  );
};

export default Button;
