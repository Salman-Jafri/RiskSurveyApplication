<?php

namespace App\Http\Controllers;

use App\RiskSurveyInfo;
use App\RiskSurveyLocationInfo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Validator;
use Illuminate\Validation\Rule;

class APIController extends Controller
{
    private $messages = [
        'required' => ':attribute is Required.',
    ];

    private $names = [
        'password'=> "User Password",
        'name'=> "First Name",
        'username'=> "Username",
    ];


    public function appUserLogin(Request $request)
    {
        $user = User::where('username', $request->get('username'))->first();
        if ($user)
        {
            if(Hash::check($request->get('password'), $user->password)){
                return response()->json(["status"=>1,"data" =>$user]);
            }else{
                return response()->json(["status"=>0,"message" =>'Invalid password']);

            }
        }else {
            return response()->json(["status"=>0,"message" =>'Invalid Username']);
        }
    }



    public function appUserRegister(Request $request)
    {
        $rules = [
            'username' => ['required', 'between:1,50'],
            'name' => ['required', 'between:1,190'],
            'password' => ['required', 'between:6,190'],
        ];

        $validate = Validator::make($request->all(), $rules, $this->messages);

        $validate->setAttributeNames($this->names);

        if ($validate->fails())
        {
            return response()->json(['status' => 0, 'message' => 'Error(s) in Input', 'errors' => $validate->errors()]);
        }
        else
        {

            if(User::where('username', $request->get('username'))->get()->count() > 0)
            {
                return response()->json(["status"=>0,"message" =>'The username is already in use']);
            }
            else
            {
                $user = User::create([
                        "username"=>$request->username,
                        "password" => bcrypt($request->password),
                        "name" => $request->name,
                    ]
                );
                if ($user->exists)
                {
                    return response()->json(["status"=>1,"message" =>'User Created Successfully']);
                }
                else
                {
                    return response()->json(["status"=>0,"message" =>'User could not be created']);
                }
            }
        }

        }



