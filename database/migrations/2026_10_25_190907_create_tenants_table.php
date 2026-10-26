<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('phone', 25);
            $table->text('address')->nullable();

            // Kamar yang ditempati penghuni
            $table->foreignId('room_id')
                ->constrained('rooms')
                ->restrictOnDelete();

            $table->date('move_in_date');
            $table->string('status')->default('Aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
