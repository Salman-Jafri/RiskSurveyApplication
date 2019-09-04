<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiskSurveyInfo extends Model
{
    protected $fillable = [
        'user_id','date','time','site_name','type','managing_agent','incharge_name','site_address','company_name','signature','company_stamp'];
}
