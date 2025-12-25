<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('products', function (Blueprint $table) {
        $table->id();

        $table->foreignId('category_id')
              ->nullable()
              ->constrained()
              ->nullOnDelete();

        $table->foreignId('brand_id')
              ->nullable()
              ->constrained()
              ->nullOnDelete();

        $table->string('name', 200);
        $table->text('description')->nullable();
        $table->string('short_description')->nullable();
        $table->decimal('price', 10, 2);
        $table->decimal('discount', 10, 2)->default(0);
        $table->decimal('final_price', 10, 2)->nullable();
        $table->boolean('is_featured')->default(false);
        $table->boolean('is_active')->default(true);
        $table->integer('views_count')->default(0);
        $table->integer('sales_count')->default(0);
        $table->decimal('shipping', 10, 2)->default(0);

        $table->timestamps();
        $table->softDeletes();
});



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
