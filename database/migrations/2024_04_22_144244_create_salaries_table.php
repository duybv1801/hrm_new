<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('time');
            $table->unsignedBigInteger('gross');
            $table->unsignedBigInteger('tax')->default(0);
            $table->unsignedBigInteger('insurance')->default(0);
            $table->unsignedBigInteger('advance_payment')->default(0);
            $table->unsignedBigInteger('reward')->default(0);
            $table->unsignedBigInteger('NET')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('salaries');
    }
}
