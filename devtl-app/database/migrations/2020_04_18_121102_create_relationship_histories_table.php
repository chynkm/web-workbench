<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationshipHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relationship_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relationship_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('foreign_table_id');
            $table->unsignedBigInteger('foreign_table_column_id');
            $table->unsignedBigInteger('primary_table_id');
            $table->unsignedBigInteger('primary_table_column_id');
            $table->timestamp('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));

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
        Schema::dropIfExists('relationship_histories');
    }
}
