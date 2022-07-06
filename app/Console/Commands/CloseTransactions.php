<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\Store;
use Illuminate\Console\Command;

class CloseTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close and change status transaction according setting time';

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
        if ((int)$currentTransactionStatus->values != 0) {
            Setting::where('name', 'Open Transaction')->update([
                'values' => 0
            ]);
        }
        $storeIds = Store::orderBy('id', 'asc')->get('id');
        for ($i = 1; $i <= $storeIds->count(); $i++) {
            Store::where('id', $storeIds[$i - 1]->id)->update([
                'open_status' => false
            ]);
        }
    }
}
