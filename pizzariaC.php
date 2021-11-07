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
		$sabor = $db->query("select * from sabor where codigo = ".$_GET["codigo"]);
		$s = $sabor->fetchArray();
		$db->close();
		if($s === false){
			echo "<font color=\"red\">Sabor não encontrada</font>";
		}else{
	$db = new SQLite3("pizzaria.db");
echo '<form name="insert" action="pizzariaC.php" method="post">';
echo '<table>';
echo '<caption><h1>Alterar Sabor</h1></caption>';
echo '<tbody>';
echo '<tr>';
$nome = $db->query("select sabor.nome as nome from sabor where codigo = ".$_GET["codigo"]);
while ($n = $nome->fetchArray()){
echo '<td><label for="nome">Nome</label></td>';
echo "<td><input type=\"text\" name=\"nome\" id=\"nome\" value=\"".$n["nome"]."\"></td>";
}
echo '</tr>';
echo '<tr>';
echo '<td><label for="tipo">Tipo</label></td>';
echo '<td><select name="tipo" id="tipo">';
$results = $db->query("select * from tipo");
while ($row = $results->fetchArray()){
  echo "<option value=\"".$row["codigo"]."\">".$row["nome"]."</option>";
}
echo '</select></td>';
echo '</tr>';
echo '<tr>';
echo '<td><label for="ingrediente">Ingrediente</label></td>';
echo '<td>';
echo '<select name="ingrediente" id="ingrediente">';
$results2 = $db->query(trim("select * from ingrediente "));
while ($row2 = $results2->fetchArray()) {
  echo "<option value=\"".$row2["codigo"]."\">".$row2["nome"]."</option>\n";
}
echo '</select>';
echo '<input name="adicionar" type="button" value="+" onclick="add()">';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td><label for="lista">Ingredientes</label></td>';
echo '<td><table id="lista"></table></td>';
echo '</tr>';
echo '<tr>';
echo '<td><input type="submit" name="Alterar" value="Alterar"></td>';
echo '</tr>';
echo '</tbody>';
echo '</table>';
echo '</form>';
echo '<script>';
echo 'const armazena = [];';
echo 'const select = document.insert.ingrediente;';
echo 'function add() {';
echo 'const value = select.options[select.selectedIndex].text;';
echo 'if (armazena.indexOf(value) !== -1) {';
echo 'return;';
echo '} else {';
echo 'armazena.push(value);';
echo '}';
echo 'lista(armazena);';
echo '}';
echo 'function del(that) {';
echo 'const value = that.parentElement.previousElementSibling.innerHTML;';
echo 'armazena.splice(armazena.indexOf(value), 1);';
echo 'lista(armazena);';
echo '}';
echo 'function lista(list) {';
echo 'const table = list.map(i => {';
echo 'return `<tr><td>${i}</td><td><input type="button" value="-" onclick="del(this)"></td></tr>`';
echo '});';
echo 'return document.getElementById("lista").innerHTML = table.join("");';
echo '}';
echo '</script>';
	}
}else{
		if(isset($_POST["Alterar"])){
			$error = "";
			if ($error == "") {
				$db = new SQLite3("pizzaria.db");
				$db->exec("PRAGMA foreign_keys = ON");
				$db->exec("update sabor set nome = '".$_POST["nome"]."' where codigo = ".$_POST[$_GET["codigo"]]);				
				echo $db->changes()." Sabor(es) alterado(s)";
				$db->close();
		}else {
			echo "<font color=\"red\">".$error."</font>";
		}
	}
}
		//echo $db->changes()." coisa incluída(s)<br>\n";
		//echo $db->lastInsertRowID()." é o código da última coisa incluída.\n";

?>
</body>
<?php

if (isset($_POST["Alterar"])) {
	echo "<script>setTimeout(function () { window.open(\"pizzariaA.php\",\"_self\"); }, 3000);</script>";
}
?>
</html>
