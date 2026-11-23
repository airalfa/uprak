<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // contoh: #KS007
            $table->string('service')->default('Dine-in'); // Dine-in, Takeaway, Delivery, Catering
            $table->string('customer');
            $table->string('phone')->nullable();
            $table->text('items');
            $table->string('payment')->default('QRIS');
            $table->string('total');
            $table->string('status')->default('Menunggu');
            $table->text('note')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};