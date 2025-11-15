<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $totalOrders = Order::count();

        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalSoldQuantity = \App\Models\OrderItem::sum('Quantity');
        $months = Order::selectRaw('MONTH(OrderDate) as month, COUNT(*) as cnt')
            ->groupBy('month')
            ->pluck('cnt', 'month')
            ->toArray();
        $soldMonths = \App\Models\OrderItem::selectRaw('MONTH(orders.OrderDate) as month, SUM(order_items.Quantity) as qty')
            ->join('orders', 'order_items.OrderID', '=', 'orders.OrderID')
            ->groupBy('month')
            ->pluck('qty', 'month')
            ->toArray();

        $selectedMonth = (int) $request->query('month', 0);
        $monthlyOrders = null;
        $monthlySoldQuantity = null;
        $monthlyRevenue = null;
        if ($selectedMonth >= 1 && $selectedMonth <= 12) {
            $monthlyOrders = isset($months[$selectedMonth]) ? (int) $months[$selectedMonth] : 0;
            $monthlySoldQuantity = isset($soldMonths[$selectedMonth]) ? (int) $soldMonths[$selectedMonth] : 0;
            $monthlyRevenue = Order::whereMonth('OrderDate', $selectedMonth)->sum('TotalAmount');
        }
        $dailyOrders = null;
        $dailySoldQuantity = null;
        $dailyRevenue = null;

        if ($request->has('date')) {
            $selectedDate = $request->query('date');
            try {
                $d = Carbon::createFromFormat('Y-m-d', $selectedDate)->startOfDay();
                $today = Carbon::today();
                if ($d->gt($today)) {
                    $selectedDate = Carbon::today()->toDateString();
                    $d = Carbon::today();
                }
            } catch (\Exception $e) {
                $selectedDate = Carbon::today()->toDateString();
                $d = Carbon::today();
            }
        } else {
            $selectedDate = Carbon::today()->toDateString();
            $d = Carbon::today();
        }

        $dailyOrders = Order::whereDate('OrderDate', $d->toDateString())->count();
        $dailyRevenue = Order::whereDate('OrderDate', $d->toDateString())->sum('TotalAmount');
        $dailySoldQuantity = \App\Models\OrderItem::join('orders', 'order_items.OrderID', '=', 'orders.OrderID')
            ->whereDate('orders.OrderDate', $d->toDateString())
            ->sum('order_items.Quantity');

        $chartMonth = $selectedMonth && $selectedMonth >= 1 ? $selectedMonth : Carbon::today()->month;
        $chartYear = Carbon::today()->year;
        $daysInMonth = Carbon::createFromDate($chartYear, $chartMonth, 1)->daysInMonth;

        $revByDayRaw = Order::selectRaw('DAY(OrderDate) as day, SUM(TotalAmount) as rev')
            ->whereYear('OrderDate', $chartYear)
            ->whereMonth('OrderDate', $chartMonth)
            ->groupBy('day')
            ->pluck('rev', 'day')
            ->toArray();

        $revenueByDayLabels = [];
        $revenueByDayData = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $revenueByDayLabels[] = (string) $day;
            $revenueByDayData[] = isset($revByDayRaw[$day]) ? (float) $revByDayRaw[$day] : 0;
        }

        $yearForMonthChart = Carbon::today()->year;
        $revByMonthRaw = Order::selectRaw('MONTH(OrderDate) as month, SUM(TotalAmount) as rev')
            ->whereYear('OrderDate', $yearForMonthChart)
            ->groupBy('month')
            ->pluck('rev', 'month')
            ->toArray();

        $revenueByMonthLabels = [];
        $revenueByMonthData = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueByMonthLabels[] = 'Tháng ' . $m;
            $revenueByMonthData[] = isset($revByMonthRaw[$m]) ? (float) $revByMonthRaw[$m] : 0;
        }

        $totalRevenue = Order::sum('TotalAmount');

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'months',
            'selectedMonth',
            'monthlyOrders',
            'totalSoldQuantity',
            'monthlySoldQuantity',
            'soldMonths',
            'totalRevenue',
            'monthlyRevenue',
            'selectedDate',
            'dailyOrders',
            'dailySoldQuantity',
            'dailyRevenue',
            'revenueByDayLabels',
            'revenueByDayData',
            'revenueByMonthLabels',
            'revenueByMonthData'
        ));
    }
}
