<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchemaTableHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schema_table_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schema_table_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('schema_id');
            $table->string('name', 100);
            $table->string('engine', 20);
            $table->string('collation', 40);
            $table->string('description')
                ->nullable();
            $table->integer('x_index')
                ->comment('in pixels');
            $table->integer('y_index')
                ->comment('in pixels');
            $table->integer('height')
                ->comment('in pixels');
            $table->integer('width')
                ->comment('in pixels');
            $table->timestamp('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('schema_table_id')
                ->references('id')
                ->on('schema_tables');
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
        Schema::dropIfExists('schema_table_histories');
    }
}
