//User model
export interface IUser {
  id: number;
  first_name: string;
  last_name: string;
  full_name: string;
  email: string;
  phone?: string;
  role: "customer" | "editor" | "admin";
  avatar?: string;
  created_at: string;
}

//Request Payloads
export interface ILoginRequest {
  email: string;
  password: string;
}

export interface IRegisterRequest {
  first_name: string;
  last_name: string;
  email: string;
  password: string;
  password_confirmation: string;
  phone?: string;
  role?: "customer" | "editor";
}

//api Response

export interface IAuthResponse {
  success: boolean;
  message: string;
  user?: IUser;
  token?: string;
  token_type?: string;
  expires_in?: number;
  permissions?: string[];
  permissions_by_module?: Record<string, string[]>;
  errors?: Record<string, string[]>;
}
