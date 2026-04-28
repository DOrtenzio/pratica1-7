<?php
require_once("operazioni.php");
require_once("tok.php");
require_once("conf.php");

$obj=null;
try{
    $obj = new Operazioni($host,$dbname,$user,$psw);
}catch(Exception $e){
    header("Location: errorpage.html");
}

if(isset($_POST["token"]) && !empty($_POST["token"])){
    $arr_token=explode(":",$_POST["token"]);
    if(count($arr_token)!=2) header("location:../errorpage.html");  
    if(verify_token($arr_token[0],$arr_token[1]) && isset($_POST["id_libro"]) && !empty($_POST["id_libro"]) && isset($_POST["id_utente"]) && !empty($_POST["id_utente"]) && isset($_POST["data_inizio"]) && !empty($_POST["data_inizio"]) && isset($_POST["data_fine_prevista"]) && !empty($_POST["data_fine_prevista"])){
        $valcompl=0;
        if(isset($_POST["restituito"])) $valcompl=1;

        $obj->insert("prestiti",["id_libro"=>$_POST["id_libro"],"id_utente"=>$_POST["id_utente"],"data_inizio"=>$_POST["data_inizio"],"data_fine_prevista"=>$_POST["data_fine_prevista"],"restituito"=>$valcompl]);
        header("location:../index.php");
    }else if(verify_token($arr_token[0],$arr_token[1]) && isset($_POST["titolo"]) && !empty($_POST["titolo"]) && isset($_POST["anno_pubblicazione"]) && !empty($_POST["anno_pubblicazione"]) && isset($_POST["isbn"]) && !empty($_POST["isbn"]) && isset($_POST["id_autore"]) && !empty($_POST["id_autore"])){
        $obj->insert("libri",["titolo"=>$_POST["titolo"],"anno_pubblicazione"=>$_POST["anno_pubblicazione"],"isbn"=>$_POST["isbn"],"id_autore"=>$_POST["id_autore"]]);
        header("location:../index.php");
    }else{
        header("location:../errorpage.html");
    }
}else{
    header("location:../errorpage.html");
}
