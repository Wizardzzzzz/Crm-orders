<?php

namespace App\Orchid\Screens;

use App\Models\Sweepstake;
use App\Orchid\Layouts\SweepstakeListLayout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class SweepstakeScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'sweepstakes' => Sweepstake::with('user')
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
        return 'Sweepstake';
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
            Layout::view('sweepstake'),
            SweepstakeListLayout::class
        ];
    }
}
