<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmploymentDatesToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('official_employment_date')->nullable();
            $table->date('resignation_date')->nullable();
            $table->date('leave_update_at')->nullable();
            $table->date('resignation_update_at')->nullable();
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
            $table->dropColumn('official_employment_date');
            $table->dropColumn('resignation_date');
            $table->dropColumn('leave_update_date');
            $table->dropColumn('resignation_update_at');
        });
    }
}
