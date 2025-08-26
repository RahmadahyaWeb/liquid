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
        Schema::create('fifo_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_invoice_detail_id')->constrained('sales_invoice_details')->onDelete('cascade');
            $table->foreignId('fifo_layer_id')->constrained('fifo_layers')->onDelete('restrict');

            $table->unsignedInteger('qty_used'); // jumlah unit yang diambil dari layer ini
            $table->decimal('harga_modal_per_unit', 16, 2);
            $table->decimal('total_harga_modal', 18, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fifo_usages');
    }
};
