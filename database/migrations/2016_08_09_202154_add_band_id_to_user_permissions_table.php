<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBandIdToUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->integer('band_id')->unsigned()->nullable();
            $table->foreign('band_id')->references('id')->on('bands');
        });
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->integer('band_id')->unsigned()->nullable();
            $table->foreign('band_id')->references('id')->on('bands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->dropForeign('user_permissions_band_id_foreign');
            $table->dropColumn('band_id');
        });
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropForeign('role_permissions_band_id_foreign');
            $table->dropColumn('band_id');
        });
    }
}
