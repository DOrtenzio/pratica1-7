<?php
require_once("operazioni.php");

if(isset($_POST["id_prestito"]) && !empty($_POST["id_prestito"]) && isset($_POST["id_libro"]) && !empty($_POST["id_libro"]) && isset($_POST["id_utente"]) && !empty($_POST["id_utente"]) && isset($_POST["data_inizio"]) && !empty($_POST["data_inizio"]) && isset($_POST["data_fine_prevista"]) && !empty($_POST["data_fine_prevista"]) && isset($_POST["restituito"]) && !empty($_POST["restituito"])){
    $obj=new Operazioni("localhost","dortenzio_biblioteca","root","");
    $obj->update("prestiti",["id_prestito"=>$_POST["id_prestito"],"id_libro"=>$_POST["id_libro"],"id_utente"=>$_POST["id_utente"],"data_inizio"=>$_POST["data_inizio"],"data_fine_prevista"=>$_POST["data_fine_prevista"],"restituito"=>1],["id_prestito"=>$_POST["id_prestito"]]);
    header("location:../index.php");
}else{
    header("location:../errorpage.html");
}