<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('foreign_table_id');
            $table->unsignedBigInteger('foreign_table_column_id');
            $table->unsignedBigInteger('primary_table_id');
            $table->unsignedBigInteger('primary_table_column_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('foreign_table_id')
                ->references('id')
                ->on('schema_tables');
            $table->foreign('foreign_table_column_id')
                ->references('id')
                ->on('schema_table_columns');
            $table->foreign('primary_table_id')
                ->references('id')
                ->on('schema_tables');
            $table->foreign('primary_table_column_id')
                ->references('id')
                ->on('schema_table_columns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relationships');
    }
}
