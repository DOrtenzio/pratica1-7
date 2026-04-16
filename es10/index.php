<?php
require_once("funzioni/operazioni.php");

$obj=null;
try{
    $obj = new Operazioni("localhost", "dortenzio_biblioteca", "root", "");
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
    </style>
</head>
<body>

    <h1>Gestione Prestiti</h1>
    <p>Benvenuto/a</p>

    <div style="margin-bottom: 20px;">
        <a href="funzioni/inserimento.php" class="btn-add">+ Nuovo Prestito</a>
        <a href="funzioni/inserimento_libro.php" class="btn-add">+ Nuovo Libro</a>
        <form action="funzioni/filtra.php" method="post">
        <select class="btn-add" name="filtro_utente">
            <option value="all">Vedi Tutto</option>
            <?php foreach($obj->query("utenti") as $u) echo "<option value='".$u["id_utenti"]."'>".$u["email"]."</option>"; ?>
            <input type="submit" value="Filtra" name="Filtra">
        </select>
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
                foreach($obj->query("utenti") as $u) { $utenti_assoc[$u["id_utenti"]] = $u; }

                $libr_assoc = [];
                foreach($obj->query("libri") as $l) { $libr_assoc[$c["id_libro"]] = $l; }

                foreach($obj->query("prestiti") as $prestiti) {
                    if($_SESSION["filtro_utente"]==="all" || $_SESSION["filtro_utente"]===$prestiti["id_utente"]){
                        $checked = ($prestiti["restituito"] == 1) ? "checked" : "";
                        $stato = ($prestiti["restituito"] == 1) ? "Retituito" : "Non Restituito";
                        
                        echo "<tr>";
                        echo "<td><strong>" .$prestiti[""]. "</strong></td>";
                        echo "<td>" .$utenti_assoc[$prestiti["id_libro"]]["titolo"]. "</td>";
                        echo "<td>" .$libr_assoc[$prestiti["id_utente"]]["email"]. "</td>";
                        echo "<td>" .$prestiti["data_inizio"]."</td>";
                        echo "<td>" .$prestiti["data_fine_prevista"]."</td>";
                        echo "<td><input type='checkbox' $checked disabled> $stato</td>";
                        if($prestiti["restituito"] == 0){
                            echo "<td class='actions'>
                                <form action='funzioni/salva_update.php' method='post'>
                                    <input type='hidden' name='id_prestito' value='".$prestiti["id_prestito"]."'>
                                    <input type='hidden' name='id_libro' value='".$prestiti["id_libro"]."'>
                                    <input type='hidden' name='id_utente' value='".$prestiti["id_utente"]."'>
                                    <input type='hidden' name='data_inizio' value='".$prestiti["data_inizio"]."'>
                                    <input type='hidden' name='data_fine_prevista' value='".$prestiti["data_fine_prevista"]."'>
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

</body>
</html>
