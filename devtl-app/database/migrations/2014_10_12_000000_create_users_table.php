<?php

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
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name', 100);
            $table->string('company', 150);
            $table->string('vat_id', 100);
            $table->string('address', 400);
            $table->string('state', 100);
            $table->string('city', 100);
            $table->string('zip', 20);
            $table->unsignedInteger('country_id');
            $table->tinyInteger('status')
                ->default(1);
            $table->timestamp('email_verified_at')
                ->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries');
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
