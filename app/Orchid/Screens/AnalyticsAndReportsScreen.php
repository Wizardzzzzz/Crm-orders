<?php

namespace App\Orchid\Screens;

use App\Models\Client;
use App\Models\Order;
use App\Orchid\Layouts\Charts\DynamicsProductsOrders;
use App\Orchid\Layouts\Charts\DynamicsProductsOrdersOnMonth;
use App\Orchid\Layouts\Charts\PersentagePriceClients;
use Illuminate\Contracts\Queue\ClearableQueue;
use Illuminate\Support\Carbon;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Illuminate\Database\Eloquent\Builder;

class AnalyticsAndReportsScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $week = Carbon::now()->subDay(7);
        $end_week = Carbon::now()->subDay(1);
        $start = Carbon::now()->subDay(31);
        $end = Carbon::now()->subDay(1);

        return [
            'persentagePrice' => Order::countForGroup('Price')->toChart(),
            'interviewedProduct' => [Order::countByDays($week, $end_week, 'receive_date')->toChart('Date of receiving on week'),],
            'interviewedProductOnMonth' => [Order::countByDays($start, $end, 'receive_date')->toChart('Date of receiving on month'),]

        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Analytics And Reports';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            \Orchid\Support\Facades\Layout::columns([
                PersentagePriceClients::class,
                DynamicsProductsOrders::class
            ]),
            DynamicsProductsOrdersOnMonth::class
        ];
    }
}
