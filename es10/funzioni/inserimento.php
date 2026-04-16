<?php
require_once("operazioni.php");
$obj=new Operazioni("localhost","dortenzio_biblioteca","root","");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; margin: 20px; color: #333; background-color: #f9f9f9; }
        .container { max-width: 500px; background: white; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: auto; }
        h1 { font-size: 1.5rem; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .checkbox-group { display: flex; align-items: center; gap: 10px; }
        .btn-save { background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; width: 100%; font-size: 1rem; }
        .btn-save:hover { background: #218838; }
        .back-link { display: inline-block; margin-bottom: 15px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

    <div class="container">
        <a href="../index.php" class="back-link">&larr; Torna alla lista</a>
        <h1>Aggiungi Nuovo Prestito</h1>

        <form action="salva_inserimento.php" method="post">
            <div class="form-group">
                <label for="id_libro">Assegna Libro</label>
                <select id="id_libro" name="id_libro" required>
                    <?php
                        foreach($obj->query("libri") as $l) echo "<option value='".$l["id_libro"]."'>".$l["titolo"]."</option>";
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_utente">Assegna a Utente</label>
                <select id="id_utente" name="id_utente" required>
                    <?php
                        foreach($obj->query("utenti") as $u) echo "<option value='".$u["id_utente"]."'>".$u["email"]."</option>";
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="data_inizio">Data Inizio</label>
                <input type="date" id="data_inizio" name="data_inizio" required>
            </div>

            <div class="form-group">
                <label for="data_fine_prevista">Data Fine Prevista</label>
                <input type="date" id="data_fine_prevista" name="data_fine_prevista" required>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="restituito" name="restituito" value="1">
                <label for="restituito" style="margin-bottom: 0;">Segna come già restituito</label>
            </div>

            <button type="submit" name="Salva" class="btn-save">Inserisci</button>
        </form>
    </div>

</body>
</html>
