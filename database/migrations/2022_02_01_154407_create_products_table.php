<?php

use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Store::class);
            $table->foreignIdFor(Category::class);
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('price');
            $table->string('price_rounded');
            $table->string('admin_price');
            $table->string('admin_price_rounded');
            $table->string('tax_price');
            $table->string('tax_price_rounded');
            $table->string('user_price');
            $table->string('user_price_rounded');
            $table->string('image')->nullable();
            $table->boolean('availability_status')->default(true);
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
        Schema::dropIfExists('products');
    }
}
