<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {

        $table->id();

        $table->foreignId('product_id')
              ->constrained()
              ->onDelete('cascade');

        $table->string('nickname');

        $table->string('user_id_game');

        $table->integer('total');

        $table->enum('status', [
            'pending',
            'success',
            'failed'
        ])->default('pending');

        $table->timestamps();

    });
}

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
