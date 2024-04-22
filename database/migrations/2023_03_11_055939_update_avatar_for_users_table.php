<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAvatarForUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable();
            $table->bigInteger('role_id')->default(1);
            $table->integer('leave_hours_left')->default(0);
            $table->integer('leave_hours_left_in_month')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('leave_hours_left_in_month');
            $table->dropColumn('leave_hours_left');
            $table->dropColumn('role_id');
            $table->dropColumn('avatar');
        });
    }
}
