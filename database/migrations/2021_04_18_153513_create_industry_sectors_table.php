<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndustrySectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industry_sector', function (Blueprint $table) {            
            $table->id();            
            $table->bigInteger('sector_id')->unsigned();
            $table->bigInteger('industry_id')->unsigned();
            $table->timestamps();
            $table->foreign('sector_id')
                ->references('id')
                ->on('sectors');
             $table->foreign('industry_id')
                ->references('id')
                ->on('industries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('industry_sectors');
    }
}
