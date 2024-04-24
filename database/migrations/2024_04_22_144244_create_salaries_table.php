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
            $table->unsignedTinyInteger('user_id');
            $table->date('time');
            $table->unsignedInteger('gross');
            $table->float('required_time', 3, 2);
            $table->float('total_time', 3, 2);
            $table->unsignedInteger('tax')->default(0)->nullable();
            $table->unsignedInteger('insurance')->default(0)->nullable();
            $table->unsignedInteger('advance_payment')->default(0)->nullable();
            $table->unsignedInteger('reward')->default(0)->nullable();
            $table->unsignedInteger('net')->default(0);
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
