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
        <h1>Aggiungi Nuovo Libro</h1>

        <form action="salva_inserimento.php" method="post">
            <div class="form-group">
                <label for="titolo">Titolo </label>
                <input type="text" id="titolo" name="titolo" placeholder="Il Nome della Rosa" required>
            </div>

            <div class="form-group">
                <label for="anno_pubblicazione">Anno Pubblicazione</label>
                <input type="text" id="anno_pubblicazione" name="anno_pubblicazione" placeholder="1980" required>
            </div>

            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" placeholder="978-8845278655" required>
            </div>

            <div class="form-group">
                <label for="id_autore">Assegna a Autore</label>
                <select id="id_autore" name="id_autore" required>
                    <?php
                        foreach($obj->query("autori") as $u) echo "<option value='".$u["id_autore"]."'>".$u["nome"]." ".$u["cognome"]."</option>";
                    ?>
                </select>
            </div>

            <button type="submit" name="Inserisci" class="btn-save">Inserisci</button>
        </form>
    </div>

</body>
</html>
