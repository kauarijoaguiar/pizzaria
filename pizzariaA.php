<html>

<head>
	<title>Cadastro de Sabores</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php
	function url($campo, $valor)
	{
		$result = array();
		if (isset($_GET["nomeSabor"])) $result["nomeSabor"] = "nomeSabor=" . $_GET["nomeSabor"];
		if (isset($_GET["tipo"])) $result["tipo"] = "tipo=" . $_GET["tipo"];
		if (isset($_GET["ingrediente"])) $result["ingrediente"] = "ingrediente=" . $_GET["ingrediente"];
		if (isset($_GET["orderby"])) $result["orderby"] = "orderby=" . $_GET["orderby"];
		if (isset($_GET["offset"])) $result["offset"] = "offset=" . $_GET["offset"];
		$result[$campo] = $campo . "=" . $valor;
		return ("pizzariaA.php?" . strtr(implode("&", $result), " ", "+"));
	}

	$db = new SQLite3("pizzaria.db");
	$db->exec("PRAGMA foreign_keys = ON");

	$limit = 5;

	echo "<h1>Cadastro de Sabores</h1>\n";


	echo "<select id=\"campo\" name=\"campo\">\n";
	echo "<option value=\"nomeSabor\"" . ((isset($_GET["nomeSabor"])) ? " selected" : "") . ">Sabor</option>\n";
	echo "<option value=\"tipo\"" . ((isset($_GET["tipo"])) ? " selected" : "") . ">Tipo</option>\n";
	echo "<option value=\"ingrediente\"" . ((isset($_GET["ingrediente"])) ? " selected" : "") . ">Ingrediente</option>\n";
	echo "</select>\n";

	$value = "";

	if (isset($_GET["nomeSabor"])) $value = $_GET["nomeSabor"];
	if (isset($_GET["tipo"])) $value = $_GET["tipo"];
	if (isset($_GET["ingrediente"])) $value = $_GET["ingrediente"];
	echo "<input type=\"text\" id=\"valor\" name=\"valor\" value=\"" . $value . "\" size=\"20\" pattern=\"[a-z\s]+$\"> \n";

	echo '<script>';
	echo 'var valor = document.querySelector("#valor");';
	echo 'valor.addEventListener("input", function () {';
	echo 'valor.value = valor.value.toUpperCase();';
	echo '});';
	echo '</script>';

	$parameters = array();
	if (isset($_GET["orderby"])) $parameters[] = "orderby=" . $_GET["orderby"];
	if (isset($_GET["offset"])) $parameters[] = "offset=" . $_GET["offset"];
	echo "<a href=\"\" onclick=\"value = document.getElementById('valor').value.trim().replace(/ +/g, '+'); result = '" . strtr(implode("&", $parameters), " ", "+") . "'; result = ((value != '') ? document.getElementById('campo').value+'='+value+((result != '') ? '&' : '') : '')+result; this.href ='pizzariaA.php'+((result != '') ? '?' : '')+result;\">&#x1F50E;</a><br>\n";
	echo "<br>\n";

	echo "<table class=\"grid\">\n";
	echo "<tr>\n";
	echo "<td><a href=\"pizzariaB.php\">&#x1F4C4;</a></td>\n";
	echo "<td><b>Nome</b> <a href=\"" . url("orderby", "nomeSabor+asc") . "\">&#x25BE;</a> <a href=\"" . url("orderby", "nomeSabor+desc") . "\">&#x25B4;</a></td>\n";
	echo "<td><b>Tipo</b> <a href=\"" . url("orderby", "tipo+asc") . "\">&#x25BE;</a> <a href=\"" . url("orderby", "tipo+desc") . "\">&#x25B4;</a></td>\n";
	echo "<td><b>Ingrediente</b></td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";

	$where = array();

	if (isset($_GET["nomeSabor"])) $where[] = "nomeSabor like '%" . strtr($_GET["nomeSabor"], " ", "%") . "%'";
	if (isset($_GET["tipo"])) $where[] = "tipo like '%" . strtr($_GET["tipo"], " ", "%") . "%'"; 
	if (isset($_GET["ingrediente"])) $where[] = "ingrediente like '%" . strtr($_GET["ingrediente"], " ", "%") . "%'";
	$where = (count($where) > 0) ? "where " . implode(" and ", $where) : "";

	$total = $db->query("select count( distinct codigoSabor) as total from (select sabor.codigo as codigoSabor, sabor.nome as nomeSabor, tipo.nome as tipo, group_concat(ingrediente.nome, ', ') as ingrediente from sabor 
	join saboringrediente on saboringrediente.sabor = sabor.codigo 
	join  ingrediente on saboringrediente.ingrediente=ingrediente.codigo 
	join tipo on sabor.tipo=tipo.codigo group by sabor.codigo) " . $where . ";")->fetchArray()["total"];

	$orderby = (isset($_GET["orderby"])) ? $_GET["orderby"] : "codigoSabor asc";

	$offset = (isset($_GET["offset"])) ? max(0, min($_GET["offset"], $total - 1)) : 0;
	$offset = $offset - ($offset % $limit);

	$results = $db->query("SELECT * FROM (select sabor.codigo as codigoSabor, sabor.nome as nomeSabor, tipo.nome as tipo, group_concat(ingrediente.nome, ', ') as ingrediente from sabor 
	join saboringrediente on saboringrediente.sabor = sabor.codigo 
	join  ingrediente on saboringrediente.ingrediente=ingrediente.codigo 
	join tipo on sabor.tipo=tipo.codigo group by sabor.codigo) "
	. $where . " order by " . $orderby . " limit " . $limit . " offset " . $offset);

	while ($row = $results->fetchArray()) {
		echo "<tr>\n";
		echo "<td><a href=\"pizzariaC.php?codigo=" . $row["codigoSabor"] . "\">&#x1F4DD;</a></td>\n";
		echo "<td>" . $row["nomeSabor"] . "</td>\n";
		echo "<td>\n";
		echo $row["tipo"];
		echo "</td>\n";
		echo "<td>\n";
		echo $row["ingrediente"];
		// $results3 = $db->query("select ingrediente.nome as ingrediente,sabor.nome as sabor, tipo.nome as tipo from sabor join saboringrediente on saboringrediente.sabor=sabor.codigo join ingrediente on saboringrediente.ingrediente=ingrediente.codigo join tipo on sabor.tipo = tipo.codigo where sabor.codigo=" . $row["codigo"]);
		// $ingredientes = "";
		// while ($row3 = $results3->fetchArray()) {
		// 	$ingredientes .= $row3["ingrediente"] . ", ";
		// }
		// echo substr($ingredientes, 0, -2);
		echo "</td>\n";


		echo "<td><a href=\"delete.php?codigo=" . $row["codigoSabor"] . "\" onclick=\"return(confirm('Tem certeza que deseja eliminar o sabor " . $row["nomeSabor"] . "?'));\">&#x1F5D1;</a></td>\n";
		echo "</tr>\n";
	}

	echo "</table>\n";
	echo "<br>\n";

	for ($page = 0; $page < ceil($total / $limit); $page++) {
		echo (($offset == $page * $limit) ? ($page + 1) : "<a href=\"" . url("offset", $page * $limit) . "\">" . ($page + 1) . "</a>") . " \n";
	}

	$db->close();
	?>
</body>

</html>