    public function addNewRiskSurvey(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'locations' => 'required',
            'locations.*' => 'required',
            'current_measures' => 'required',
            'current_measures.*' => 'required',
            'threats' => 'required',
            'threats.*' => 'required',
            'risks' => 'required',
            'risks.*' => 'required',
            'impacts' => 'required',
            'impacts.*' => 'required',
            'recommendations' => 'required',
            'recommendations.*' => 'required',
            'remarks' => 'required',
            'remarks.*' => 'required',
        ]);


        if ($validate->fails())
        {
            return response()->json(['status' => 0, 'message' => 'Error(s) in Input', 'errors' => $validate->errors()]);
        }

        $user = User::find($request->user_id);


        if ($request->filled('type')) {
            $type = $request->input('type');
        }
        else {
            $type = "";
        }
        if ($request->filled('managing_agent')) {
            $managing_agent = $request->input('managing_agent');
        }
        else {
            $managing_agent = "";
        }
        if ($request->filled('incharge_name')) {
            $incharge_name = $request->input('incharge_name');
        }
        else {
            $incharge_name = "";
        }

        if($file_data = $request->file('signature'))
        {
            $signature_image = $user->username . '-' . strtotime("now") .'.'. $file_data->getClientOriginalExtension();
            $file_data->move(public_path().'/assets/images/signatures/',$signature_image);
            $signature = "/assets/images/signatures/" . $signature_image;
        }
        else
        {
            $signature = "";
        }

        if($file_data = $request->file('stamp'))
        {
            $stamp_image = $user->username . '-' . strtotime("now") .'.'. $file_data->getClientOriginalExtension();
            $file_data->move(public_path().'/assets/images/stamps/',$stamp_image);
            $stamp = "/assets/images/stamps/" . $stamp_image;
        }
        else
        {
            $stamp = "";
        }

        $survey_info = RiskSurveyInfo::create([
            "user_id"=>$user->id,"date"=>$request->date,"time" =>$request->time,"site_name"=>$request->site_name,"type"=>$type,"managing_agent"=>$managing_agent,"incharge_name"=>$incharge_name,"site_address"=>$request->site_address,"company_name"=>$request->company_name,
            "signature"=>$signature,"company_stamp"=>$stamp
            ]);


        if (!$survey_info->exists)
        {
            return response()->json(["status"=>0,"message" =>'Survey Error could not be created']);
        }



        $locations = $request->locations;
        $current_measures = $request->current_measures;
        $threats = $request->threats;
        $risks = $request->risks;
        $impacts = $request->impacts;
        $recommendations = $request->recommendations;
        $remarks = $request->remarks;

        for($i=0;$i<count($locations);$i++)
        {

            if($request->hasFile('photo_1')[$i])
            {
                $file_data = $request->file('photo_1')[$i];
                $photo_1_image = $user->username . '-1' . strtotime("now") .'.'. $file_data->getClientOriginalExtension();
                $file_data->move(public_path().'/assets/images/photos/',$photo_1_image);
                $photo_1 = "/assets/images/photos/" . $photo_1_image;
            }
            else
            {
                $photo_1 = "";
            }


            if($request->hasFile('photo_2')[$i])
            {
                $file_data = $request->file('photo_2')[$i];
                $photo_2_image = $user->username . '-2' . strtotime("now") .'.'. $file_data->getClientOriginalExtension();
                $file_data->move(public_path().'/assets/images/photos/',$photo_2_image);
                $photo_2 = "/assets/images/photos/" . $photo_2_image;

                var_dump($request->file('photo_2')[$i]);

            }
            else
            {
                $photo_2 = "";
            }


            $risk_location_info = RiskSurveyLocationInfo::create([
                "risk_survey_id"=>$survey_info->id,
                "location"=>$locations[$i],
                "current_measures"=>$current_measures[$i],
                "threats"=>$threats[$i],
                "risk"=>$risks[$i],
                "impact"=>$impacts[$i],
                "recommendation"=>$recommendations[$i],
                "remarks"=>$remarks[$i],
                'photo_1'=>$photo_1,
                "photo_2"=>$photo_2
            ]);


        }

        if ($risk_location_info->exists)
        {
            return response()->json(["status"=>1,"message" =>'Risk Survey Added Successfully']);
        }
        else
        {
            return response()->json(["status"=>0,"message" =>'Error Could not be created']);
        }

    }


    public function addInitialInfo(Request $request)
    {
        $user = User::find($request->user_id);

        if ($request->filled('type')) {
            $type = $request->input('type');
        }
        else {
            $type = "";
        }
        if ($request->filled('managing_agent')) {
            $managing_agent = $request->input('managing_agent');
        }
        else {
            $managing_agent = "";
        }
        if ($request->filled('incharge_name')) {
            $incharge_name = $request->input('incharge_name');
        }
        else {
            $incharge_name = "";
        }


        $survey_info = RiskSurveyInfo::create([
            "user_id"=>$user->id,"date"=>$request->date,"time" =>$request->time,"site_name"=>$request->site_name,"type"=>$type,"managing_agent"=>$managing_agent,"incharge_name"=>$incharge_name,"site_address"=>$request->site_address,"company_name"=>"",
            "signature"=>"","company_stamp"=>""
        ]);

        return response()->json(["status"=>1,"data" =>$survey_info]);

    }

    public function addLocationInfo(Request $request)
    {
        $survey_id = $request->survey_id;


        if ($request->filled('location')) {
            $location = $request->input('location');
        }
        else {
            $location = "";
        }

        if ($request->filled('current_measure')) {
            $current_measure = $request->input('current_measure');
        }
        else {
            $current_measure = "";
        }
        if ($request->filled('risk')) {
            $risk = $request->input('risk');
        }
        else {
            $risk = "";
        }
        if ($request->filled('threat')) {
            $threat = $request->input('threat');
        }
        else {
            $threat = "";
        }

        if ($request->filled('recommendation')) {
            $recommendation = $request->input('recommendation');
        }
        else {
            $recommendation = "";
        }

        if ($request->filled('remark')) {
            $remark = $request->input('remark');
        }
        else {
            $remark = "";
        }


        if ($request->filled('impact')) {
            $impact = $request->input('impact');
        }
        else {
            $impact = "";
        }

        if($request->hasFile('photo_1'))
        {
            $file_data = $request->file('photo_1');
            $photo_1_image = rand() . '-1' . strtotime("now") .'.'. $file_data->getClientOriginalExtension();
            $file_data->move(public_path().'/assets/images/photos/',$photo_1_image);
            $photo_1 = "/assets/images/photos/" . $photo_1_image;
        }
        else
        {
            $photo_1 = "";
        }


        if($request->hasFile('photo_2'))
        {
            $file_data = $request->file('photo_2');
            $photo_2_image = rand() . '-2' . strtotime("now") .'.'. $file_data->getClientOriginalExtension();
            $file_data->move(public_path().'/assets/images/photos/',$photo_2_image);
            $photo_2 = "/assets/images/photos/" . $photo_2_image;


        }
        else
        {
            $photo_2 = "";
        }



        $risk_location_info = RiskSurveyLocationInfo::create([
            "risk_survey_id"=>$survey_id,
            "location"=>$location,
            "current_measures"=>$current_measure,
            "threats"=>$threat,
            "risk"=>$risk,
            "impact"=>$impact,
            "recommendation"=>$recommendation,
            "remarks"=>$remark,
            'photo_1'=>$photo_1,
            "photo_2"=>$photo_2
        ]);


        if ($risk_location_info->exists)
        {
            return response()->json(["status"=>1,"message" =>'Risk Location Added Successfully',"data"=>$risk_location_info]);
        }
        else
        {
            return response()->json(["status"=>0,"message" =>'Error Location not Added']);
        }

    }


    public function addRemainingInfo(Request $request)
    {

        $survey_id = $request->survey_id;

        $survey = RiskSurveyInfo::find($survey_id);

        if($survey)
        {

            if($request->hasFile('signature'))
            {
                $file_data = $request->file('signature');
                $signature_image = rand() . '-' . strtotime("now") .'.'. $file_data->getClientOriginalExtension();
                $file_data->move(public_path().'/assets/images/signatures/',$signature_image);
                $signature = "/assets/images/signatures/" . $signature_image;
            }
            else
            {
                $signature = "";
            }

            if($request->hasFile('stamp'))
            {
                $file_data = $request->file('stamp');
                $stamp_image = rand() . '-' . strtotime("now") .'.'. $file_data->getClientOriginalExtension();
                $file_data->move(public_path().'/assets/images/stamps/',$stamp_image);
                $stamp = "/assets/images/stamps/" . $stamp_image;
            }
            else
            {
                $stamp = "";
            }

            if ($request->filled('company_name')) {
                $company_name = $request->input('company_name');
            }
            else {
                $company_name = "";
            }

            $result = $survey->update([
                "signature"=>$signature,
                "company_stamp" => $stamp,
                "company_name" => $company_name,
                "is_complete" => true,
            ]);

            if ($result)
            {
                return response()->json(["status"=>1,"message" =>'Risk Survey Update Successfully',"data"=>$survey]);
            }
            else
            {
                return response()->json(["status"=>0,"message" =>'Error Not Updated']);
            }

        }
        else
        {
            return response()->json(["status"=>"fail","message" =>'Survey Not Found']);
        }


    }


    public function getSurveys(Request $request)
    {
        $user_id = $request->user_id;

        if (!$request->filled('user_id'))
        {
            return response()->json(["status"=>0,"message"=>"User ID is required"]);
        }


        if ($request->filled('date'))
        {
            $date = $request->input('date');

            $data = RiskSurveyInfo::where('user_id',$user_id)->where("date",$date)->with('locations')->get();

        }
        else
        {
            $data = RiskSurveyInfo::where('user_id',$user_id)->with('locations')->get();
        }
        if($data)
        {
            return response()->json(["status"=>1,"data"=>$data]);
        }
        else
        {
            return response()->json(["status"=>0,"message"=>"No record found"]);
        }




    }
}
