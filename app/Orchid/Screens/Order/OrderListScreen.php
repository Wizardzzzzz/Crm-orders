<?php

namespace App\Orchid\Screens\Order;

use App\Models\Order;
use App\Orchid\Layouts\User\UserFiltersLayout;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class OrderListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'orders' => Order::with('user')
                ->filters()
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Orders';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add '))
                ->icon('plus')
                ->route('platform.systems.orders.create'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('clients', [
                TD::make('id')
                    ->sort()
                    ->cantHide(),
                TD::make('user.first_name')
                    ->cantHide(),
                TD::make('user.last_name')
                    ->cantHide(),
                TD::make('user.email')
                    ->cantHide(),
                TD::make('product_id'),
                TD::make('receive_date')
                    ->sort()
                    ->cantHide()
                    ->filter(Input::make()),
                TD::make('price')
                    ->sort()
                    ->cantHide()
                    ->filter(Input::make()),
                TD::make('action')
                    ->render(function (Order $order) {
                        return Link::make()
                            ->icon('pencil')
                            ->route('platform.systems.orders.edit', $order);
                    }),
            ])
        ];
    }

    public function remove(Order $order)
    {
        $order->delete();
    }
}
