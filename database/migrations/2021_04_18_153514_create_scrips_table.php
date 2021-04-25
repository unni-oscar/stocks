<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrips', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('name');
            $table->string('series')->nullable();
            $table->string('status')->nullable();            
            $table->string('trading_status')->nullable();
            $table->bigInteger('issuedCap')->nullable();
            $table->integer('bse_code')->nullable();
            $table->string('isin_no');
            $table->date('listing_date')->nullable();
            $table->string('group')->nullable();
            $table->integer('faceValue')->nullable();
            $table->unsignedBigInteger("industry_sector_id")->nullable();            
            $table->timestamps();
            $table->foreign('industry_sector_id')->references('id')->on('industry_sector');                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scrips');
    }
}
