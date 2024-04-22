<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropColumn(['date', 'to_time', 'from_time']);

            $table->dateTime('to_datetime');
            $table->dateTime('from_datetime');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'overtimes',
            function (Blueprint $table) {
                $table->dropColumn('to_datetime');
                $table->dropColumn('from_datetime');
            }
        );
    }
}
