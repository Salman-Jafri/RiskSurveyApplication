<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsCompletedFlagToRiskSurveyInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risk_survey_infos', function (Blueprint $table) {
            $table->boolean("is_complete")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risk_survey_infos', function (Blueprint $table) {
            $table->dropColumn("is_complete");
        });
    }
}
