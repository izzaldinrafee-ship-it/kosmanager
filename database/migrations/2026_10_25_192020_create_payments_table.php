<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Penghuni yang ditagih
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            // Periode tagihan
            $table->unsignedTinyInteger('billing_month');
            $table->unsignedSmallInteger('billing_year');

            // Nominal tagihan dan pembayaran
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('paid_amount')->default(0);

            $table->date('due_date');
            $table->string('status')->default('Belum Bayar');
            $table->date('paid_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Satu penghuni hanya memiliki satu tagihan per bulan
            $table->unique(
                ['tenant_id', 'billing_month', 'billing_year'],
                'payments_tenant_period_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
