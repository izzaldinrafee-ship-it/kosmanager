
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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            // Informasi kamar
            $table->string('room_number')->unique();
            $table->string('room_type')->default('Standar');
            $table->unsignedBigInteger('price');
            $table->string('status')->default('Kosong');
            $table->text('description')->nullable();

            // Waktu pembuatan dan perubahan data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
