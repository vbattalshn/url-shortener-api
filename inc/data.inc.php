<?php 
!isset($include) ? die(json_encode(array("success" => false, "message" => "Access denied"))) : null;

class Data extends Database
{
    public $msg;
    public $functions;

    public function __construct()
    {
        $this->functions = new Functions;
    }

    public function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min;
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1;
        $bits = (int) $log + 1;
        $filter = (int) (1 << $bits) - 1;
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; 
        } while ($rnd > $range);
        return $min + $rnd;
    }

    public function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet);

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
        }

        return $token;
    }

    public function shorUrl($url)
    {
        if(!$this->functions->checkEmpty($url)){
            return $this->feedback(false, "Url is not found");
            die();
        }
        
        $token = $this->getToken(5);
        
        if(!$this->select("SELECT `urlCode` FROM `urls`  WHERE `urlCode` = ?", array($token), false, true) == 0){
            return $this->shorUrl($url);
            die();
        }

        $isShorten = $this->select("INSERT INTO `urls`(`url`, `urlCode`, `createdIp`, `createdDate`, `createdUser`, `analyse`) VALUES (?,?,?,?,?,?)", array($url, $token, $this->functions->getIpAddress(), $this->functions->getDate(), "", json_encode(array())), false, true);

        if($isShorten == 0){
            return $this->feedback(false, "An error occurred, try again");
            die();
        }

        $this->msg["success"] = true;
        $this->msg["message"] = "Url is shorten";
        $this->msg["token"] = $token;
        return $this->msg;                
    }

    public function feedback($suc, $msg)
    {
        $this->msg["success"] = $suc;
        $this->msg["message"] = $msg;
        return $this->msg;
    }

    public function getUrl($token)
    {
        $url = $this->select("SELECT `url` FROM `urls`  WHERE `urlCode` = ?", array($token));
        if($url){
            $this->msg["success"] = true;
            $this->msg["message"] = "Url is found";
            $this->msg["url"] = $url["url"];
            return $this->msg;                    
        }else{
            $this->feedback(false, "Url is not found.");
        }
    }
}

?>