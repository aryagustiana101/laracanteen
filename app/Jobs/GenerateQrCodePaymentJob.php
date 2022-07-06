<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQrCodePaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image_name;
    protected $payment_code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $payment_code,$image_name)
    {
        $this->image_name = $image_name;
        $this->payment_code = $payment_code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        QrCode::size(500)->format('png')->generate(url("/payment/$this->payment_code/pay"), public_path("storage/qrcodes/paymentgroups/$this->image_name"));
    }
}
