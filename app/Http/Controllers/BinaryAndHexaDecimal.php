<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;


class BinaryAndHexaDecimal extends Controller
{
    public function doSomthing(Request $request, $decimal, $math = 2){
        /* 傳址 */
        // $arrayValue = array($decimal,'123');
        // $this->changeArrayValue($arrayValue);
        // $this->showArrayValue($arrayValue);

        /*取的?後面的參數*/ 
        $this->getParameters($request);

        /*1/3 Home Work */
        $this->checkType($decimal, $math); //check input variable is a number or a numeric string.

        switch ($math) {
            case '2':
                return $this->getChange($decimal, $math);
                break;
            case '8':
                return $this->getChange($decimal, $math);
                break;
            case '16':
                return $this->getChange($decimal, $math);
                break;
            case '99':
                if(View::exists('testWelcome'))
                    return view('testWelcome',['name' => 'Eric']);
                    // dd(view('testWelcome',['name' => 'Eric']));
                break;
            case '100':
                return $this->showView();
                break; 
            default:
                dump("You enter math is error!!!");
                break;
        }
    }

    private function getChange(int $decimal, $math){
        dd("$decimal 的 $math 進制是 ".base_convert($decimal, 10, $math));
    }

    private function checkType($num_decimal, $math_math){
        if (!is_numeric($num_decimal)) abort(403,"You enter decimal variable is not int Type");
        if (!is_numeric($math_math)) abort(403,"You enter math variable is not int Type");
    }

    private function getParameters($request){
        $parameters = $request->get('first');
        if ($parameters != null ) dump("First = $parameters");
    }

    private function showView(){
        if(View::exists('testWelcome'))
            return view('testWelcome',['name' => 'Eric']);
            // dd(view('testWelcome',['name' => 'Eric']));
    }

    private function changeArrayValue(&$arrayValue){
        $arrayValue[1] = '456';
    }

    private function showArrayValue($arrayValue){
        dump("Value[0] = $arrayValue[0] ------ Value[1] = $arrayValue[1]");
    }
}
