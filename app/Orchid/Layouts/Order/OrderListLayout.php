<?php

namespace App\Orchid\Layouts\Order;

use App\Models\Client;
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
    protected $target = 'clients';

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
                ->render(function (Client $client) {
                    return $client->id;
                }),

            TD::make('name', __('Name'))
                ->cantHide()
                ->render(function (Client $client) {
                    return Link::make($client->first_name . ' ' . $client->last_name)
                        ->route('platform.client.edit', $client);
                }),

            TD::make('email', __('Email'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Client $client) {
                    return $client->email;
                }),

            TD::make('product_id', __('Product id'))
                ->cantHide()
                ->filter(Input::make())
                ->render(function (Client $client) {
                    return $client->product_id;
                }),

            TD::make('price', __('Price'))
                ->sort()
                ->cantHide()
                ->render(function (Client $client) {
                    return $client->price;
                }),

            TD::make(__('Delete'))
                ->align(TD::ALIGN_CENTER)
                ->render(function (Client $client) {
                    return Button::make(__('Delete'))
                        ->icon('trash')
                        ->confirm(__('Are you sure you want to delete client.'))
                        ->method('remove', [
                            'id' => $client->id,
                        ]);
                }),
        ];
    }
}
