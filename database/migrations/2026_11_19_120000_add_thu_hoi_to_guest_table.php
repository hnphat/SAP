<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThuHoiToGuestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guest', function (Blueprint $table) {
            if (!Schema::hasColumn('guest', 'is_thu_hoi')) {
                $table->tinyInteger('is_thu_hoi')->default(0)->after('lyDoLostSale');
            }
            if (!Schema::hasColumn('guest', 'ly_do_thu_hoi')) {
                $table->string('ly_do_thu_hoi', 255)->nullable()->after('is_thu_hoi');
            }
            if (!Schema::hasColumn('guest', 'thoi_gian_thu_hoi')) {
                $table->dateTime('thoi_gian_thu_hoi')->nullable()->after('ly_do_thu_hoi');
            }
            if (!Schema::hasColumn('guest', 'sale_cu_id')) {
                $table->integer('sale_cu_id')->unsigned()->nullable()->after('thoi_gian_thu_hoi');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guest', function (Blueprint $table) {
            if (Schema::hasColumn('guest', 'sale_cu_id')) {
                $table->dropColumn('sale_cu_id');
            }
            if (Schema::hasColumn('guest', 'thoi_gian_thu_hoi')) {
                $table->dropColumn('thoi_gian_thu_hoi');
            }
            if (Schema::hasColumn('guest', 'ly_do_thu_hoi')) {
                $table->dropColumn('ly_do_thu_hoi');
            }
            if (Schema::hasColumn('guest', 'is_thu_hoi')) {
                $table->dropColumn('is_thu_hoi');
            }
        });
    }
}
