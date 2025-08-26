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
        Schema::create('fifo_layers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('purchase_detail_id')->nullable()->constrained('purchase_details')->nullOnDelete();
            $table->integer('qty_total');
            $table->integer('qty_sisa');
            $table->decimal('harga_modal', 18, 2);
            $table->date('tanggal_masuk');
            $table->enum('source', ['STOCK AWAL', 'PEMBELIAN'])->default('PEMBELIAN');
            $table->enum('status', ['aktif', 'terkunci'])->default('aktif');
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fifo_layers');
    }
};
