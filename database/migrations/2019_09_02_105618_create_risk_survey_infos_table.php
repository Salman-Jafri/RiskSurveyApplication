<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiskSurveyInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk_survey_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->date('date');
            $table->time('time');
            $table->string('site_name');
            $table->string('type');
            $table->string('managing_agent');
            $table->string('incharge_name');
            $table->text('site_address');
            $table->string('company_name');
            $table->string('signature');
            $table->string('company_stamp');
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
        Schema::dropIfExists('risk_survey_infos');
    }
}
