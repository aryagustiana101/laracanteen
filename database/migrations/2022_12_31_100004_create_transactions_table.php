<?php

use App\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Store::class);
            $table->string('transaction_code')->unique();
            $table->string('total_income');
            $table->string('total_income_rounded');
            $table->string('total_admin_income');
            $table->string('total_admin_income_rounded');
            $table->string('total_tax_income');
            $table->string('total_tax_income_rounded');
            $table->string('total_user_income');
            $table->string('total_user_income_rounded');
            $table->string('total_items');
            $table->boolean('withdraw_status')->default(false);
            $table->dateTime('withdraw_time')->nullable();
            $table->string('withdraw_details')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
