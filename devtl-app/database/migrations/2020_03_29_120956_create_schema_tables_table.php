<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchemaTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schema_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('schema_id');
            $table->string('name', 100);
            $table->string('description')
                ->nullable();
            $table->integer('x_index')
                ->default(10)
                ->comment('in pixels');
            $table->integer('y_index')
                ->default(10)
                ->comment('in pixels');
            $table->integer('height')
                ->default(100)
                ->comment('in pixels');
            $table->integer('width')
                ->default(250)
                ->comment('in pixels');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreign('schema_id')
                ->references('id')
                ->on('schemas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schema_tables');
    }
}
