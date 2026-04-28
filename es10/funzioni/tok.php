<?php
/*
CREATE TABLE tokens (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    type VARCHAR(50),
    expires_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    revoked BOOLEAN DEFAULT FALSE,
    
    UNIQUE (token)
);
*/

require_once("operazioni.php");
function create_token($page): string{
    require_once("conf.php");

    try{
        $obj = new Operazioni($host,$dbname,$user,$psw);
        $
    }catch(Exception $e){
        header("Location: errorpage.html");
    }
    $arr=explode("/",$page);
    return $arr[count($arr)-1].":".password_hash("secret",PASSWORD_DEFAULT);
}

function verify_token($page,$hash): bool{
    return in_array($page,["index.php","inserimento.php","inserimento_libro.php"]) && password_verify("secret",$hash);
}