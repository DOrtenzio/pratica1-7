<?php
require_once("operazioni.php");
$obj=new Operazioni("localhost","dortenzio_biblioteca","root","");

if(isset($_POST["id_libro"]) && !empty($_POST["id_libro"]) && isset($_POST["id_utente"]) && !empty($_POST["id_utente"]) && isset($_POST["data_inizio"]) && !empty($_POST["data_inizio"]) && isset($_POST["data_fine_prevista"]) && !empty($_POST["data_fine_prevista"])){
    $valcompl=0;
    if(isset($_POST["restituito"])) $valcompl=1;

    $obj->insert("prestiti",["id_libro"=>$_POST["id_libro"],"id_utente"=>$_POST["id_utente"],"data_inizio"=>$_POST["data_inizio"],"data_fine_prevista"=>$_POST["data_fine_prevista"],"restituito"=>$valcompl]);
    header("location:../index.php");
}else if(isset($_POST["titolo"]) && !empty($_POST["titolo"]) && isset($_POST["anno_pubblicazione"]) && !empty($_POST["anno_pubblicazione"]) && isset($_POST["isbn"]) && !empty($_POST["isbn"]) && isset($_POST["id_autore"]) && !empty($_POST["id_autore"])){
    $obj->insert("libri",["titolo"=>$_POST["titolo"],"anno_pubblicazione"=>$_POST["anno_pubblicazione"],"isbn"=>$_POST["isbn"],"id_autore"=>$_POST["id_autore"]]);
    header("location:../index.php");
}else{
    header("location:../errorpage.html");
}