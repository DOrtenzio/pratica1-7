<?php
function create_token($page): string{
    $arr=explode("/",$page);
    return $arr[count($arr)-1].":".password_hash("secret",PASSWORD_DEFAULT);
}

function verify_token($page,$hash): bool{
    return in_array($page,["index.php","inserimento.php","inserimento_libro.php"]) && password_verify("secret",$hash);
}