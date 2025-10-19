<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBulanPembayaranToBayarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bayar', function (Blueprint $table) {
            $table->string('bulan_pembayaran')->nullable()->after('bulan_bayar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bayar', function (Blueprint $table) {
            $table->dropColumn('bulan_pembayaran');
        });
    }
}
