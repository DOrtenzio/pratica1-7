<?php
if(session_status()!==PHP_SESSION_ACTIVE) session_start();

if(isset($_POST["filtro_utente"]) && !empty($_POST["filtro_utente"])){
    $_SESSION["filtro_utente"]=$_POST["filtro_utente"];
    header("location:../index.php");
}else{
    header("location:../errorpage.html");
}