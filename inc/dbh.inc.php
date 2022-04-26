<?php 
!isset($include) ? die(json_encode(array("success" => false, "message" => "Access denied"))) : null;

class Database
{
    public $pdo;
    private $stmt;

    private function connect()
    {
        try {
            $this->pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8;dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            die("X_X");
        }
    }

    public function select($query, $params=array(), $all=false, $count=false)
    {
        $this->connect();
        $this->stmt = $this->pdo->prepare($query);
        $this->stmt->execute($params);

        if(!$count){
            return !$all ? $this->stmt->fetch(PDO::FETCH_ASSOC) : $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return $this->stmt->rowCount();
        }
    }
}

?>