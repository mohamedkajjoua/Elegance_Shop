import type { IProduct, IProductParams, IProductPayload } from "@/types/ProductType";
import api from "./api";

class AdminProductService {
  // get all product
  async getAll(params: IProductParams) {
    return api.get("/products", { params });
  }
  //
  async getById(id: number) {
    return api.get<{ data: IProduct }>(`/products/${id}`);
  }
  //create product
  async create(payload: IProductPayload) {
    const FromData = this.createFormData(payload);

    return api.post("/products", FromData, {
      headers: { "Content-Type": "multipart/form-data" },
    });
  }
  //update
  async update(id: number, payload: IProductPayload) {
    const FromData = this.createFormData(payload);
    FromData.append("_method", "PUT");

    return api.post(`/products/${id}`, FromData, {
      headers: { "Content-Type": "multipart/form-data" },
    });
  }
  //delete
  async delete(id: number) {
    return api.delete(`/products/${id}`);
  }

  //active and des active products

  async toggleStatus(id: number) {
    return api.patch(`/products/${id}/toggle-status`);
  }

  //fromData

  private createFormData(payload: IProductPayload): FormData {
    const FromData = new FormData();

    FromData.append("name", payload.name);
    FromData.append("description", payload.description);
    FromData.append("short_description", payload.short_description);
    FromData.append("price", String(payload.price));
    FromData.append("discount", String(payload.discount || 0));
    FromData.append("shipping", String(payload.shipping || 0));
    FromData.append("category_id", String(payload.category_id));
    FromData.append("brand_id", String(payload.brand_id));
    FromData.append("is_featured", payload.is_featured ? "1" : "0");
    FromData.append("is_active", payload.is_active ? "1" : "0");

    //add variants to array
    if (payload.variants && payload.variants.length > 0) {
      payload.variants.forEach((variant, index) => {
        FromData.append(`variants[${index}][color]`, variant.color);
        FromData.append(`variants[${index}][size]`, variant.size);
        FromData.append(`variants[${index}][price]`, String(variant.price));
        FromData.append(`variants[${index}][stock]`, String(variant.stock));
        if (variant.id) {
          FromData.append(`variants[${index}][id]`, String(variant.id));
        }
      });
    }
    if (payload.deleted_images && payload.deleted_images.length > 0) {
      payload.deleted_images.forEach((id) => {
        FromData.append("deleted_images[]", String(id));
      });
    }
    // add image to file
    if (payload.images && payload.images.length > 0) {
      payload.images.forEach((file, index) => {
        FromData.append(`images[${index}]`, file);
      });
    }
    return FromData;
  }
}

export default new AdminProductService();
