<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function active(Request $request)
    {
    	if((int)$request->id > 0){
    		$model = $this->loadModel((int)$request->id);
    		if($model->status == 1){
    			$model->status = 0;
    		}else{
    			$model->status = 1;
    		}

    		if($model->save()){
    			return json_encode(array("status"=>true));
    		}
    	}

    	return json_encode(array("status"=>false));
    }    

    public function tokenDecode($content,$token){
        if(!empty($content) && !empty($token)){
            $iv = substr($token, 0, 16);
            return openssl_decrypt(
                str_replace (" ","+",$content),
                "AES-256-CBC",
                $token,
                0,
                $iv
            );
        }       
    }

    public function tokenEncode($content,$token){
        $iv = substr($token, 0, 16);
        return openssl_encrypt(
            $content,
            "AES-256-CBC",
            $token,
            0,
            $iv
        );  
    }

    public function runCurl($params,$url){
        $params = http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $data=curl_exec($ch);
        
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);      
        curl_close($ch);
        return array(
            "data" => $data,
            "code" => $httpcode,
        );   
    }

    public function getParamsByArray($array)
    {
        $params = array();

        foreach ($array as $key => $value) {
            $params[] = $key ."=". $value;
        }

        return implode(",", $params);
    }

}
