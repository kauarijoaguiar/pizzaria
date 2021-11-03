<html>
<title>Cadastro de Comandas</title>
<body>
<?php

if (isset($_POST["Inclui"])) {
	$error = "";
	if ($error == "") {
		$db = new SQLite3("pizzaria.db");
		$db->exec('PRAGMA foreign_keys = ON');
		$db->exec("insert into comanda (numero, data, mesa, pago) values (".$_POST['Numero'].", DATE('now'), ".$_POST['mesa'].", false)");
		echo "Comanda de número ".$_POST['Numero']." incluída!<br>\n";
		$db->close();
	}else{
		echo "<font color=\"red\">".$error."</font>";
	}
}
else {
	    $db = new SQLite3("pizzaria.db");
        $numero = $db->query("select comanda.numero as numero from comanda where numero = ".$_GET["numero"])->fetchArray() ;
        $tamanho = $db->query("select tamanho.nome as tamanho from tamanho");
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        echo '<form name="insertComanda" action="pizzariaE.php" method="POST">';
        echo '<table>';
        echo '<caption><h1>Inclusão de Pizza</h1></caption>';
        echo '<tbody>';

        echo '<tr>';
        echo '<td><label for="Numero">Numero</label></td>';
        echo '<td><input type="text" style="border:none; background-color: transparent; font-size: 15px"  name="Numero" id="Numero" value="'.$numero["numero"].'"></td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td><label for="Data">Data</label></td>';
        echo '<td>'.ucfirst(strftime('%a %d/%m/%y', strtotime('today'))).'</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td><label for="Tamanho">Tamanho</label></td>';
        echo '<td><select name="tamanho" id="tamanho">';
        while ($t = $tamanho->fetchArray()){
            echo "<option value=\"".$t["codigo"]."\">".$t["tamanho"]."</option>";
        }
        echo '</select></td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td><label for="Borda">Borda</label></td>';
        echo '<td><select name="borda" id="borda">';
        echo "<option value=\"sim\">Sim</option>";
        echo "<option value=\"nao\">Não</option>";
        echo '</tr>';

        echo '<tr>';
        echo '<td><label for="Sabor">Sabor</label></td>';
        echo '<td><select name="tipo" id="tipo">';
        $results = $db->query("select * from tipo");
        while ($row = $results->fetchArray()){
        echo "<option value=\"".$row["codigo"]."\">".$row["nome"]."</option>";
        }
        echo '</select>';
        echo '<input name="adicionar" type="button" value="+" onclick="add()">';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td><label for="Sabores">Sabores</label></td>';
        echo '<td><table id="lista"></table></td>';
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