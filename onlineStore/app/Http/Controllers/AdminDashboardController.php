<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Item;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        //Total de pedidos
        $totalOrders = Order::count();

        //Total de ventas
        $totalSales = Order::sum('total');

        //Datos necesarios para el grafico de los pedidos realizados los ultimos 7 días
        $ordersPerDay = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->get();

        //Datos necesarios para el grafico de las ventas realizadas los ultimos 7 días
        $salesPerDay = Order::selectRaw('DATE(created_at) as date, SUM(total) as sales')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->get();

        //Obtener los productos mas vendidos
        $topProducts = Item::selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get();
        
        $labels = $topProducts->pluck('product.name');
        $data = $topProducts->pluck('total_sold');

        // --- NUEVO: CONSUMIR BIG DATA E IA DESDE EL MICROSERVICIO ---
        $aiStats = [
            'forecast' => [],
            'affinity' => [],
            'product_names' => [],
            'segmentation' => [],
            'stock_risk' => []
        ];

        try {
            // Predicciones y Afinidad
            $response = \Illuminate\Support\Facades\Http::timeout(3)->get('http://127.0.0.1:8001/admin/stats');
            if ($response->successful()) {
                $decoded = $response->json();
                $aiStats['forecast'] = $decoded['sales_forecast'] ?? [];
                $aiStats['affinity'] = $decoded['product_affinity'] ?? [];
                $aiStats['product_names'] = $decoded['product_names'] ?? [];
            }

            // Segmentación de Clientes (Clustering)
            $responseSeg = \Illuminate\Support\Facades\Http::timeout(3)->get('http://127.0.0.1:8001/admin/segmentation');
            if ($responseSeg->successful()) {
                $aiStats['segmentation'] = $responseSeg->json()['summary'] ?? [];
            }

            // Riesgo de Inventario
            $responseStock = \Illuminate\Support\Facades\Http::timeout(3)->get('http://127.0.0.1:8001/admin/inventory-risk');
            if ($responseStock->successful()) {
                $aiStats['stock_risk'] = $responseStock->json() ?? [];
            }

        } catch (\Exception $e) {
            // Silently fail if AI service is down
        }

        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'totalSales' => $totalSales,
            'ordersPerDay' => $ordersPerDay,
            'salesPerDay' => $salesPerDay,
            'labels' => $labels,
            'data' => $data,
            'aiStats' => $aiStats,
        ]);
    }
}
