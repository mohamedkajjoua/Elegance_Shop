import type { IProduct, IProductParams } from "@/types/ProductType";
import api from "./api";

class ProductShopService {
  async getAll(params: IProductParams) {
    const cleanParams = Object.fromEntries(
      Object.entries(params).filter(([_, v]) => v != null && v !== "")
    );

    return api.get<{ data: IProduct[]; meta: any }>("/products/filter", { params: cleanParams });
  }

  async getById(id: number | string) {
    return api.get<{ data: IProduct }>(`/products/${id}`);
  }

  async getRelated(id: string) {
    return api.get(`/products/${id}/related`);
  }
  async search(params: any) {
    return api.get("products/search", { params });
  }

  async filters(params: any) {
    return api.get("products/filter", { params });
  }
}

export default new ProductShopService();
