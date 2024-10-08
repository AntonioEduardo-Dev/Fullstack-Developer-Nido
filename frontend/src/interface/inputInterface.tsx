export interface InputProps {
  id: string;
  type: string;
  placeholder: string;
  value?: string;
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
  icon: React.ReactNode | null;
}
