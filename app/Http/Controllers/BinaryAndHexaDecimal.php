<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BinaryAndHexaDecimal extends Controller
{
    public function doSomthing(Request $request, $decimal, $math = 2){
        
        $this->checkType($decimal, $math);

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
            default:
                dump("You enter math is error!!!");
                break;
        }
    }

    private function getChange(int $decimal, $math){
        dd(base_convert($decimal, 10, $math));
    }

    private function checkType($num_decimal, $math_math){
        
        if(!is_numeric($num_decimal)) abort(403,"You enter decimal variable is not int Type");
        if(!is_numeric($math_math)) abort(403,"You enter math variable is not int Type");
    }
}
