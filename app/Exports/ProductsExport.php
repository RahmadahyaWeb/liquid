<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    protected $categoryId;

    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    public function collection()
    {
        $query = Product::with(['prices', 'category']);

        if ($this->categoryId) {
            $query->where('product_category_id', $this->categoryId);
        }

        $products = $query->get();

        return $products->map(function ($product) {
            $b2bPrice = $product->prices->firstWhere('customer_type', 'B2B')?->harga_jual ?? '-';
            $b2cPrice = $product->prices->firstWhere('customer_type', 'B2C')?->harga_jual ?? '-';

            return [
                'Kode Produk'   => $product->kode_produk,
                'Nama Produk'   => $product->nama_produk,
                'Nama Kategori' => $product->category->nama_kategori ?? '-',
                'Harga B2B'     => $b2bPrice,
                'Harga B2C'     => $b2cPrice,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Produk',
            'Nama Produk',
            'Nama Kategori',
            'Harga B2B',
            'Harga B2C',
        ];
    }
}
