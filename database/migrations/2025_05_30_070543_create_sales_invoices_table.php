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
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->date('tanggal');
            $table->date('jatuh_tempo');
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_orders');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->enum('metode_pembayaran', ['tunai', 'kredit']);
            $table->decimal('total_harga', 15, 2);
            $table->decimal('total_diskon', 15, 2)->default(0);
            $table->decimal('total_bayar', 15, 2);
            $table->text('keterangan')->nullable();
            $table->string('status_cetak')->default('N');
            $table->timestamp('status_cetak_date')->nullable();
            $table->string('status_pembayaran')->default('N');
            $table->timestamp('status_pembayaran_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoices');
    }
};
