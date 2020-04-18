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
            $table->foreignId('schema_table_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('schema_id')->constrained();
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
