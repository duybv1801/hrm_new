<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInOutFogetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_out_forgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->date('date');
            $table->time('in_time');
            $table->time('out_time');
            $table->integer('total_hours');
            $table->string('reason');
            $table->string('evident');
            $table->unsignedBigInteger('approver_id');
            $table->foreign('approver_id')->references('id')
                ->on('users');
            $table->text('comment');
            $table->tinyInteger('status')->comment('1: Đang xử lý, 2: Đã duyệt, 3: Không cho phép, 4: Xin hủy, 5: Hủy');
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
        Schema::dropIfExists('in_out_fogets');
    }
}
