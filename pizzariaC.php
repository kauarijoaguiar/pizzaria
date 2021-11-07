<html>

<head>
	<title>Alterar Sabor</title>
	<link rel="stylesheet" href="style.css">
</head>

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
		$sabor = $db->query("select * from sabor where codigo = " . $_GET["codigo"]);
		$s = $sabor->fetchArray();
		$db->close();
		if ($s === false) {
			echo "<font color=\"red\">Sabor não encontrado</font>";
		} else {
			$db = new SQLite3("pizzaria.db");
			echo '<form name="insert" action="pizzariaC.php" method="post">';
			echo '<table>';
			echo '<caption><h1>Alterar Sabor</h1></caption>';
			echo '<tbody>';
			echo "<tr><td><input hidden type=\"text\" name=\"codigo\" value=\"" . $_GET["codigo"] . "\"></td></tr>";
			echo '<tr>';
			$nome = $db->query("select sabor.nome as nome from sabor where codigo = " . $_GET["codigo"]);
			while ($n = $nome->fetchArray()) {
				echo '<td><label for="nome">Nome</label></td>';
				echo "<td><input type=\"text\" name=\"nome\" id=\"nome\" value=\"" . $n["nome"] . "\" required></td>";
			}
			echo '</tr>';
			echo '<tr>';
			echo '<td><label for="tipo">Tipo</label></td>';
			echo '<td><select name="tipo" id="tipo">';
			$results = $db->query("select * from tipo");
			while ($row = $results->fetchArray()) {
				echo "<option value=\"" . $row["codigo"] . "\">" . $row["nome"] . "</option>";
				$tp = $db->query("select tipo.codigo as codigo, tipo.nome as nome from tipo join sabor on sabor.tipo = tipo.codigo where sabor.codigo =" . $_GET["codigo"]);
				while ($rowtipo = $tp->fetchArray()) {
					echo "<option value=\"nome\" selected disabled hidden>" . $rowtipo["nome"] . "</option>";
				}
			}
			echo '</select></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td><label for="ingrediente">Ingrediente</label></td>';
			echo '<td>';
			echo '<select name="ingrediente" id="ingrediente">';
			$results2 = $db->query(trim("select * from ingrediente "));
			while ($row2 = $results2->fetchArray()) {
				echo "<option value=\"" . $row2["codigo"] . "\">" . $row2["nome"] . "</option>\n";
			}
			echo '</select>';
			echo '<input name="adicionar" type="button" value="+" onclick="add()">';
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td><label for="lista">Ingredientes</label></td>';
			echo '<td><table id="lista">';
			$ingredienteSabor = $db->query("select * from saboringrediente join ingrediente on saboringrediente.ingrediente = ingrediente.codigo where saboringrediente.sabor=" . $_GET["codigo"]);
			while ($ingrediente = $ingredienteSabor->fetchArray()) {
				echo '<tr><td value="' . $ingrediente["codigo"] . '" class="ingredienteEscolhido">' . $ingrediente["nome"] . '</td><td><input type="button" value="-" onclick="del(this)"></td></tr>';
			}

			echo '</table></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td><input type="submit" name="Alterar" value="Alterar" onClick="preencheIngredientes()"></td>';
			echo '<td><input type="text" id="componenteIngredientes" name="componenteIngredientes" hidden value=""></td>';
			echo '</tr>';
			echo '</tbody>';
			echo '</table>';
			echo '</form>';
			echo '<script>';
			echo 'const armazena = [];';
			echo 'let ingredientesEscolhidos = document.querySelectorAll(".ingredienteEscolhido");';
			echo 'ingredientesEscolhidos.forEach(ingrediente => {';
			echo 'if (!ingrediente.hidden) {';
			echo 'armazena.push([ingrediente.attributes[0].nodeValue, ingrediente.innerHTML]);';
			echo '}';
			echo '});';
			echo 'console.log(armazena);';
			echo 'const select = document.insert.ingrediente;';
			echo 'function indexOfArray(array, item) {';
			echo '    for (var i = 0; i < array.length; i++) {';
			echo '        if (array[i][0] == item[0] && array[i][1] == item[1]) {';
			echo '            return i;   ';
			echo '        }';
			echo '    }';
			echo '    return -1;';
			echo '}';
			echo 'function add() {';
			echo 'const value = select.options[select.selectedIndex].value;';
			echo 'const text = select.options[select.selectedIndex].text;';
			echo 'const object = [value, text];';
			echo 'if (indexOfArray(armazena, object) !== -1) {';
			echo 'return;';
			echo '} else {';
			echo 'armazena.push(object);';
			echo '}';
			echo 'lista(armazena);';
			echo '}';
			echo 'function del(that) {';
			echo 'const value = that.parentElement.previousElementSibling.attributes[0].nodeValue;';
			echo 'const text = that.parentElement.previousElementSibling.innerHTML;';
			echo 'const object = [value, text];';
			echo 'armazena.splice(indexOfArray(armazena, object), 1);';
			echo 'lista(armazena);';
			echo '}';
			echo 'function lista(list) {';
			echo 'const table = list.map(i => {';
			echo 'return `<tr><td value="${i[0]}" class="ingredienteEscolhido">${i[1]}</td><td><input type="button" value="-" onclick="del(this)"></td></tr>`';
			echo '});';
			echo 'return document.getElementById("lista").innerHTML = table.join("");';
			echo '}';
			echo 'function preencheIngredientes(){';
			echo 'let componenteIngredientes = document.getElementById("componenteIngredientes");';
			echo 'console.log(componenteIngredientes);';
			echo 'let ingredientesEscolhidos = document.querySelectorAll(".ingredienteEscolhido");';
			echo 'componenteIngredientes.value="";';
			echo 'ingredientesEscolhidos.forEach(ingrediente => {';
			echo 'if (!ingrediente.hidden) {';
			echo 'console.log(ingrediente);';
			echo 'componenteIngredientes.value=componenteIngredientes.value + (componenteIngredientes.value == "" ? "" : ",") + ingrediente.attributes[0].nodeValue;';
			echo '}';
			echo '});';
			echo  '}';
			echo 'var nome = document.querySelector("#nome");';
			echo 'nome.addEventListener("input", function () {';
			echo 'nome.value = nome.value.toUpperCase();';
			echo '});';
			echo 'document.insert.onsubmit = (evt) => {';
				echo 'evt.preventDefault();';
				echo 'if (armazena.length !== 0) {';
				echo 'document.insert.submit();';
				echo '}';
				echo '}';
			echo '</script>';
		}
	} else {
		if (isset($_POST["Alterar"])) {
			$error = "";
			if ($error == "") {
				$db = new SQLite3("pizzaria.db");
				$db->exec("PRAGMA foreign_keys = ON");
				$db->exec("update sabor set nome = '" . $_POST["nome"] . "', tipo=" . $_POST["tipo"] . " where codigo = " . $_POST["codigo"]);
				$db->exec("delete from saboringrediente where sabor = " . $_POST["codigo"]);

				$ingredientes = explode(",", $_POST["componenteIngredientes"]);

				foreach ($ingredientes as $ingrediente) {
					$db->exec("insert into saboringrediente (sabor,ingrediente) values (" . $_POST["codigo"] . "," . $ingrediente . ")");
				}

				echo $db->changes() . " Sabor alterado!";
				$db->close();
			} else {
				echo "<font color=\"red\">" . $error . "</font>";
			}
		}
	}

	?>
</body>
<?php

if (isset($_POST["Alterar"])) {
	echo "<script>setTimeout(function () { window.open(\"pizzariaA.php\",\"_self\"); }, 3000);</script>";
}
?>

</html>