<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class OpenTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:open';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open and change status transaction according setting time';

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
        $currentTransactionStatus = Setting::where('name', 'Open Transaction')->first();
        if ((int) $currentTransactionStatus->values != 1){
            Setting::where('name', 'Open Transaction')->update([
                'values' => 1
            ]);
        }
        checkFirstTransaction();
    }
}
