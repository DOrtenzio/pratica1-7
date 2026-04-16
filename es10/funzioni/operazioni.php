<?php
class Operazioni{
    private PDO $conn;
    private array $whitelist=["prestiti","utenti","libri","autori"];

    function __construct($host,$dbname,$user,$psw){
        try{
            $this->conn= new PDO("mysql: host=$host; dbname=$dbname",$user,$psw);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    //query
    function query($table): array{
        if(!in_array($table,$this->whitelist)) throw new Exception("Tabella non trovata");
        $stmt=$this->conn->prepare("SELECT * FROM `$table`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //insert delete update
    function insert($table,$arr_att_val){
        if(!in_array($table,$this->whitelist)) throw new Exception("Tabella non trovata");
        if(!is_array($arr_att_val)) throw new Exception("Errore nei valori passati");

        $valori=[];
        foreach($arr_att_val as $chiave => $valore) $valori[":$chiave"]=$valore; //chiave è il nome della colonna e valore è cio da inserire

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