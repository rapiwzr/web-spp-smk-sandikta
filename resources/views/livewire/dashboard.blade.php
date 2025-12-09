<div>
    {{-- 1. HEADER SIMPLE (Menggantikan Profile Card di Sidebar) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light-primary">
                <div class="card-body py-3 d-flex align-items-center">
                    <div class="avatar avatar-lg me-3">
                        <img src="{{ asset('compiled/jpg/1.jpg') }}" alt="Face 1">
                    </div>
                    <div>
                        <h5 class="font-bold mb-0">Selamat Datang, {{ auth()->user()->name }}</h5>
                        <p class="text-muted mb-0 small">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. STATS CARDS (Top Row) --}}
    <section class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2"><i class="iconly-boldProfile"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Pelajar</h6>
                            <h6 class="font-extrabold mb-0">{{ $charts['counter']['student'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2"><i class="iconly-boldBookmark"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Kelas</h6>
                            <h6 class="font-extrabold mb-0">{{ $charts['counter']['schoolClass'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon green mb-2"><i class="iconly-boldBag"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Jurusan</h6>
                            <h6 class="font-extrabold mb-0">{{ $charts['counter']['schoolMajor'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon red mb-2"><i class="iconly-boldProfile"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Admin</h6>
                            <h6 class="font-extrabold mb-0">{{ $charts['counter']['administrator'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. MAIN INTERACTIVE CHART (Full Width) --}}
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h4 id="card-chart-cash-transactions-title" class="mb-0">Total Transaksi Tahun Ini</h4>
                    <div class="d-flex align-items-center mt-2 mt-md-0">
                        <label for="year" class="form-label me-2 mb-0 text-nowrap">Tahun:</label>
                        <input wire:model="year" wire:keyup.enter="updateChart" type="number" id="year"
                            placeholder="Tahun.." value="{{ date('Y') }}" class="form-control form-control-sm" style="width: 100px;">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6" wire:ignore>
                            <div id="cash-transaction-chart-bar-by-year"></div>
                        </div>
                        <div class="col-md-6" wire:ignore>
                            <div id="cash-transaction-chart-line-by-year"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. SECONDARY CHARTS GRID (2 Columns) --}}
    <section class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <x-apexcharts.line-chart chartTitle="Pemasukan SPP / Tahun" seriesTitle="Transaksi"
                    chartID="chart-cash-transactions-count-per-year"
                    :series="$charts['lineChart']['cashTransactionCountPerYear']['series']"
                    :categories="$charts['lineChart']['cashTransactionCountPerYear']['categories']" />
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <x-apexcharts.line-chart chartTitle="Nominal Pembayaran / Tahun"
                    seriesTitle="Total (Rp)" chartID="chart-cash-transactions-amount-per-year"
                    :series="$charts['lineChart']['cashTransactionAmountPerYear']['series']"
                    :categories="$charts['lineChart']['cashTransactionAmountPerYear']['categories']" />
            </div>
        </div>
    </section>

    {{-- 5. PIE CHARTS GRID (3 Columns) --}}
    <section class="row">
        <div class="col-12 col-md-4">
            <div class="card">
                <x-apexcharts.pie-chart chartTitle="Gender Pelajar" chartID="chart-pie-student-gender"
                    :series="$charts['pieChart']['studentGender']['series']"
                    :labels="$charts['pieChart']['studentGender']['labels']" :colors="['#57CAEB', '#FF7976']" />
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card">
                <x-apexcharts.pie-chart chartTitle="Jurusan Pelajar" chartID="chart-pie-student-school-major"
                    :series="$charts['pieChart']['studentMajor']['series']"
                    :labels="$charts['pieChart']['studentMajor']['labels']" />
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card">
                <x-apexcharts.pie-chart chartTitle="Transaksi by Gender"
                    chartID="chart-pie-cash-transaction-by-gender"
                    :series="$charts['pieChart']['cashTransactionCountByGender']['series']"
                    :labels="$charts['pieChart']['cashTransactionCountByGender']['labels']" :colors="['#57CAEB', '#FF7976']" />
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
  document.addEventListener('livewire:init', () => {
    let cashTransactionBarChart;
    let cashTransactionLineChart;

    const categories = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

    Livewire.on('dashboard-chart-loaded', (e) => {
      const { amount, count } = e;

      // Render Bar Chart
      const barChartOptions = {
        chart: { type: "bar", height: 250 },
        series: [{ name: "Total Transaksi", data: Object.values(count) }], // Simplified data mapping
        colors: ["#435ebe"],
        xaxis: { categories: categories },
      };
      cashTransactionBarChart = new ApexCharts(document.querySelector("#cash-transaction-chart-bar-by-year"), barChartOptions);
      cashTransactionBarChart.render();

      // Render Line Chart
      const lineChartOptions = {
        series: [{ name: "Jumlah Pembayaran", data: Object.values(amount) }],
        chart: { height: 250, type: "line", zoom: { enabled: false } },
        dataLabels: { enabled: false },
        stroke: { curve: "straight" },
        grid: { row: { colors: ["#f3f3f3", "transparent"], opacity: 0.5 } },
        xaxis: { categories: categories },
      };
      cashTransactionLineChart = new ApexCharts(document.querySelector("#cash-transaction-chart-line-by-year"), lineChartOptions);
      cashTransactionLineChart.render();
    })

    Livewire.on('dashboard-chart-updated', (e) => {
      const { amount, count } = e;
      // Update data mapping to ensure order matches months
      cashTransactionBarChart.updateSeries([{
          data: [count.jan, count.feb, count.mar, count.apr, count.mei, count.jun, count.jul, count.agu, count.sep, count.okt, count.nov, count.des]
      }]);
      cashTransactionLineChart.updateSeries([{
          data: [amount.jan, amount.feb, amount.mar, amount.apr, amount.mei, amount.jun, amount.jul, amount.agu, amount.sep, amount.okt, amount.nov, amount.des]
      }]);
    });
  });
</script>
@endpush