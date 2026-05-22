<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Contoh: Promo Topup Murah
            $table->string('image'); // Nama file gambarnya
            $table->integer('status')->default(1); // 1 aktif, 0 nonaktif
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
