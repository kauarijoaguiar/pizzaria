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
		if (isset($_GET["tamanho"])) $result["tamanho"] = "tamanho=" . $_GET["tamanho"];
		if (isset($_GET["borda"])) $result["borda"] = "borda=" . $_GET["borda"];
		if (isset($_GET["valor"])) $result["valor"] = "valor=" . $_GET["valor"];

		if (isset($_GET["orderby"])) $result["orderby"] = "orderby=" . $_GET["orderby"];
		if (isset($_GET["offset"])) $result["offset"] = "offset=" . $_GET["offset"];
		$result[$campo] = $campo . "=" . $valor;
		return ("pizzariaG.php?numero=" . $_GET["numero"] . "&" . strtr(implode("&", $result), " ", "+"));
	}

	$db = new SQLite3("pizzaria.db");
	$db->exec("PRAGMA foreign_keys = ON");

	$limit = 5;

	echo "<h1>Pizzas da Comanda " . $_GET["numero"] . "</h1>\n";

	$value = "";
	if (isset($_GET["tamanho"])) $value = $_GET["tamanho"];
	if (isset($_GET["borda"])) $value = $_GET["borda"];
	if (isset($_GET["sabores"])) $value = $_GET["sabores"];
	if (isset($_GET["valor"])) $value = $_GET["valor"];
	if (isset($_GET["total"])) $value = $_GET["total"];


	$parameters = array();
	if (isset($_GET["orderby"])) $parameters[] = "orderby=" . $_GET["orderby"];
	if (isset($_GET["offset"])) $parameters[] = "offset=" . $_GET["offset"];
	echo "<br>\n";

	echo "<table class=\"grid\">\n";
	echo "<tr>\n";
	echo "<td><b>Tamanho</b> <a href=\"" . url("orderby", "tamanho+asc") . "\">&#x25BE;</a> <a href=\"" . url("orderby", "tamanho+desc") . "\">&#x25B4;</a></td>\n";
	echo "<td><b>Borda</b> <a href=\"" . url("orderby", "borda+asc") . "\">&#x25BE;</a> <a href=\"" . url("orderby", "borda+desc") . "\">&#x25B4;</a></td>\n";
	echo "<td><b>Sabores</b></td>\n";
	echo "<td><b>Valor</b> <a href=\"" . url("orderby", "valor+asc") . "\">&#x25BE;</a> <a href=\"" . url("orderby", "valor+desc") . "\">&#x25B4;</a></td>\n";

	echo "</tr>\n";



	$orderby = (isset($_GET["orderby"])) ? $_GET["orderby"] : "codigo asc";

	$offset = (isset($_GET["offset"])) ? max(0, min($_GET["offset"], $total - 1)) : 0;
	$offset = $offset - ($offset % $limit);

	$results = $db->query(
		"select numero, pizza.codigo, group_concat(sabor.nome, ', ') as sabor, sabor.codigo as codigo, case
when pizza.borda is null then 'N??O'
else borda.nome
end as borda,'R$ ' || REPLACE(max(case
when borda.preco is null then 0
else borda.preco
end+precoportamanho.preco), '.', ',') as valor, tamanho.nome as tamanho from comanda
join pizza on pizza.comanda = comanda.numero
join pizzasabor on pizza.codigo = pizzasabor.pizza
join sabor on pizzasabor.sabor = sabor.codigo 
join tipo on sabor.tipo = tipo.codigo
join mesa on mesa.codigo = comanda.mesa
join precoportamanho on precoportamanho.tipo = sabor.tipo and precoportamanho.tamanho = pizza.tamanho
left join borda on pizza.borda = borda.codigo 
join tamanho on pizza.tamanho = tamanho.codigo
where comanda.numero = " . $_GET["numero"] . " 
group by pizza.codigo" . " order by " . $orderby . " limit " . $limit . " offset " . $offset
	);
	while ($row = $results->fetchArray()) {
		echo "<tr>";
		echo "<td>";
		echo $row["tamanho"];
		echo "</td>";
		echo "<td>";
		echo $row["borda"];
		echo "</td>";
		echo "<td>";
		echo $row["sabor"];
		echo "</td>";
		echo "<td>";
		echo $row["valor"];
		echo "</td>";
		echo "</tr>\n";
	}
	echo "<tr>";
	echo "<td colspan=3><b>Total</b></td>\n";
	echo "<td><b>";
	$results2 = $db->query(
		"select sum(tmp.preco) as total
	from
		(select
			max(case
					when borda.preco is null then 0
					else borda.preco
				end+precoportamanho.preco) as preco
		from comanda
			join pizza on pizza.comanda = comanda.numero
			join pizzasabor on pizzasabor.pizza = pizza.codigo
			join sabor on pizzasabor.sabor = sabor.codigo
			join precoportamanho on precoportamanho.tipo = sabor.tipo and precoportamanho.tamanho = pizza.tamanho
			left join borda on pizza.borda = borda.codigo
		where comanda.numero = " . $_GET["numero"] . " group by pizza.codigo) as tmp;"
	);
	while ($row2 = $results2->fetchArray()) {
		echo 'R$ ' . str_replace(".", ",", $row2["total"]);
	}
	echo "</b></td>";
	echo "</tr>";
	echo "</table>\n";

	echo "<a href=\"pizzariaD.php\"><button>Voltar</button></a>";

	$db->close();
	?>
</body>

</html>