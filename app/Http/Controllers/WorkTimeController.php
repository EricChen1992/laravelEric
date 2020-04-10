<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PunchTime;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
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

        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        // $spreadsheet->setActiveSheetIndex(1);//設定分頁 0為第一頁
        $spreadSheet->getActiveSheet()->setTitle('Hello12342536'); //設定分頁名稱

        // $sheet -> setAutoSize(true);
        $sheet -> freezePane('A2');//起始頭
        $sheet -> setCellValue('A1', '姓名')//設定A1內容
               -> setCellValue('B1', 'cua_id')//設定B1
               -> setCellValue('C1', '日期')//設定C1
               -> setCellValue('D1', '上班')//設定D1
               -> setCellValue('E1', "下班");//設定E1
        // $timetable_date = PunchTime::where('date','REGEXP','-'.date('m').'-')->select('date')->distinct()->get();//distinct() 去
        $timetable_all = PunchTime::where('date','REGEXP','-'.date('m').'-')->select('cua_id','name','date','time_clock_in','time_clock_out')->get();
        // $tmpDateJson = json_decode($timetable_date);
        $tmpJson = json_decode($timetable_all);
        $tempNum = 2;
        for($i=0 ; $i < sizeof($timetable_all)  ; $i++){
            $tempArray = array($tmpJson[$i]->cua_id, $tmpJson[$i]->name, $tmpJson[$i]->date, $tmpJson[$i]->time_clock_in, $tmpJson[$i]->time_clock_out);
            $sheet -> fromArray($tempArray, null, 'A'.($tempNum + $i ) );
        }
        // $sheet -> fromArray(['Eric','1026','2020-04-09','08:55','19:00'],null,'A2'); //以陣列去塞
        // $cellA2 = $sheet->getCell('A2');
        // echo 'Value: ', $cellA2->getValue(), '; Address: ', $cellA2->getCoordinate(), PHP_EOL;

        // $writer = new Xlsx($spreadSheet);
        // ob_end_clean();
        // $writer->save('testExcel.xlsx');
        //  dd($writer);
        // exit();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="textExport.xls"');//設定檔名
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadSheet, 'Xls');
        $writer->save('php://output');
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
