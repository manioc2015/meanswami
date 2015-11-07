<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password', 60)->nullable();
            $table->bigInteger('facebook_user_id')->nullable();
            $table->string('gender', 6)->nullable();
            $table->date('birthday')->nullable();
		$table->string('confirmation_code')->nullable();
		$table->boolean('confirmed')->default(config('access.users.confirm_email') ? false : true);
            $table->rememberToken()->nullable();
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
        Schema::drop('users');
    }
}
