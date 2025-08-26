<div>
    <div class="w-full bg-white rounded-lg shadow-sm p-4 md:p-6">
        <div class="flex justify-between">
            <div>
                <h5 class="leading-none text-3xl font-bold text-gray-900 pb-2">
                    Rp {{ number_format(array_sum($chartData['series'][0]['data'] ?? []), 0, ',', '.') }}
                </h5>
                <p class="text-base font-normal text-gray-500">Penjualan {{ $range }} hari terakhir</p>
            </div>
            <div class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 text-center">
                @if ($growthPercentage === null)
                    <span class="text-green-500">New ↑</span>
                @else
                    <span class="{{ $growthPercentage >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        {{ round($growthPercentage, 2) }}% {{ $growthPercentage >= 0 ? '↑' : '↓' }}
                    </span>
                @endif
            </div>
        </div>

        <div wire:ignore id="penjualanChart"></div>

        <div class="grid grid-cols-1 items-center border-gray-200 border-t justify-between">
            <div class="flex justify-between items-center pt-5">
                <select wire:model.change="range" class="text-sm border-gray-300 rounded focus:ring focus:ring-blue-200"
                    name="range">
                    <option value="1">Today</option>
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                </select>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>

        <script>
            var options = {
                chart: {
                    type: "area",
                    fontFamily: "Inter, sans-serif",
                    dropShadow: {
                        enabled: false,
                    },
                    toolbar: {
                        show: false,
                    },
                },
                tooltip: {
                    enabled: true,
                    x: {
                        show: false,
                    },
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        opacityFrom: 0.55,
                        opacityTo: 0,
                        shade: "#1C64F2",
                        gradientToColors: ["#1C64F2"],
                    },
                },
                dataLabels: {
                    enabled: false
                },
                series: @json($chartData['series']),
                noData: {
                    text: 'Loading...'
                },
                xaxis: {
                    categories: @json($chartData['categories']),
                    labels: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                },
                yaxis: {
                    show: false,
                },
                grid: {
                    show: true,
                    strokeDashArray: 4,
                    padding: {
                        left: 2,
                        right: 2,
                        top: 0
                    },
                },
            }

            window['penjualanChart'] = new ApexCharts(
                document.querySelector("#penjualanChart"),
                options
            );

            window['penjualanChart'].render();

            function loadChartData(range) {
                axios.get('/chart/penjualan', {
                    params: {
                        range: range
                    }
                }).then(function(response) {
                    window['penjualanChart'].updateOptions({
                        series: response.data.series,
                        xaxis: {
                            categories: response.data.categories
                        }

                    });
                }).catch(function(error) {
                    // console.error("Gagal load data chart:", error);
                });
            }

            document.querySelector('select[name="range"]').addEventListener('change', function(e) {
                loadChartData(e.target.value);
            });
        </script>
    @endpush
</div>
