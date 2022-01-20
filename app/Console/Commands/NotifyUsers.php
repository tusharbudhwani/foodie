<?php

namespace App\Console\Commands;

use App\Models\Storeroom;
use App\Models\User;
use App\Notifications\ExpiryDate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NotifyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();
        // DB::table('notifications')->truncate();
        foreach($users as $user) {
            $finalProducts = [];
            $products =
                    Storeroom::where('user_id', $user->id)
                        ->isPurchased()
                        ->isNotConsumed()
                        ->with('product', 'unit')
                        ->oldest('expiry_date')->get();

            foreach($products as $product) {
                if($product->expiryProductsWithinAWeek()) {
                    array_push($finalProducts, $product);
                }
            }
            if(count($finalProducts) > 0) {
                $user->notify(new ExpiryDate($user, $finalProducts));
            }
        }
    }
}
