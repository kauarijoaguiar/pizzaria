<html>

<head>
    <title>Cadastro de Comandas</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php

    if (isset($_POST["Inclui"])) {
        $error = "";
        if ($error == "") {
            $db = new SQLite3("pizzaria.db");
            $db->exec('PRAGMA foreign_keys = ON');
            $db->exec("insert into comanda (numero, data, mesa, pago) values (" . $_POST['Numero'] . ", DATE('now', 'localtime'), " . $_POST['mesa'] . ", false)");
            echo "Comanda de número " . $_POST['Numero'] . " incluída!<br>\n";
            $db->close();
        } else {
            echo "<font color=\"red\">" . $error . "</font>";
        }
    } else {
        $db = new SQLite3("pizzaria.db");
        $proximoNumero = $db->query("SELECT NUMERO+1 AS PROXIMO FROM COMANDA ORDER BY NUMERO DESC LIMIT 1")->fetchArray();
        $mesas = $db->query("SELECT * FROM MESA");
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        echo '<form name="insertComanda" action="pizzariaE.php" method="POST">';
        echo '<table>';
        echo '<caption><h1>Incluir Comanda</h1></caption>';
        echo '<tbody>';

        echo '<tr>';
        echo '<td><label for="Numero">Numero</label></td>';
        echo '<td><input type="text" style="border:none; background-color: transparent; font-size: 15px"  name="Numero" id="Numero" readonly value="' . $proximoNumero['PROXIMO'] . '"></td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td><label for="Data">Data</label></td>';
        echo '<td>' . ucfirst(strftime('%a %d/%m/%y', strtotime('today'))) . '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td><label for="Mesa">Mesa</label></td>';
        echo '<td><select name="mesa" id="mesa">';
        while ($linhaMesa = $mesas->fetchArray()) {
            echo "<option value=\"" . $linhaMesa["codigo"] . "\">" . $linhaMesa["nome"] . "</option>";
        }
        echo '</select></td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td><input type="submit" name="Inclui" value="Inclui"></td>';
        echo '</tr>';

        echo '</tbody>';
        echo '</table>';
        echo '</form>';
    }

    ?>
</body>
<?php


if (isset($_POST["Inclui"])) {
    echo "<script>setTimeout(function () { window.open(\"pizzariaD.php\",\"_self\"); }, 3000);</script>";
}


?>

</html>