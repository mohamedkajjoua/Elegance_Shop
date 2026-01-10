export interface IProductImage {
  id: number;
  url: string;
  is_primary: boolean;
}

export interface IProductVariant {
  id: number;
  color: string;
  size: string;
  price: number;
  stock: number;
}

export interface IProduct {
  id: number;
  name: string;
  description: string;
  short_description: string;
  price: number;
  final_price: number;
  discount: number;
  is_featured: boolean;
  is_active: boolean;
  category?: { id: number; name: string };
  brand?: { id: number; name: string };
  variants: IProductVariant[];
  images: IProductImage[];
}

export interface IProductParams {
  page?: number;
  limit?: number;
  q?: string;
  category_id?: number;
  brand_id?: number;
  min_price?: number;
  max_price?: number;
  sort_by?: "newest" | "price_low" | "price_high";
}

// send data to server
export interface IProductPayload {
  name: string;
  description: string;
  short_description: string;
  price: number;
  discount: number;
  shipping: number;
  category_id: number;
  brand_id: number;
  is_featured: boolean;
  is_active: boolean;
  variants: IProductVariant[];
  images?: File[];
  deleted_images?: number[];
}
