<?php

namespace App\Orchid\Screens;

use App\Models\SweepstakeWinner;
use App\Orchid\Layouts\SweepstakeWinnersListLayout;
use Orchid\Screen\Screen;

class SweepstakeWinnersScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'sweepstake_winners' => SweepstakeWinner::with('user')
                ->defaultSort('id', 'asc')
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
        return 'Sweepstake winners';
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
            SweepstakeWinnersListLayout::class
        ];
    }
}
