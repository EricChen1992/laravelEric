<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PunchTime;

class WorkTimeController extends Controller
{
    public function workclockin(Request $request){

        $timetable = new PunchTime();
        $timetable->fill($request->post());
        $timetable->date = date('Y-m-d');
        $timetable->time_clock_in = date('H:i:s');
        $timetable->time_clock_out = '';
        $timetable->save();
        echo($timetable->toJson());

    }

    public function workclockout(Request $request){

        $name = $request->input('name');
        $type = $request->input('type');

        $timetable = PunchTime::where('name',$name)->where('date',date('Y-m-d'))->where('type','1')->update(['time_clock_out' => date('H:i:s'),'type'=>$type]);
        
        echo (PunchTime::where('name',$name)->where('date',date('Y-m-d'))->where('type','0')->get());
    }

    public function getUserAllData(Request $request){

        $timetable_Id = $request->input('cua_id');
        $timetable_name = $request->input('cua_name');

        $timetable_all = PunchTime::where('cua_id', $timetable_Id)->where('name', $timetable_name)->get();
        echo($timetable_all);

    }

    public function getTime(){
        echo date('Y-m-d');
    }

    public function downloadExcel(Request $request){
    

    }

    public function getCua(Request $request){
        $username = $request->input('cuaemail');
        $password = $request->input('cuapassword');
        $params = array(
            "user" => $username,
            "password" => $password,
            "service" => 11
        );
        $url = config('app.cuaUrl') . "api/login/service";
        $loginresult = $this->runCurl($params, $url);
        $loginResultToTokenDecode = $this->tokenDecode($loginresult, config('app.serviceToken'));
        $loginResultToJsonDecode = json_decode($loginResultToTokenDecode);
        if($loginResultToJsonDecode->status != 1){
            $responJson = json_encode(array('status'=>$loginResultToJsonDecode->status,'msg'=>$loginResultToJsonDecode->msg));
            echo $responJson;
        } else {
            $loginToJsonResult = $this->tokenDecode($loginResultToJsonDecode->token, config('app.serviceToken'));//取得Result 的 token 進行JsonDecode.
            echo $loginToJsonResult;
        }
        
    }

    public static function runCurl($params, $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $data;
    }

    public function tokenDecode($content, $token) {
        if (!empty($content) && !empty($token)) {
            $iv = substr($token, 0, 16);
            return openssl_decrypt(
                str_replace (" ", "+", $content),
                "AES-256-CBC",
                $token,
                0,
                $iv
            );
        }       
    }
}
