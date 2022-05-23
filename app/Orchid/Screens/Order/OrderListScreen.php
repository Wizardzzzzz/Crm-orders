<?php

namespace App\Orchid\Screens\Order;

use App\Models\Order;
use App\Models\Sweepstake;
use App\Orchid\Layouts\Order\OrderListLayout;
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
            OrderListLayout::class
        ];
    }

    public function remove(Order $order)
    {
        $sweepstakeUser = Sweepstake::where('user_id', $order->user_id)
            ->whereDate('created_at', '>=', $order->created_at)
            ->first();
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
