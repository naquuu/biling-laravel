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
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('party_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->date('due_date')->nullable();              // jatuh tempo (untuk hutang/cicil)
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);                  // total nominal
            $table->enum('payment_status', ['lunas', 'cicil', 'hutang'])->default('hutang');
            $table->enum('payment_method', ['cash', 'transfer'])->nullable();
            $table->decimal('paid_amount', 15, 2)->default(0); // jumlah yang sudah dibayar (untuk cicil)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};