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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
          $table->id();
        $table->string('sitename');
        $table->text('site_description')->nullable();
        $table->string('call_us')->nullable();
        $table->string('email_us')->nullable();
        $table->string('visit_us')->nullable();
        $table->string('instagram')->nullable();
        $table->string('facebook')->nullable();
        $table->string('x')->nullable();
        $table->timestamps();
    }
};
