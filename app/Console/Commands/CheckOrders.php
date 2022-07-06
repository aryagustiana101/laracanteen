<?php

namespace App\Console\Commands;

use App\Models\OrderHeader;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check every orders in database';

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
        $incomingStatus = Status::where('position', 'first')->where('result', 'success-user')->first();
        $prepareStatus = Status::where('position', 'third')->where('result', 'success-seller')->first();
        $readyPayStatus = Status::where('position', 'fifth')->where('result', 'success-seller')->first();
        $readyTakeStatus = Status::where('position', 'seventh')->where('result', 'success-seller')->first();
        $paramCheck = [
            'status' => [$incomingStatus->id, $prepareStatus->id, $readyPayStatus->id, $readyTakeStatus->id],
            'order' => 'desc'
        ];
        $ordersStatusCheck = OrderHeader::statusCheck($paramCheck)->get();
        if ($ordersStatusCheck) {
            for ($i = 1; $i <= $ordersStatusCheck->count(); $i++) {
                if ($ordersStatusCheck[$i - 1]->status_id == $incomingStatus->id) {
                    incomingOrderCheck($ordersStatusCheck[$i - 1]);
                }
                if ($ordersStatusCheck[$i - 1]->status_id == $prepareStatus->id) {
                    prepareOrderCheck($ordersStatusCheck[$i - 1]);
                }
                if ($ordersStatusCheck[$i - 1]->status_id == $readyPayStatus->id) {
                    readyToPayOrderCheck($ordersStatusCheck[$i - 1]);
                }
                if ($ordersStatusCheck[$i - 1]->status_id == $readyTakeStatus->id) {
                    readyToTakeOrderCheck($ordersStatusCheck[$i - 1]);
                }
            }
        }
    }
}
