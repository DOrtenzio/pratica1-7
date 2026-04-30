<?php
class Operazioni{
    private PDO $conn;
    private array $whitelist=["prestiti","utenti","libri","autori","tokens"];

    function __construct(){
        $conf=require("conf.php");

        try{
            $this->conn= new PDO("mysql: host=".$conf["host"]."; dbname=".$conf["dbname"],$conf["user"],$conf["psw"]);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    function query($table): array{
        if(!in_array($table,$this->whitelist)) throw new Exception("Tabella non trovata");
        $stmt=$this->conn->prepare("SELECT * FROM `$table`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function query_where($table,$arr_id){
        if(!in_array($table,$this->whitelist)) throw new Exception("Tabella non trovata");
        if(!is_array($arr_id)) throw new Exception("Dati non validi");

        $valori=[];
        foreach($arr_id as $k=>$v) $valori[":w_$k"]=$v;

        $sql="SELECT * FROM `$table` WHERE ";
        foreach($arr_id as $k=>$v){
            $sql=$sql."$k=:w_$k AND";
        }
        $sql=substr($sql,0,-3).";";


        $stmt=$this->conn->prepare($sql);
        $stmt->execute($valori);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function query_count($table,$arr_id,$arr_groupby){
        if(!in_array($table,$this->whitelist)) throw new Exception("Tabella non trovata");
        if(!is_array($arr_id)) throw new Exception("Dati non validi");
        if(!is_array($arr_groupby)) throw new Exception("Dati non validi");

        $valori=[];
        foreach($arr_id as $k=>$v) $valori[":w_$k"]=$v;

        $sql="SELECT COUNT(*) as totale FROM `$table` WHERE ";
        foreach($arr_id as $k=>$v){
            $sql=$sql."$k=:w_$k AND";
        }
        $sql=substr($sql,0,-3)." GROUP BY ".implode(",",array_map(fn($k) => "`$k`", $arr_groupby)).";";


        $stmt=$this->conn->prepare($sql);
        $stmt->execute($valori);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function insert($table,$arr_att_val){
        if(!in_array($table,$this->whitelist)) throw new Exception("Tabella non trovata");
        if(!is_array($arr_att_val)) throw new Exception("Errore nei valori passati");

        $valori=[];
        foreach($arr_att_val as $chiave => $valore) $valori[":$chiave"]=$valore;

        $stmt=$this->conn->prepare("INSERT INTO `$table`(".implode(",", array_map(fn($k) => "`$k`", array_keys($arr_att_val))).") VALUES (".implode(",",array_keys($valori)).")");
        $stmt->execute($valori);
        return $this->conn->lastInsertId();
    }

    function update($table,$arr_att_val,$arr_id_val){
        if(!in_array($table,$this->whitelist)) throw new Exception("Tabella non trovata");
        if(!is_array($arr_att_val)) throw new Exception("Errore nei valori passati");
        if(!is_array($arr_id_val)) throw new Exception("Errore nei id passati");

        $valori = [];
        foreach($arr_att_val as $k => $v) $valori[":v_$k"] = $v;

        $sql = "UPDATE `$table` SET " . implode(", ", array_map(fn($k) => "`$k` = :v_$k", array_keys($arr_att_val))) . " WHERE ";
        foreach($arr_id_val as $k => $v) {
            $sql .= "`$k` = :w_$k AND ";
            $valori[":w_$k"] = $v;
        }
        $sql = substr($sql, 0, -5);

        $stmt=$this->conn->prepare($sql);
        $stmt->execute($valori);
        return;
    }

    function delete($table,$arr_id_val){
        if(!in_array($table,$this->whitelist)) throw new Exception("Tabella non trovata");
        if(!is_array($arr_id_val)) throw new Exception("Errore nei id passati");

        $valori=[];

        $sql="DELETE FROM `$table` WHERE ";
        foreach($arr_id_val as $k => $v){
            $sql=$sql."`$k`=:$k AND";
            $valori[":$k"]=$v;
        }
        $sql=substr($sql,0,-4).";";

        $stmt=$this->conn->prepare($sql);
        $stmt->execute($valori);
        return;
    }
}