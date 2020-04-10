<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PunchTime;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        // $spreadsheet = new Spreadsheet();//实例化
        // $spreadsheet->setActiveSheetIndex(0);//设置excel的索引
        // $sheet=$spreadsheet->getActiveSheet();
        // /*设置单元格列宽*/
        // $sheet->getColumnDimension('A')->setWidth(20);
        // $sheet->getColumnDimension('B')->setWidth(15);
        // $sheet->getColumnDimension('C')->setAutoSize(true);
        // /*设置字体大小*/
        // $sheet->getStyle('A1:c1')->getFont()->setBold(true)->setName('Arial')->setSize(10);
        // //锁定表头
        // $sheet->freezePane('A2');
        // $sheet->setCellValue('A1','答卷时间')
        //     ->setCellValue('B1','答卷人姓名')
        //     ->setCellValue('C1','答案文本');

        // $sheet->fromArray($result,null,'A2');
        // $writer = new Xls($spreadsheet);
        // $pathUrl = public_path(). '\excel/';
        // $filePath=$pathUrl.$fileName;
        // //判断目录是否存在，如果不存在就新建
        // if(!is_dir($pathUrl))
        //     mkdir($pathUrl,0755,true);

        // $spreadsheet = new Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setCellValue('A1', 'Hello World !');

        // $writer = new Xlsx($spreadsheet);
       
        // $writer->save('hi world.xlsx');

//#################################################################

        // $spreadSheet = new Spreadsheet();
        // $workSheet = $spreadSheet->getActiveSheet();

        // Set details for the formula that we want to evaluate, together with any data on which it depends
        // $workSheet->fromArray(
        //     [1, 2, 3],//[A,B,C]
        //     null,//unknow
        //     'A2'//Start
        // );

        // $cellC1 = $workSheet->getCell('C2');
        // echo 'Value: ', $cellC1->getValue(), '; Address: ', $cellC1->getCoordinate(), PHP_EOL;

        // $cellA1 = $workSheet->getCell('A2');
        // echo 'Value: ', $cellA1->getValue(), '; Address: ', $cellA1->getCoordinate(), PHP_EOL;

        // $writer = new Xlsx($spreadSheet);
        // $writer->save('TEST_FORMARRAY.xlsx');
//###################################################################
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        
        // $sheet -> setAutoSize(true);
        // $sheet -> freezePane('A2');//起始頭
        $sheet -> setCellValue('A1', '姓名')//設定A1內容
               -> setCellValue('B1', 'cua_id')//設定B1
               -> setCellValue('C1', '上班')//設定B1
               -> setCellValue('D1', "下班");//設定C1

        $timetable_all = PunchTime::select('cua_id','name','time_clock_in','time_clock_out')->get();
        $tmpJson = json_decode($timetable_all);
        $tempNum = 2;
        for($i=0 ; $i < sizeof($timetable_all)  ; $i++){
            $tempArray = array($tmpJson[$i]->name, $tmpJson[$i]->cua_id, $tmpJson[$i]->time_clock_in, $tmpJson[$i]->time_clock_out);
            $sheet -> fromArray($tempArray, null, 'A'.($tempNum + $i ) );
        }
        // $sheet -> fromArray(['Eric','08:55','19:00'],null,'A2'); //以陣列去塞
        $writer = new Xlsx($spreadSheet);
        $writer->save('TEST_FORMARRAY.xlsx');
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
