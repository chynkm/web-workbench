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
            $table->string('email')
                ->unique();
            $table->string('password')
                ->nullable();
            $table->string('name', 100)
                ->nullable();
            $table->string('company', 150)
                ->nullable();
            $table->string('vat_id', 100)
                ->nullable();
            $table->string('address', 400)
                ->nullable();
            $table->string('state', 100)
                ->nullable();
            $table->string('city', 100)
                ->nullable();
            $table->string('zip', 20)
                ->nullable();
            $table->unsignedInteger('country_id')
                ->nullable();
            $table->tinyInteger('status')
                ->default(1);
            $table->string('session_id', 50)
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
