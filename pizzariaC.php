<html>
<body>
<?php
/*
.headers on
.mode column
PRAGMA foreign_keys = ON;
*/
if (isset($_GET["codigo"])) {
	$db = new SQLite3("pizzaria.db");
	$db->exec("PRAGMA foreign_keys = ON");
	//$results = $db->query("select * from sabor where codigo = ".$_GET["codigo"]);
	$row = $results->fetchArray();
	$db->close();
	if ($row === false) {
		echo "<font color=\"red\">Sabor não encontrado</font>";
	} else {
		echo "<form name=\"update\" action=\"pizzariaC.php\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"codigo\" value=\"".$row["codigo"]."\">";
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td>Nome</td>\n";
		echo "<td><input type=\"text\" name=\"nome\" value=\"".$row["nome"]."\" size=\"50\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>Tipo</td>\n"; 
		/*
		echo "<td><select id=\"tipo\" name=\"tipo\">\n";
		$results = $db->query("select * from tipo");
		while ($row = $results->fetchArray()){
			echo "<option value=\"".$row["codigo"]."\">".$row["nome"]."</option>";
		}
		echo "</select></td>\n"; 
		*/
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>Ingredientes</td>\n"; 
		/*
		echo "<td><select id=\"ingrediente\" name=\"ingrediente\">\n";
		$results2 = $db->query(trim("select * from ingrediente "));
		while ($row2 = $results2->fetchArray()) {
		echo "<option value=\"".$row2["codigo"]."\">".$row2["nome"]."</option>\n";
		}
		echo "</select></td>\n";
		*/
		echo "<td><input type=\"button\" id=\"botao\" name=\"botao\" value=\"+\" onclick=\"add()\"></td>\n"; 
		echo "</tr>\n";
		echo "</table>\n";
		echo "<input type=\"submit\" name=\"alterar\" value=\"Alterar\">\n";
		echo "</form>\n";
	}
}else{
	if (isset($_POST["alterar"])) {
		$error = "";
		//coloque aqui o código para validação dos campos recebidos
		//se ocorreu algum erro, preencha a variável $error com uma mensagem de erro
		if ($error == "") {
			$db = new SQLite3("pizzaria.db");
			$db->exec("PRAGMA foreign_keys = ON");
			//$db->exec("update pessoa set nome = '".$_POST["nome"]."', genero = '".$_POST["genero"]."', nascimento = '".$_POST["nascimento"]."' where codigo = ".$_POST["codigo"]);
			echo $db->changes()." pessoa(s) alterada(s)";
			$db->close();
		} else {
			echo "<font color=\"red\">".$error."</font>";
		}
	}
}



?>
</body>
<?php
if (isset($_POST["alterar"])) {
	echo "<script>setTimeout(function () { window.open(\"pizzariaA.php\",\"_self\"); }, 3000);</script>";
}
?>
</html>