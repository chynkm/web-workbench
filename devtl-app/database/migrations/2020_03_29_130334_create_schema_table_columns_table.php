<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchemaTableColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schema_table_columns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('schema_table_id');
            $table->string('name');
            $table->string('datatype', 50);
            $table->string('length');
            $table->boolean('primary_key')
                ->default(0);
            $table->boolean('nullable')
                ->default(1);
            $table->boolean('unsigned')
                ->default(0);
            $table->boolean('unique')
                ->default(0);
            $table->boolean('zero_fill')
                ->default(0);
            $table->boolean('auto_increment')
                ->default(0);
            $table->string('default_value')
                ->nullable();
            $table->string('comment')
                ->nullable();
            $table->tinyInteger('order');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreign('schema_table_id')
                ->references('id')
                ->on('schema_tables');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schema_table_columns');
    }
}
