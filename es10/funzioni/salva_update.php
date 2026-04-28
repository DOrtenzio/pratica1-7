<?php
require_once("operazioni.php");
require_once("tok.php");
require_once("conf.php");

try{
    if(isset($_POST["token"]) && !empty($_POST["token"])){
        $arr_token=explode(":",$_POST["token"]);
        if(count($arr_token)!=2) header("location:../errorpage.html");
        if(verify_token($arr_token[0],$arr_token[1]) && isset($_POST["id_prestito"]) && !empty($_POST["id_prestito"])){
            $obj=new Operazioni($host,$dbname,$user,$psw);
            $obj->update("prestiti",["id_prestito"=>$_POST["id_prestito"],"restituito"=>1],["id_prestito"=>$_POST["id_prestito"]]);
            header("location:../index.php");
        }else{
            header("location:../errorpage.html");
        }
    }else{
        header("location:../errorpage.html");
    }
}catch(Exception $e){
    header("Location: errorpage.html");
}