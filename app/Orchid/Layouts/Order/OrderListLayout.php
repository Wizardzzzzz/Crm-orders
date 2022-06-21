<?php

namespace App\Orchid\Layouts\Order;

use App\Models\Client;
use App\Models\Order;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'orders';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', __('Id'))
                ->sort()
                ->cantHide()
                ->render(function (Order $order) {
                    return $order->id;
                }),
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
            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Order $order) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->icon('pencil')
                                ->route('platform.systems.orders.edit', $order),
                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm(__('Once the account is deleted,
                                all of its resources and data will be permanently deleted.
                                 Before deleting your account, please download any data or
                                  information that you wish to retain.'))
                                ->method('remove', [
                                    'id' => $order->id,
                                ]),
                        ]);
                }),
        ];
    }
}
