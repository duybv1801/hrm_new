<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->date('record_date')->comment('Ngày chấm công');
            $table->time('in_time')->comment('Giờ vào xác nhận');
            $table->time('out_time')->coment('Giờ ra xác nhận');
            $table->time('check_in')->default('00:00')->comment('Giờ check in thực tế');
            $table->time('check_out')->default('00:00')->comment('Giờ check out thực tế');
            $table->tinyInteger('status');
            $table->integer('working_hours')->default(0);
            $table->integer('overtime_hours')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheets');
    }
}
