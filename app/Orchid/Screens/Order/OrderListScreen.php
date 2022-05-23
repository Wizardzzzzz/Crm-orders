<?php

namespace App\Orchid\Screens\Order;

use App\Models\Order;
use App\Models\Sweepstake;
use App\Orchid\Layouts\User\UserFiltersLayout;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Alert;
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
            Layout::table('orders', [
                TD::make('id', __('Id'))
                    ->sort()
                    ->cantHide(),
                TD::make('user.name', __('Name'))
                    ->cantHide(),
                TD::make('user.email', __('Email'))
                    ->cantHide(),
                TD::make('product_id', __('Product id')),
                TD::make('receive_date', __('Receive date'))
                    ->sort()
                    ->cantHide()
                    ->filter(Input::make()),
                TD::make('price', __('Price'))
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
        $sweepstakeUser = Sweepstake::where('user_id', $order->user_id)->first();
        if ($sweepstakeUser) {
            if($sweepstakeUser->amount <= 0) {
                $sweepstakeUser->delete();
            } else {
                $sweepstakeUser->amount -= $order->price;
                $sweepstakeUser->save();
            }
        }

        $order->delete();
        Alert::info('You have successfully deleted the order.');
    }
}
