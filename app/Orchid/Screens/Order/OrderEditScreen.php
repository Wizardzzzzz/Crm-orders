<?php

namespace App\Orchid\Screens\Order;

use App\Models\Order;
use App\Models\Sweepstake;
use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
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
            Button::make('Create order')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->order->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->order->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->order->exists),
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
                Relation::make('order.user_id')
                    ->fromModel(User::class, 'name')
                    ->applyScope('onlyClient')
                    ->required()
                    ->title(__('User')),
                Input::make('order.product_id')
                    ->required()
                    ->type('number')
                    ->title('Product id'),
                DateTimer::make('order.receive_date')
                    ->required()
                    ->title('Receive date')
                    ->enableTime(),
                Input::make('order.price')
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

    public function createOrUpdate(Order $order, Request $request): RedirectResponse
    {
        DB::transaction(function() use ($order, $request) {
            $order->fill($request->get('order'))->save();
            if ($order->wasRecentlyCreated) {
                $sweepstakeUser = Sweepstake::firstOrNew(['user_id' => $order->user_id]);
                $sweepstakeUser->amount += $order->price;
                $sweepstakeUser->save();
            }
        });

        Alert::info('You have successfully saved the order.');

        return redirect()->route('platform.systems.orders');
    }

    public function remove(Order $order): RedirectResponse
    {
        $sweepstakeUser = Sweepstake::where('user_id', $order->user_id)->first();
        if ($sweepstakeUser) {
            if($sweepstakeUser->amount >= 0) {
                $sweepstakeUser->delete();
            } else {
                $sweepstakeUser->amount -= $order->price;
                $sweepstakeUser->save();
            }
        }

        $order->delete();
        Alert::info('You have successfully deleted the order.');

        return redirect()->route('platform.systems.orders');
    }
}
