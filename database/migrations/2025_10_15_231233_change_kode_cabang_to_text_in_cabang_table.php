<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeKodeCabangToTextInCabangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cabang', function (Blueprint $table) {
            // Langkah 1: Hapus unique index yang ada jika ada.
            // Laravel biasanya menamainya dengan format: nama_tabel_nama_kolom_unique
            $table->dropUnique('cabang_kode_cabang_unique');
        });

        Schema::table('cabang', function (Blueprint $table) {
            // Langkah 2: Ubah tipe kolom menjadi TEXT
            $table->text('kode_cabang')->change();
        });

        // Langkah 3: Buat kembali unique index dengan panjang kunci 255
        DB::statement('ALTER TABLE cabang ADD UNIQUE INDEX `cabang_kode_cabang_unique` (`kode_cabang`(255))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cabang', function (Blueprint $table) {
            // Balikkan prosesnya jika migrasi di-rollback
            $table->dropUnique('cabang_kode_cabang_unique');
        });

        Schema::table('cabang', function (Blueprint $table) {
            $table->string('kode_cabang')->change();
        });

        Schema::table('cabang', function (Blueprint $table) {
            $table->unique('kode_cabang');
        });
    }
}
