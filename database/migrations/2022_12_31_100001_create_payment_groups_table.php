<?php

use App\Models\Cashier;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Cashier::class)->nullable();
            $table->string('payment_code')->unique();
            $table->string('total_price');
            $table->string('total_price_rounded');
            $table->string('total_admin_price');
            $table->string('total_admin_price_rounded');
            $table->string('total_tax_price');
            $table->string('total_tax_price_rounded');
            $table->string('total_user_price');
            $table->string('total_user_price_rounded');
            $table->string('total_items');
            $table->string('total_reduced_price')->default('0');
            $table->string('total_reduced_price_rounded')->default('0');
            $table->string('total_reduced_admin_price')->default('0');
            $table->string('total_reduced_admin_price_rounded')->default('0');
            $table->string('total_reduced_tax_price')->default('0');
            $table->string('total_reduced_tax_price_rounded')->default('0');
            $table->string('total_reduced_user_price')->default('0');
            $table->string('total_reduced_user_price_rounded')->default('0');
            $table->string('total_reduced_items')->default('0');
            $table->string('amount_user_paid')->default('0');
            $table->string('user_change')->default('0');
            $table->boolean('payment_status')->default(false);
            $table->dateTime('payment_time')->nullable();
            $table->string('payment_details')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('payment_groups');
    }
}
