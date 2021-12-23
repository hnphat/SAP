<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Kpi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('thang', 7);
            $table->enum('type', ['pdv', 'pkd']);
            $table->integer('kpi1')->nullable();
            $table->integer('kpi2')->nullable();
            $table->float('kpiDecimal', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi');
    }
}
