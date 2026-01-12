import type { IAuthResponse, ILoginRequest, IRegisterRequest, IUser } from "../types/authTypes";

import api from "./api";

class AuthService {
  async register(data: IRegisterRequest): Promise<IAuthResponse> {
    try {
      const response = await api.post("/auth/register", data);

      if (response.data.success && response.data.token) {
        this.setSession(response.data);
      }

      return response.data;
    } catch (error: any) {
      return this.handleError(error, "Registration failed");
    }
  }

  async login(data: ILoginRequest): Promise<IAuthResponse> {
    try {
      const res = await api.post("/auth/login", data);
      if (res.data.success && res.data.token) {
        this.setSession(res.data);
      }
      return res.data;
    } catch (err: any) {
      return this.handleError(err, "login failed");
    }
  }

  async logout(): Promise<IAuthResponse> {
    try {
      const res = await api.post("/auth/logout");
      this.clearStorage();
      return res.data;
    } catch (err: any) {
      this.clearStorage();
      return {
        success: true,
        message: "Logged out locally",
      };
    }
  }

  async getCurrentUser(): Promise<IAuthResponse> {
    try {
      const response = await api.get("/auth/me");

      if (response.data.success && response.data.user) {
        localStorage.setItem("user", JSON.stringify(response.data.user));
        if (response.data.permissions) {
          localStorage.setItem("permissions", JSON.stringify(response.data.permissions));
        }
        if (response.data.permissions_by_module) {
          localStorage.setItem(
            "permissions_by_module",
            JSON.stringify(response.data.permissions_by_module)
          );
        }
      }

      return response.data;
    } catch (error: any) {
      return this.handleError(error, "Failed to fetch user");
    }
  }
  async getUserById(id: number): Promise<any> {
    const response = await api.get(`/auth/users/${id}`);
    return response.data;
  }

  async getAllUsers(page: number = 1, perPage: number = 10): Promise<any> {
    try {
      const response = await api.get("/auth/users", {
        params: {
          page: page,
          per_page: perPage,
        },
      });
      return response.data;
    } catch (error: any) {
      return this.handleError(error, "Failed to fetch users list");
    }
  }
  async delete(id: number) {
    return api.delete(`/auth/users/${id}`);
  }
  async updateUser(id: number, data: FormData | object) {
    const response = await api.post(`/auth/users/${id}`, data, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    return response.data;
  }
  //Token renewal
  async refreshToken(): Promise<IAuthResponse> {
    try {
      const response = await api.post("/auth/refresh");

      if (response.data.token) {
        localStorage.setItem("token", response.data.token);
      }

      return response.data;
    } catch (error: any) {
      return this.handleError(error, "Failed to refresh token");
    }
  }

  //Helper Methods
  private setSession(data: IAuthResponse) {
    if (data.token) localStorage.setItem("token", data.token);
    if (data.user) localStorage.setItem("user", JSON.stringify(data.user));
    if (data.permissions) localStorage.setItem("permissions", JSON.stringify(data.permissions));
    if (data.permissions_by_module)
      localStorage.setItem("permissions_by_module", JSON.stringify(data.permissions_by_module));
  }

  private clearStorage() {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    localStorage.removeItem("permissions");
    localStorage.removeItem("permissions_by_module");
  }

  private handleError(error: any, defaultMsg: string): IAuthResponse {
    return {
      success: false,
      message: error.response?.data?.message || defaultMsg,
      errors: error.response?.data?.errors || null,
    };
  }

  /**
   * Get token
   */
  getToken(): string | null {
    return localStorage.getItem("token");
  }

  /**
   * Get data user to stored
   */

  getStoredUser(): IUser | null {
    const user = localStorage.getItem("user");
    return user ? JSON.parse(user) : null;
  }

  /**
   * user is login are not
   */

  isLoggedIn(): boolean {
    return !!this.getToken();
  }
  /**
   * check permissions
   */

  hasPermission(permissionName: string): boolean {
    const permissionsStr = localStorage.getItem("permissions");

    if (!permissionsStr) return false;
    const permissions: string[] = JSON.parse(permissionsStr);
    return permissions.includes(permissionName);
  }

  getStoredPermissions(): string[] {
    const permissions = localStorage.getItem("permissions");
    return permissions ? JSON.parse(permissions) : [];
  }

  /**
   * get permissions by module
   */
  getStoredPermissionsByModule(): Record<string, string[]> {
    const permissions = localStorage.getItem("permissions_by_module");
    return permissions ? JSON.parse(permissions) : {};
  }
}

export default new AuthService();
