<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BieuMau extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bieu_mau', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ngayTao');
            $table->string('tieuDe')->unique();
            $table->string('slug');
            // $table->longText('noiDung')->nullable();
            $table->string('moTa')->nullable();
            $table->string('ghiChu')->nullable();
            $table->string('url')->nullable();
            $table->enum('type', ['TB', 'BM']);
            $table->boolean('allow')->default(false);
            $table->boolean('all')->default(false);
            // $table->integer('user_see')->unsigned();
            // $table->foreign('user_see')->references('id')->on('users');
            $table->integer('user_create')->unsigned();
            $table->foreign('user_create')->references('id')->on('users');
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
        Schema::dropIfExists('bieu_mau');
    }
}
