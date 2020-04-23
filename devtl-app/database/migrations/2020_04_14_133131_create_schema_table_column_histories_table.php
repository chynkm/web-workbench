<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchemaTableColumnHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schema_table_column_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schema_table_column_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('schema_table_id')->constrained();
            $table->string('name');
            $table->string('datatype', 50);
            $table->string('length')
                ->nullable();
            $table->boolean('primary_key');
            $table->boolean('nullable');
            $table->boolean('unsigned');
            $table->boolean('unique');
            $table->boolean('zero_fill');
            $table->boolean('auto_increment');
            $table->string('default_value')
                ->nullable();
            $table->string('comment')
                ->nullable();
            $table->tinyInteger('order');
            $table->timestamp('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schema_table_column_histories');
    }
}
