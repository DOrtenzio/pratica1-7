<?php
require_once("funzioni/operazioni.php");
require_once("funzioni/tok.php");
require_once("funzioni/conf.php");

if(session_status()!==PHP_SESSION_ACTIVE) session_start();
if(!isset($_SESSION["filtro_utente"]))  $_SESSION["filtro_utente"]="all";

$obj=null;
try{
    $obj = new Operazioni($host,$dbname,$user,$psw);
}catch(Exception $e){
    header("Location: errorpage.html");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; margin: 20px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:hover { background-color: #f9f9f9; }
        .actions { display: flex; gap: 5px; }
        button, input[type="submit"] { cursor: pointer; padding: 5px 10px; }
        .btn-add { background: #28a745; color: white; border: none; padding: 10px 15px; text-decoration: none; border-radius: 4px; }
        .btn { background: #f4f4f4; color: black; border: none; padding: 10px 15px; text-decoration: none; border-radius: 4px; }
        .toolbar { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            background: #fff; 
            padding: 15px; 
            border-radius: 8px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .separator {
            width: 1px;
            height: 30px;
            background-color: #ddd;
            margin: 0 10px;
        }
        .toolbar form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }
        .btn-filter {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1>Gestione Prestiti</h1>
    <p>Benvenuto/a</p>

    <div class="toolbar">
        <a href="funzioni/inserimento.php" class="btn-add">+ Nuovo Prestito</a>
        <a href="funzioni/inserimento_libro.php" class="btn-add">+ Nuovo Libro</a>
        <div class="separator"></div>
        <form action="funzioni/filtra.php" method="post">
            <select name="filtro_utente">
                <option value="all">Vedi Tutto</option>
                <?php foreach($obj->query("utenti") as $u) echo "<option value='".$u["id_utente"]."'>".$u["email"]."</option>"; ?>
            </select>
            <input type="submit" value="Filtra" name="Filtra" class="btn-filter">
        </form>
    </div>


    <table>
        <thead>
            <tr>
                <th>LIBRO</th>
                <th>UTENTE</th>
                <th>DATA_INIZIO</th>
                <th>DATA_FINE</th>
                <th>RESTITUITO</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $utenti_assoc = [];
                foreach($obj->query("utenti") as $u) { $utenti_assoc[$u["id_utente"]] = $u; }

                $libr_assoc = [];
                foreach($obj->query("libri") as $l) { $libr_assoc[$l["id_libro"]] = $l; }

                foreach($obj->query("prestiti") as $prestiti) {
                    if($_SESSION["filtro_utente"]==="all" || $_SESSION["filtro_utente"]==$prestiti["id_utente"]){
                        $checked = ($prestiti["restituito"] == 1) ? "checked" : "";
                        $stato = ($prestiti["restituito"] == 1) ? "Restituito" : "Non Restituito";
                        echo "<tr>";
                        echo "<td>" .$libr_assoc[$prestiti["id_libro"]]["titolo"]. "</td>";
                        echo "<td>" .$utenti_assoc[$prestiti["id_utente"]]["email"]. "</td>";
                        echo "<td>" .$prestiti["data_inizio"]."</td>";
                        echo "<td>" .$prestiti["data_fine_prevista"]."</td>";
                        echo "<td><input type='checkbox' $checked disabled> $stato</td>";
                        if($prestiti["restituito"] == 0){
                            echo "<td class='actions'>
                                <form action='funzioni/salva_update.php' method='post' id='formRest'>
                                    <input type='hidden' name='token' value='".create_token($_SERVER['REQUEST_URI'])."'>
                                    <input type='hidden' name='id_prestito' value='".$prestiti["id_prestito"]."'> 
                                    <input type='hidden' name='restituito' value='".$prestiti["restituito"]."'>
                                    <input type='submit' value='Restituisci' name='Restituisci'>
                                </form>
                            </td>";
                        } else{
                            echo "<td>Non ci sono azioni disponibili</td>";
                        }
                        echo "</tr>";
                    }
                }
            } catch(Exception $e) {
                echo "<tr><td colspan='5' style='color:red;'>Errore nel caricamento dati.</td></tr>";
            }

            ?>
        </tbody>
    </table>
    <script>
        document.getElementById("formRest").addEventListener("submit", function(e) {
            const conferma = confirm("Confermi l'operazione?");
            if (!conferma) e.preventDefault();
        });
    </script>

</body>
</html>
