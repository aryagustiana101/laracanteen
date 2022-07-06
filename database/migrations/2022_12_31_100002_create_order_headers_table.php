<?php

use App\Models\PaymentGroup;
use App\Models\Status;
use App\Models\Store;
use App\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Transaction::class);
            $table->foreignIdFor(PaymentGroup::class);
            $table->foreignIdFor(Store::class);
            $table->foreignIdFor(Status::class);
            $table->string('order_code');
            $table->string('total_price');
            $table->string('total_price_rounded');
            $table->string('total_admin_price');
            $table->string('total_admin_price_rounded');
            $table->string('total_tax_price');
            $table->string('total_tax_price_rounded');
            $table->string('total_user_price');
            $table->string('total_user_price_rounded');
            $table->string('total_items');
            $table->dateTime('retrieval_time')->nullable();
            $table->string('retrieval_details')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_headers');
    }
}
