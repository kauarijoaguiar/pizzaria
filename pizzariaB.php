<html>
<body>
<?php

//.headers on
//.mode column
//PRAGMA foreign_keys = ON;


		$db = new SQLite3("pizzaria.db");
		$db->exec("PRAGMA foreign_keys = ON");
		echo "<form name=\"insert\" action=\"pizzariaB.php\" method=\"post\">\n";
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td>Nome</td>\n";
		echo "<td><input type=\"text\" name=\"nome\" value=\"\" size=\"50\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>Tipo</td>\n"; 
		echo "<td><select id=\"tipo\" name=\"tipo\">\n";
		$results = $db->query(trim("select * from tipo "));
		while ($row = $results->fetchArray()) {
			echo "<option value=\"".$row["codigo"]."\">".$row["nome"]."</option>\n";
		}
		echo "</select></td>\n"; 
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>Ingredientes</td>\n"; 
		echo "<td><select id=\"ingrediente\" name=\"ingrediente\">\n";
		$results = $db->query(trim("select * from ingrediente "));
		while ($row = $results->fetchArray()) {
		echo "<option value=\"".$row["codigo"]."\">".$row["nome"]."</option>\n";
		}
		echo "</select></td>\n";
		echo "<td><input type=\"button\" id=\"botao\" name=\"botao\" value=\"+\" onclick=\"add()\"></td>\n"; 
		echo "</tr>\n";
		echo "</table>\n";
		echo "<input type=\"submit\" name=\"inclui\" value=\"inclui\">\n";
		echo "</form>\n";
		//echo $db->changes()." coisa incluída(s)<br>\n";
		//echo $db->lastInsertRowID()." é o código da última coisa incluída.\n";
	$db->close();
?>
</body>
</html>