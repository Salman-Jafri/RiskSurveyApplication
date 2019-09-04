<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiskSurveyLocationInfo extends Model
{
    protected $fillable = [
        'risk_survey_id','location','current_measures','threats','risk','impact','recommendation','remarks','photo_1','photo_2'];

}
