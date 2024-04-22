<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->datetime('from_datetime');
            $table->datetime('to_datetime');
            $table->tinyInteger('type')->comment('1: Có lương,2: chị em, 3: Không lương, 4: Nghỉ chế độ(Cưới, đám ma...), 5: Nghỉ bảo hiểm.');
            $table->integer('total_hours');
            $table->string('reason')->comment('Lý do');
            $table->string('evident')->comment('Đường dẫn ảnh bằng chứng');
            $table->unsignedBigInteger('approver_id');
            $table->foreign('approver_id')->references('id')
                ->on('users');
            $table->text('comment')->nullable();
            $table->tinyInteger('status')->comment('1: Đang xử lý, 2: Đã duyệt, 3: Không cho phép, 4: Hủy')->default(1);
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
        Schema::dropIfExists('leaves');
    }
}
