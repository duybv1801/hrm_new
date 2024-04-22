<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('code')->nullable();
            $table->date('start_date')->nullable()->useCurrent();
            $table->date('offical_start_date')->nullable()->useCurrent();
            $table->integer('dependent_person')->default(0);
            $table->tinyInteger('gender')->default(1);
            $table->tinyInteger('contract')->default(1);
            $table->date('birthday')->nullable();
            $table->string('phone', 100)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('position')->default(1);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->softDeletes();
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
            $table->dropColumn('deleted_at');
            $table->dropForeign('users_user_id_foreign');
            $table->dropColumn('user_id');
            $table->dropColumn('position');
            $table->dropColumn('status');
            $table->dropColumn('phone');
            $table->dropColumn('birthday');
            $table->dropColumn('contract');
            $table->dropColumn('gender');
            $table->dropColumn('dependent_person');
            $table->dropColumn('offical_start_date');
            $table->dropColumn('start_date');
            $table->dropColumn('code');
        });
    }
}
