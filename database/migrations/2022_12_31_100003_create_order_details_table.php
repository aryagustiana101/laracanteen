<?php

use App\Models\OrderHeader;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderHeader::class);
            $table->foreignIdFor(Product::class);
            $table->string('amount');
            $table->string('name');
            $table->string('description');
            $table->string('price');
            $table->string('price_rounded');
            $table->string('admin_price');
            $table->string('admin_price_rounded');
            $table->string('tax_price');
            $table->string('tax_price_rounded');
            $table->string('user_price');
            $table->string('user_price_rounded');
            $table->string('customer_notes')->nullable();
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
        Schema::dropIfExists('order_details');
    }
}
