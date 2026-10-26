<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_id')
                ->constrained('payments')
                ->restrictOnDelete();

            $table->unsignedBigInteger('amount');

            $table->date('transaction_date');

            $table->string('payment_method', 50)
                ->default('Tunai');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['payment_id', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};