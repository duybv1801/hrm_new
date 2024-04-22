<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSalaryUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_number', 10)->unique()->nullable()->after('email');
            $table->unsignedInteger('base_salary')->nullable()->after('account_number');
            $table->unsignedInteger('allowance')->nullable()->after('base_salary');
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
            $table->dropColumn('account_number');
            $table->dropColumn('base_salary');
            $table->dropColumn('allowance');
        });
    }
}
