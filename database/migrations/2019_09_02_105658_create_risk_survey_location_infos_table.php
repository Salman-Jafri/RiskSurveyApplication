<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiskSurveyLocationInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk_survey_location_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('risk_survey_id');
            $table->string("location");
            $table->text("current_measures");
            $table->text("threats");
            $table->string("risk");
            $table->string("impact");
            $table->text("recommendation");
            $table->text("remarks");
            $table->string("photo_1");
            $table->string("photo_2");
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
        Schema::dropIfExists('risk_survey_location_infos');
    }
}
