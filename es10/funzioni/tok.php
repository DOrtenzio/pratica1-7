<?php
require_once("operazioni.php");
function create_token(){
    try{
        $obj = new Operazioni();
        $token=bin2hex(random_bytes(10));
        $obj->insert("tokens",[
            "token"=>password_hash($token,PASSWORD_DEFAULT),
            "type"=> "operation"]);
        return $token;
    }catch(Exception $e){
        header("Location: ../errorpage.html");
    }
}

function verify_token($token){
    try{
        $obj = new Operazioni();
        foreach($obj->query("tokens") as $tok){
            if(password_verify($tok["token"],$token) && time()>(strtotime($tok["created_at"])+15)){
                $obj->delete("tokens",["token"=>$tok["token"]]);
                return true;
            }
        }
        return false;
    }catch(Exception $e){
        header("Location: ../errorpage.html");
    }
}