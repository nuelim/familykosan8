<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('biodata', function (Blueprint $table) {
            $table->text('id_cabang')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('biodata', function (Blueprint $table) {
            $table->integer('id_cabang')->nullable()->change();
        });
    }
};