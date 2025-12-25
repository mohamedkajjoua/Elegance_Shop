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
           Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('addresse_id')->constrained() ->restrictOnDelete();
                $table->decimal('subtotal', 10, 2);
                $table->decimal('total_price', 10, 2);
                $table->enum('status', ['pending','processing','shipped','delivered','cancelled'])->default('pending');
                $table->string('payment_method');
                $table->enum('payment_status', ['pending','paid','failed','refunded'])->default('pending');;
                $table->timestamps();
                 $table->softDeletes();


});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
