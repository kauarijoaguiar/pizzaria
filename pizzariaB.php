<html>

<head>
	<title>Incluir Sabor</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php

	if (isset($_POST["Inclui"])) {
		$error = "";
		if ($error == "") {
			$db = new SQLite3("pizzaria.db");
			$db->exec("PRAGMA foreign_keys = ON");
			$db->exec("insert into sabor (nome, tipo) values ('" . $_POST["nome"] . "', '" . $_POST["tipo"] . "')");
			$sabor = $db->lastInsertRowID();
			$ingredientes = explode(",", $_POST["componenteIngredientes"]);

			foreach ($ingredientes as $ingrediente) {
				$db->exec("insert into saboringrediente (sabor,ingrediente) values (" . $sabor . "," . $ingrediente . ")");
			}

			echo "Sabor " . $_POST["nome"] . " incluído!<br>";
			$db->close();
		} else {
			echo "<font color=\"red\">" . $error . "</font>";
		}
	} else {
		$db = new SQLite3("pizzaria.db");
		echo '<form name="insert" action="pizzariaB.php" method="post">';
		echo '<table>';
		echo '<caption><h1>Incluir Sabor</h1></caption>';
		echo '<tbody>';
		echo '<tr>';
		echo '<td><label for="nome">Nome</label></td>';
		echo '<td><input type="text" name="nome" id="nome" required></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td><label for="tipo">Tipo</label></td>';
		echo '<td><select name="tipo" id="tipo">';
		$results = $db->query("select * from tipo");
		while ($row = $results->fetchArray()) {
			echo "<option value=\"" . $row["codigo"] . "\">" . $row["nome"] . "</option>";
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
		echo '<td><table id="lista"></table></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td><input type="submit" name="Inclui" value="Inclui" onClick="preencheIngredientes()"></td>';
		echo '<td><input type="text" id="componenteIngredientes" name="componenteIngredientes" hidden value=""></td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</form>';
		echo '<script>';
		echo 'const armazena = [];';
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

		echo '</script>';
	}

	?>
</body>
<?php


if (isset($_POST["Inclui"])) {
	echo "<script>setTimeout(function () { window.open(\"pizzariaA.php\",\"_self\"); }, 3000);</script>";
}


?>

</html>