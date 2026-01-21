<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductService
{

    public function createProduct(array $data): Product
    {
        try {
            DB::beginTransaction();

            // Calculate final price based on discount
            $data['final_price'] = $data['price'] - ($data['price'] * ($data['discount'] ?? 0) / 100);

            // Create the Product record
            $product = Product::create($data);

            // Create Variants if they exist
            if (!empty($data['variants'])) {
                $this->createVariants($product->id, $data['variants']);
            }

            // Upload Images if they exist
            if (!empty($data['images'])) {
                $this->uploadImages($product->id, $data['images']);
            }

            DB::commit();
            return $product;
        } catch (\Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }

    public function updateProduct($id, $data)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);


            $data['final_price'] = $data['price'] - ($data['price'] * ($data['discount'] ?? 0) / 100);


            $product->update($data);


            if (!empty($data['deleted_images'])) {
                $imagesToDelete = $product->images()->whereIn('id', $data['deleted_images'])->get();

                foreach ($imagesToDelete as $image) {

                    $relativePath = str_replace(url('/storage/'), '', $image->url);
                    $relativePath = str_replace('/storage/', '', $relativePath);


                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }


                    $image->delete();
                }
            }


            if (!empty($data['variants'])) {

                $product->variants()->delete();
                $this->createVariants($product->id, $data['variants']);
            }


            if (!empty($data['images'])) {
                $this->uploadImages($product->id, $data['images']);
            }

            DB::commit();


            return $product->load(['images', 'variants']);
        } catch (\Exception $err) {
            DB::rollBack();
            throw $err;
        }
    }

    //Activate/deactivate product
    public function toggleProductStatus(int $id): Product
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        return $product;
    }

    public function deleteProduct(int $id): bool
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);


            foreach ($product->images as $image) {
                if (Storage::exists($image->image_url)) {
                    Storage::delete($image->image_url);
                }
            }


            $product->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAllProducts($perPage = 10)
    {
        return Product::with([
            'category',
            'brand',
            'variants',
            'images',
        ])
            ->select([
                'id',
                'name',
                'description',
                'short_description',
                'price',
                'discount',
                'final_price',
                'category_id',
                'brand_id',
                'is_featured',
                'is_active',
                'views_count',
                'sales_count',
                'shipping',
                'created_at',
                'updated_at'
            ])
            ->withCount('reviews')


            ->addSelect([
                'reviews_avg_rating' => Review::selectRaw('avg(rating::numeric)')
                    ->whereColumn('product_id', 'products.id')
            ])

            ->latest()
            ->paginate($perPage);
    }

    public function getProductById(int $id): ?Product
    {
        $product = Product::with([
            'category',
            'brand',
            'variants.images',
            'images',
            'reviews.user'
        ])
            ->find($id);

        if ($product) {
            $product->increment('views_count');
        }

        return $product;
    }




    public function createVariants(int $productId, array $variants): void
    {
        foreach ($variants as $variant) {
            ProductVariant::create([
                'product_id' => $productId,
                'color'      => $variant['color'],
                'size'       => $variant['size'],
                'price'      => $variant['price'],
                'stock'      => $variant['stock'] ?? 0,
                'is_active'  => $variant['is_active'] ?? true,
            ]);
        }
    }

    /**
     * Update a specific variant.
     */
    public function updateVariant(int $variantId, array $data): ProductVariant
    {
        $variant = ProductVariant::findOrFail($variantId);
        $variant->update($data);
        return $variant;
    }

    /**
     * Delete a specific variant.
     */
    public function deleteVariant(int $variantId): bool
    {
        $variant = ProductVariant::findOrFail($variantId);
        $variant->delete();
        return true;
    }

    /**
     * Handle image uploading and storage.
     */
    public function uploadImages(int $productId, array $images): void
    {
        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                // Store file in the 'public' disk
                $path = $image->store("products/{$productId}", 'public');

                ProductImage::create([
                    'product_id' => $productId,
                    'image_url'  => $path,
                    'is_primary' => $index === 0, // Set the first image as primary
                    'sort_order' => $index,
                ]);
            }
        }
    }

    /**
     * Replace all product images with new ones.
     * WARNING: This deletes all old images from the server and database.
     */
    public function updateImage(int $productId, array $images): void
    {
        $product = Product::findOrFail($productId);

        // Loop through existing images to delete them
        foreach ($product->images as $image) {
            // Check if file exists in 'public' disk and delete it
            if (Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
            }

            // Delete the database record
            $image->delete();
        }

        // Upload the new images
        // FIXED: Call uploadImages instead of updateImage to avoid infinite loop
        $this->uploadImages($productId, $images);
    }

    /**
     * Delete a single image from storage and database.
     */
    public function deleteImage(int $imageId): bool
    {
        $image = ProductImage::findOrFail($imageId);

        // Delete file from storage
        if (Storage::disk('public')->exists($image->image_url)) {
            Storage::disk('public')->delete($image->image_url);
        }

        // Delete record from database
        $image->delete();

        return true;
    }

    /**
     * 
     */
    public function formatProductResponse(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'short_description' => $product->short_description,
            'price' => (float) $product->price,
            'final_price' => (float) $product->final_price,
            'shipping' => (float) $product->shipping,
            'discount' => (int) $product->discount,
            'is_active' => (bool) $product->is_active,
            'is_featured' => (bool) $product->is_featured,
            'category' => $product->category ? ['id' => $product->category->id, 'name' => $product->category->name] : null,
            'brand' => $product->brand ? ['id' => $product->brand->id, 'name' => $product->brand->name] : null,
            'variants' => $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'size' => $variant->size,
                    'color' => $variant->color,
                    'stock' => $variant->stock,
                    'price' => $variant->price,
                ];
            }),

            'images' => $product->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => asset('storage/' . $image->image_url),
                    'is_primary' => (bool) $image->is_primary,
                ];
            }),
            'created_at' => $product->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
