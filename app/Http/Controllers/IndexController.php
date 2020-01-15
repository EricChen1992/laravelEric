<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function main(){
        return "Eric";
    }

    public function main123(){
        return "Eric123";
    }

    public function mainView(){
        return view('testWelcome',['name' => 'Eric']);
    }
}
