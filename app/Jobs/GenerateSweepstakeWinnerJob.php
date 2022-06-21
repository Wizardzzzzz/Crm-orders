<?php

namespace App\Jobs;

use App\Models\Sweepstake;
use App\Models\SweepstakeWinner;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GenerateSweepstakeWinnerJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const STEP = 10;

    public array $box = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::transaction(function () {
                $sweepstakes = Sweepstake::with('user')->get();

                foreach ($sweepstakes as $sweepstake) {
                    for ($i = 0; $i < (int)($sweepstake->amount / self::STEP); $i++) {
                        $this->box[] = $sweepstake->user->id;
                    }
                }

                $winner = $this->box[array_rand($this->box)];
                $user = User::find($winner);
//                dd($this->box);
                SweepstakeWinner::create([
                    'user_id' => $winner
                ]);

                Sweepstake::truncate();

                // TODO: Send email to user
            });
        } catch (\Exception $exception) {
            info($exception->getMessage());
            dd($exception->getMessage());
        }
    }
}
