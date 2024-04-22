<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->date('date');
            $table->time('to_time');
            $table->time('from_time');
            $table->integer('total_hours');
            $table->integer('salary_hours');
            $table->string('reason');
            $table->string('evident');
            $table->unsignedBigInteger('approver_id');
            $table->foreign('approver_id')->references('id')
                ->on('users');
            $table->text('comment')->nullable();
            $table->tinyInteger('status')->comment('1: Đăng ký OT, 2: Duyệt đăng ký, 3: Confirm lại giờ OT khi có timesheet, 4: Đã cofirm, 5: Không được duyệt, 6: Hủy');
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
        Schema::dropIfExists('overtimes');
    }
}
