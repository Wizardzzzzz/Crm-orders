<?php

namespace App\Orchid\Layouts;

use App\Models\Sweepstake;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SweepstakeListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'sweepstakes';

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
                ->render(function (Sweepstake $sweepstake) {
                    return $sweepstake->id;
                }),

            TD::make('user.name', __('Name'))
                ->cantHide(),

            TD::make('user.email', __('Email'))
                ->cantHide(),

            TD::make('amount', __('Amount'))
                ->sort()
                ->cantHide()
                ->render(function (Sweepstake $sweepstake) {
                    return $sweepstake->amount;
                }),
        ];
    }
}
