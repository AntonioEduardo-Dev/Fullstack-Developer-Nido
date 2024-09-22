import { InputProps } from "../../interface/inputInterface";

const Input: React.FC<InputProps> = ({ id, type, placeholder, icon, onChange }) => {
  return (
    <div className="relative mb-6 w-[80%] ">
      <div className="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
        {icon}
      </div>
      <input
        type={type}
        id={id}
        className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 selection:border-red-500 block w-full ps-10 p-2.5"
        placeholder={placeholder}
        onChange={onChange}
      />
    </div>
  );
};

export default Input;
