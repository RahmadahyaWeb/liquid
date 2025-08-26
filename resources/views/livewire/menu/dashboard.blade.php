<div>
    @if ($salesOrderDraftCount > 0)
        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
            role="alert">
            <span class="font-medium">Peringatan!</span> Terdapat {{ $salesOrderDraftCount }} draft sales order.
        </div>
    @endif

    <div class="grid lg:grid-cols-2 gap-4">

        {{-- <livewire:component.chart-penjualan /> --}}

        <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h5 class="mb-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Top 5 Produk Terlaris Berdasarkan Invoice
            </h5>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Kode Produk
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama Produk
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total Terjual
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($topProducts as $product)
                            <tr class="bg-white border-b border-gray-200">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $product->kode_produk }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $product->nama_produk }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $product->total_terjual }}
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h5 class="mb-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Top 5 Customer Berdasarkan Invoice
            </h5>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Kode Pelanggan
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama Pelanggan
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total Invoice
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($topCustomers as $customer)
                            <tr class="bg-white border-b border-gray-200">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $customer->kode_pelanggan }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $customer->nama_pelanggan }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $customer->total_invoice }}
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h5 class="mb-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Top 5 Produk yang Paling Sering Dibeli
            </h5>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Kode Produk
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama Produk
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Total Beli
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($topPurchases as $purchase)
                            <tr class="bg-white border-b border-gray-200">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $purchase->kode_produk }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $purchase->nama_produk }}
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $purchase->total_terbeli }}
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
