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

    function query($table,$where=[],$groupBy=[],$having=[],$orderBy=[],$select=['*']){
        if(!in_array($table, $this->whitelist)) throw new Exception("Tabella non trovata");
        
        $valori = [];
    
        // SELECT
        $sql = "SELECT ".implode(",", array_map(fn($c) => $c=='*' ? '*' : "`$c`", $select))." FROM `$table`";
    
        // WHERE
        if(!empty($where)) {
            $sql=$sql." WHERE ";
            $condizioni_where = [];
            foreach($where as $k=>$v) {
                $condizioni_where[]="`$k`=:w_$k";
                $valori[":w_$k"]=$v;
            }
            $sql .= implode(" AND ", $condizioni_where);
        }
    
        // GROUP BY
        if(!empty($groupBy)) $sql=$sql." GROUP BY ".implode(",", array_map(fn($c) => "`$c`", $groupBy));
    
        // HAVING
        if(!empty($having)) {
            $sql=$sql." HAVING ";
            $condizioni_having=[];
            foreach($having as $k=>$v) {
                $condizioni_having[] = "`$k`=:h_$k";
                $valori[":h_$k"] = $v;
            }
            $sql .= implode(" AND ", $condizioni_having);
        }
    
        // ORDER BY
        if(!empty($orderBy)) {
            $orders=[];
            foreach($orderBy as $k => $dir) {
                $dir=strtoupper($dir)=='DESC' ? 'DESC' : 'ASC';
                $orders[]="`$k` $dir";
            }
            $sql=$sql." ORDER BY " . implode(",", $orders);
        }
    
        $sql=$sql.";";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($valori);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        $condizioni=[];
        foreach($arr_id_val as $k => $v) {
            $condizioni[]="`$k`=:w_$k";
            $valori[":w_$k"]=$v;
        }

        $sql = "UPDATE `$table` SET " . implode(", ", array_map(fn($k) => "`$k` = :v_$k", array_keys($arr_att_val))) . " WHERE ".implode(" AND ",$condizioni);

        $stmt=$this->conn->prepare($sql);
        $stmt->execute($valori);
        return;
    }

    function delete($table,$arr_id_val){
        if(!in_array($table,$this->whitelist)) throw new Exception("Tabella non trovata");
        if(!is_array($arr_id_val)) throw new Exception("Errore nei id passati");

        $valori=[];

        $condizioni=[];
        foreach($arr_id_val as $k => $v) {
            $condizioni[]="`$k`=:w_$k";
            $valori[":w_$k"]=$v;
        }

        $sql="DELETE FROM `$table` WHERE ".implode(" AND ",$condizioni).";";

        $stmt=$this->conn->prepare($sql);
        $stmt->execute($valori);
        return;
    }
}