<?php 
!isset($include) ? die(json_encode(array("success" => false, "message" => "Access denied"))) : null;

class Functions
{
    public function checkEmpty($data){
        return !is_null($data) && $data != "" ? true : false;
    }
    
    public function getIpAddress(){
        return $_SERVER["REMOTE_ADDR"];
    }

    public function getDate(){
        return date("Y-m-d H:i:s");
    }
}
?>