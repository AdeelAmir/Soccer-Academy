<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Subscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and Manage Subscriptions';

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
        Transaction::create([
            'type' => 2,
            'bill_to' => 0,
            'order_id' => 0,
            'total_amount' => 0,
            'amount_paid' => 0,
            'status' => 2,
            'paid_date' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        return true;
    }
}