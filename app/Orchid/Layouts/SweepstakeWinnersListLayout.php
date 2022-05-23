<?php

namespace App\Orchid\Layouts;

use App\Models\SweepstakeWinner;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SweepstakeWinnersListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'sweepstake_winners';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('user.name', __('Name'))
                ->cantHide(),

            TD::make('user.email', __('Email'))
                ->cantHide(),

            TD::make('created_at', __('Win date'))
                ->cantHide()
                ->render(function (SweepstakeWinner $sweepstakeWinner) {
                    return $sweepstakeWinner->created_at->toDateTimeString();
                }),
        ];
    }
}
