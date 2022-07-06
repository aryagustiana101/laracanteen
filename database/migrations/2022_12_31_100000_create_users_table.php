<?php

use App\Models\Administrator;
use App\Models\Seller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Teller;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->unique()->nullable();
            $table->foreignIdFor(Teacher::class)->unique()->nullable();
            $table->foreignIdFor(Seller::class)->unique()->nullable();
            $table->foreignIdFor(Teller::class)->unique()->nullable();
            $table->foreignIdFor(Administrator::class)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('password');
            $table->boolean('active_status')->default(true);
            $table->string('image')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
