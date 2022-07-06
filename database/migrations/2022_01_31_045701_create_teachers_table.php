<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nuptk')->unique()->nullable();
            $table->string('nip')->unique()->nullable();
            $table->enum('gender', ['Laki-Laki', 'Perempuan']);
            $table->date('birth_date');
            $table->string('birth_place')->nullable();
            $table->string('address')->nullable();
            $table->string('information')->nullable();
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
        Schema::dropIfExists('teachers');
    }
}
