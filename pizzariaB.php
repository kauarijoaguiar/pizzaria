<html>
<body>
<?php
/*
.headers on
.mode column
PRAGMA foreign_keys = ON;
*/
/*
OBS: QUANDO EU COLOCO ESSA LINHA "$db = new SQLite3("pizzaria.db");" DENTRO DO ELSE
OS SELECTS FUNCIONAM MAS SE EU COLOCO FORA, DENTRO DO SEGUNDO IF, ELE PARA
*/

if (isset($_POST["inclui"])) {
	$error = "";
	if ($error == "") {
		$db = new SQLite3("pizzaria.db");
		$db->exec("PRAGMA foreign_keys = ON");
		//$db->exec("insert into sabor (nome, tipo) values ('".$_POST["nome"]."', '".$_POST["tipo"]."')");
		//$db->exec("insert into ingrediente (nome) values ('".$_POST["ingrediente"]."')");
		//echo $db->changes()." pessoa(s) incluída(s)<br>\n";
		//echo $db->lastInsertRowID()." é o código da última pessoa incluída.\n";
		$db->close();
	}else{
		echo "<font color=\"red\">".$error."</font>";
	}
}else{
		echo "<form name=\"insert\" action=\"pizzariaC.php\" method=\"post\">\n";
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td>Nome</td>\n";
		echo "<td><input type=\"text\" name=\"nome\" value=\"\" size=\"50\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>Tipo</td>\n"; 
		echo "<td><select id=\"tipo\" name=\"tipo\">\n";
		$results = $db->query("select * from tipo");
		while ($row = $results->fetchArray()){
			echo "<option value=\"".$row["codigo"]."\">".$row["nome"]."</option>";
		}
		echo "</select></td>\n"; 
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>Ingredientes</td>\n"; 
		echo "<td><select id=\"ingrediente\" name=\"ingrediente\">\n";
		$results2 = $db->query(trim("select * from ingrediente "));
		while ($row2 = $results2->fetchArray()) {
		echo "<option value=\"".$row2["codigo"]."\">".$row2["nome"]."</option>\n";
		}
		echo "</select></td>\n";

		echo "<td><input type=\"button\" id=\"botao\" name=\"botao\" value=\"+\" onclick=\"add()\"></td>\n"; 
		echo "</tr>\n";
		echo "</table>\n";
		echo "<input type=\"submit\" name=\"inclui\" value=\"inclui\">\n";
		echo "</form>\n";
	}
		//echo $db->changes()." coisa incluída(s)<br>\n";
		//echo $db->lastInsertRowID()." é o código da última coisa incluída.\n";

?>
</body>
<?php
if (isset($_POST["inclui"])) {
	echo "<script>setTimeout(function () { window.open(\"select.php\",\"_self\"); }, 3000);</script>";
}
?>
</html>