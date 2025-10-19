<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeJenisKamarToStringInKamarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kamar', function (Blueprint $table) {
            // Mengubah tipe kolom menjadi VARCHAR(255) yang lebih fleksibel
            $table->string('jenis_kamar', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kamar', function (Blueprint $table) {
            // Jika di-rollback, kembalikan ke tipe data sebelumnya (sesuaikan jika perlu)
            // Misalnya, jika sebelumnya ENUM atau VARCHAR(50)
            $table->string('jenis_kamar', 50)->change();
        });
    }
}
