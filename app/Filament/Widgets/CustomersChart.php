<?php

namespace App\Filament\Widgets;

use App\Models\Shop\Customer;
use Filament\Widgets\ChartWidget;

class CustomersChart extends ChartWidget
{
    protected static ?string $heading = 'Total Customers';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $monthlyCustomers = array_fill(0, 12, 0);

        $customers = Customer::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        foreach ($customers as $month => $total) {
            $monthlyCustomers[$month - 1] = $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Customers',
                    'data' => $monthlyCustomers,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)', // Warna isi bar
                    'borderColor' => 'rgba(75, 192, 192, 1)', // Warna border bar
                    'borderWidth' => 1,
                ],
            ],
            'labels' => [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
