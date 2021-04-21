<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBhavcopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bhavcopies', function (Blueprint $table) {
            $table->id();
            $table->date('bhavcopy_date')->nullable();
            $table->float('prev_close')->nullable();
            $table->float('open_price')->nullable();
            $table->float('high_price')->nullable();
            $table->float('low_price')->nullable();
            $table->float('last_price')->nullable();
            $table->float('close_price')->nullable();
            $table->float('avg_price')->nullable();
            $table->integer('trd_qty')->nullable();
            $table->float('turnover')->nullable();
            $table->integer('no_of_trades')->nullable();
            $table->integer('del_qty')->nullable();
            $table->float('del_per')->nullable();
            $table->string('exchange');
            $table->unsignedBigInteger("scrip_id")->nullable();
            $table->timestamps();
            $table->foreign('scrip_id')->references('id')->on('scrips');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bhavcopies');
    }
}
