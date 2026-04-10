@extends('layouts.admin')

@section('title', __('auth.dashboard'))

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">@lang('auth.dashboard')</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">@lang('auth.total_orders')</h5>
                    <p class="card-text fs-3">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">@lang('auth.total_sales')</h5>
                    <p class="card-text fs-3">${{ number_format($totalSales, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h4>@lang('auth.orders_last_7_days')</h4>
            <canvas id="ordersChart"></canvas>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h4>@lang('auth.sales_last_7_days')</h4>
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

    <div class="card mt-4">
        <div class="card-body">
            <h4>@lang('auth.top_selling_products')</h4>
            <canvas id="topProductsChart"></canvas>
        </div>
    </div>

    <!-- NUEVOS GRÁFICOS DE IA Y BIG DATA -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">@lang('auth.ai_sales_forecast')</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">@lang('auth.ai_algo_linear_regression')</p>
                    <canvas id="forecastChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">@lang('auth.ai_product_affinity')</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">@lang('auth.ai_market_intelligence')</p>
                    <canvas id="affinityChart"></canvas>
                </div>
            </div>
            
            <div class="card mt-3 bg-light border-0">
                <div class="card-body">
                    <h6>⚡ Big Data Insight</h6>
                    <p id="insightText" class="small italic">@lang('auth.ai_loading_insight')</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SEGUNDA FILA IA: SEGMENTACIÓN Y STOCK -->
    <div class="row mt-4 mb-5">
        <div class="col-md-5">
            <div class="card shadow-sm border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">@lang('auth.ai_client_segmentation')</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">@lang('auth.ai_algo_kmeans')</p>
                    <canvas id="segmentationChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">@lang('auth.ai_inventory_alerts')</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">@lang('auth.ai_stock_risk_desc')</p>
                    <ul class="list-group list-group-flush">
                        @forelse($aiStats['stock_risk'] as $risk)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>{{ $risk['name'] }}</strong> (Stock: {{ $risk['stock'] }})</span>
                                <div>
                                    <span class="badge bg-{{ $risk['risk_level'] == 'Alto' ? 'danger' : 'warning' }} rounded-pill">
                                        @lang('auth.ai_stock_risk_' . ($risk['risk_level'] == 'Alto' ? 'high' : 'mid'))
                                    </span>
                                    <small class="ms-2 text-muted">@lang('auth.ai_days_left', ['days' => $risk['days_remaining']])</small>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-success text-center">@lang('auth.ai_inventory_ok')</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- Gráficos Originales ---
    var ordersData = {
        labels: {!! json_encode($ordersPerDay->pluck('date')) !!},
        datasets: [{
            label: '@lang("auth.orders")',
            data: {!! json_encode($ordersPerDay->pluck('count')) !!},
            borderColor: 'blue',
            backgroundColor: 'rgba(0, 0, 255, 0.1)',
            fill: true
        }]
    };

    var salesData = {
        labels: {!! json_encode($salesPerDay->pluck('date')) !!},
        datasets: [{
            label: '@lang("auth.sales") ($)',
            data: {!! json_encode($salesPerDay->pluck('sales')) !!},
            borderColor: 'green',
            backgroundColor: 'rgba(0, 255, 0, 0.1)',
            fill: true
        }]
    };

    // --- NUEVO: GRÁFICOS DE IA ---
    
    // 1. Predicción (Línea punteada)
    var historicalLabels = {!! json_encode($salesPerDay->pluck('date')) !!};
    var historicalSales = {!! json_encode($salesPerDay->pluck('sales')) !!};
    var forecastData = {!! json_encode($aiStats['forecast']) !!};
    
    // Generar labels futuros
    var lastDate = new Date(historicalLabels[historicalLabels.length - 1]);
    var forecastLabels = [];
    for(let i=1; i<=7; i++) {
        let next = new Date(lastDate);
        next.setDate(lastDate.getDate() + i);
        forecastLabels.push(next.toISOString().split('T')[0]);
    }

    new Chart(document.getElementById('forecastChart'), {
        type: 'line',
        data: {
            labels: [...historicalLabels, ...forecastLabels],
            datasets: [
                {
                    label: "@lang('auth.sales')",
                    data: historicalSales,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                },
                {
                    label: "@lang('auth.ai_sales_forecast')",
                    data: [...Array(historicalSales.length-1).fill(null), historicalSales[historicalSales.length-1], ...forecastData],
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderDash: [5, 5],
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    fill: false
                }
            ]
        }
    });

    // 2. Afinidad (Polar Area)
    var affinityRaw = {!! json_encode($aiStats['affinity']) !!};
    var productNames = {!! json_encode($aiStats['product_names']) !!};
    
    var affinityLabels = affinityRaw.map(a => (productNames[a.prod_a] || 'P'+a.prod_a) + ' + ' + (productNames[a.prod_b] || 'P'+a.prod_b));
    var affinityValues = affinityRaw.map(a => a.freq);

    new Chart(document.getElementById('affinityChart'), {
        type: 'polarArea',
        data: {
            labels: affinityLabels,
            datasets: [{
                data: affinityValues,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ]
            }]
        }
    });

    // Insight dinámico
    if(affinityRaw.length > 0) {
        let best = affinityRaw[0];
        document.getElementById('insightText').innerHTML = `La IA ha detectado que el <strong>${productNames[best.prod_a]}</strong> se vende frecuentemente junto con <strong>${productNames[best.prod_b]}</strong>. ¡Considera crear un pack promocional!`;
    }

    // 3. Segmentación (Doughnut)
    var segmentationRaw = {!! json_encode($aiStats['segmentation']) !!};
    var segLabels = Object.keys(segmentationRaw);
    var segValues = Object.values(segmentationRaw);

    new Chart(document.getElementById('segmentationChart'), {
        type: 'doughnut',
        data: {
            labels: segLabels,
            datasets: [{
                data: segValues,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // El resto de gráficos originales
    new Chart(document.getElementById('ordersChart'), { type: 'line', data: ordersData, options: { responsive: true } });
    new Chart(document.getElementById('salesChart'), { type: 'line', data: salesData, options: { responsive: true } });

    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: '@lang("auth.sold_products")',
                data: {!! json_encode($data) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>
@endsection