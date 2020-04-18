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
            $table->foreignId('user_id')->constrained();
            $table->foreignId('schema_id')->constrained();
            $table->string('name', 100);
            $table->string('engine', 20);
            $table->string('collation', 40);
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
