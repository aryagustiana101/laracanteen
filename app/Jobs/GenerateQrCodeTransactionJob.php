<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQrCodeTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image_name;
    protected $transaction_code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $transaction_code,$image_name)
    {
        $this->image_name = $image_name;
        $this->transaction_code = $transaction_code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        QrCode::size(500)->format('png')->generate($this->transaction_code, public_path("storage/qrcodes/transactions/ $this->image_name"));
    }
}
