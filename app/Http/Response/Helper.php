<?php
namespace App\Http\Response;

use Illuminate\Http\Response;

trait Helper{

    protected function successData($msg, $data = [], $status = 200){
        return array('status' => $status, 'msg' => $msg, 'data' => $data);
    }
    
    protected function errorData($msg, $data = '', $status = 0){
        return array('status' => $status, 'msg' => $msg, 'data' => $data);
    }
    
}