<?php

namespace App\Filament\Widgets;

use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{

    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null ? Carbon::parse($this->filters['startDate']) : now()->startOfMonth();
        $endDate = $this->filters['endDate'] ?? now();

        $revenue = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total_price');
        $newCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])->count();
        $newOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        $formatNumber = function (int $number): string {
            if ($number < 1000) {
                return number_format($number, 0);
            }

            if ($number < 1000000) {
                return number_format($number / 1000, 2) . 'k';
            }

            return number_format($number / 1000000, 2) . 'm';
        };

        return [
            Stat::make('Revenue', 'Rp ' . number_format($revenue, 0, ',', '.'))
                ->description('Calculated based on the selected period')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($this->getRevenueChart($startDate, $endDate))
                ->color('success'),

            Stat::make('New Customers', $formatNumber($newCustomers))
                ->description('Calculated based on the selected period')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart($this->getNewCustomersChart($startDate, $endDate))
                ->color('danger'),

            Stat::make('New Orders', $formatNumber($newOrders))
                ->description('Calculated based on the selected period')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($this->getNewOrdersChart($startDate, $endDate))
                ->color('success'),
        ];
    }

    protected function getRevenueChart($startDate, $endDate): array
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
                    ->selectRaw('DAY(created_at) as day, SUM(total_price) as total')
                    ->groupBy('day')
                    ->orderBy('day')
                    ->pluck('total')
                    ->toArray();
    }

    protected function getNewCustomersChart($startDate, $endDate): array
    {
        return Customer::whereBetWeen('created_at', [$startDate, $endDate])
                       ->selectRaw('DAY(created_at) as day, COUNT(*) as count')
                       ->groupBy('day')
                       ->orderBy('day')
                       ->pluck('count')
                       ->toArray();
    }

    protected function getNewOrdersChart($startDate, $endDate): array
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
                    ->selectRaw('DAY(created_at) as day, COUNT(*) as count')
                    ->groupBy('day')
                    ->orderBy('day')
                    ->pluck('count')
                    ->toArray();
    }
}
