<?php

namespace App\Orchid\Screens\Order;

use App\Models\Order;
use App\Models\Sweepstake;
use Illuminate\Http\Request;
use \Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class OrderEditScreen extends Screen
{

    public Order $order;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Order $order): iterable
    {
        return [
            'order' => $order
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->order->exists ? 'Edit order' : 'Creating a new order';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create client')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->client->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->client->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->client->exists),
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
            Layout::rows([
                Input::make('client.first_name')
                    ->required()
                    ->type('text')
                    ->title('First name')
                    ->placeholder('First name'),

                Input::make('client.last_name')
                    ->required()
                    ->type('text')
                    ->title('Last name')
                    ->placeholder('Last name'),

                Input::make('client.phone')
                    ->required()
                    ->mask([
                        'mask' => '+(999) 99 99 99 999',
                        'removeMaskOnSubmit' => true,
                    ])
                    ->title('Phone number'),

                Input::make('client.email')
                    ->required()
                    ->type('email')
                    ->title('Email')
                    ->placeholder('Email'),

                Input::make('client.product_id')
                    ->required()
                    ->type('number')
                    ->title('Product id'),

                DateTimer::make('client.receive_date')
                    ->required()
                    ->title('Receive date')
                    ->enableTime(),

                Input::make('client.price')
                    ->required()
                    ->title('Price')
                    ->mask([
                        'alias' => 'currency',
                        'prefix' => '$',
                        'groupSeparator' => ' ',
                        'unmaskAsNumber' => true,
                        'removeMaskOnSubmit' => true,
                    ])
            ])
        ];
    }

    public function createOrUpdate(Client $client, Request $request): RedirectResponse
    {
        $client->fill($request->get('client'))->save();
        if ($client->wasRecentlyCreated) {
            $sweepstakeUser = Sweepstake::firstOrNew(['client_id', $client->id]);
            $sweepstakeUser->amount += $request->price;
            $sweepstakeUser->save();
        }

        Alert::info('You have successfully saved the clients.');

        return redirect()->route('platform.systems.clients');
    }

    public function remove(Client $client): RedirectResponse
    {
        $sweepstakeUser = Sweepstake::where('client_id', $client->id)->first();
        if ($sweepstakeUser) {
            if($sweepstakeUser->amount <= 0) {
                $sweepstakeUser->delete();
            } else {
                $sweepstakeUser->amount -= $client->price;
            }
        }

        $client->delete();
        Alert::info('You have successfully deleted the clients.');
        return redirect()->route('platform.systems.clients');
    }
}
