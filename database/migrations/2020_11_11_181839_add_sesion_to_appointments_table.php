<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSesionToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->smallInteger('session')->after('end');
            $table->tinyInteger('price')->after('session')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->enum('status',['pending','confirmed','canceled'])->default('pending');
            $table->char('color',10)->default('#C21DF0');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes('deleted_at', 0)->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
}
