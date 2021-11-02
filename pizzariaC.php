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
/*
FUNÇÃO PRA PEGAR O VALOR DO SELECT
<script>
    function add() {
        let armazena = [];
        input = document.getElementById("ingrediente").value;
        let lista = document.getElementById('lista');
        armazena.push(input)
        for (var i = 0; i < armazena.length; i++) {
            let item = document.createElement('li');
            item.appendChild(document.createTextNode(armazena[i]));
            lista.appendChild(item);
        }
    }
</script>


*/

if (isset($_POST["Alterar"])) {
	$error = "";
	if ($error == "") {
		$db = new SQLite3("pizzaria.db");
		$db->exec("PRAGMA foreign_keys = ON");
		//$db->exec("insert into sabor (nome, tipo) values ('".$_POST["nome"]."', '".$_POST["tipo"]."')");
		//$db->exec("insert into ingrediente (nome) values ('".$_POST["ingrediente"]."')");
		//echo $db->changes()." Piazza(s) incluída(s)<br>\n";
		//echo $db->lastInsertRowID()." é o código da última Pizza incluída.\n";
		$db->close();
	}else{
		echo "<font color=\"red\">".$error."</font>";
	}
}else
{
	$db = new SQLite3("pizzaria.db");
echo '<form name="insert" action="pizzariaC.php" method="post">';
echo '<table>';
echo '<caption><h1>Alterar Sabor</h1></caption>';
echo '<tbody>';
echo '<tr>';
echo '<td><label for="nome">Nome</label></td>';
echo '<td><input type="text" name="nome" id="nome"></td>';
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