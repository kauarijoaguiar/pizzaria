<html>
<head>
<title>Cadastro de Comandas</title>
<style>
	a { text-decoration: none;}
	.paginacao { font-size: 50% }
</style>
</head>
<body>
<?php
function url($campo, $valor) {
	$result = array();
	if (isset($_GET["numero"])) $result["numero"] = "numero=".$_GET["numero"];
	if (isset($_GET["data"])) $result["data"] = "data=".$_GET["data"];
	if (isset($_GET["mesa"])) $result["mesa"] = "mesa=".$_GET["mesa"];
	if (isset($_GET["pizzas"])) $result["pizzas"] = "pizzas=".$_GET["pizzas"];
	if (isset($_GET["valor"])) $result["valor"] = "valor=".$_GET["valor"];
	if (isset($_GET["pago"])) $result["pago"] = "pago=".$_GET["pago"];
    if (isset($_GET["orderby"])) $result["orderby"] = "orderby=".$_GET["orderby"];
	if (isset($_GET["offset"])) $result["offset"] = "offset=".$_GET["offset"];
	$result[$campo] = $campo."=".$valor;
	return("pizzariaD.php?".strtr(implode("&", $result), " ", "+"));
}

$db = new SQLite3("pizzaria.db");
$db->exec("PRAGMA foreign_keys = ON");

$limit = 5;

echo "<h1>Cadastro de Comandas</h1>\n";

echo "<select id=\"campo\" name=\"campo\">\n";
echo "<option value=\"numero\"".((isset($_GET["numero"])) ? " selected" : "").">Número</option>\n";
echo "<option value=\"data\"".((isset($_GET["data"])) ? " selected" : "").">Data</option>\n";
echo "<option value=\"mesa\"".((isset($_GET["mesa"])) ? " selected" : "").">Mesa</option>\n";
echo "<option value=\"pizza\"".((isset($_GET["pizza"])) ? " selected" : "").">Pizzas</option>\n";
echo "<option value=\"valor\"".((isset($_GET["valor"])) ? " selected" : "").">Valor</option>\n";
echo "<option value=\"pago\"".((isset($_GET["pago"])) ? " selected" : "").">Pago</option>\n";
echo "</select>\n"; 

$value = "";
if (isset($_GET["numero"])) $value = $_GET["numero"];
if (isset($_GET["data"])) $value = $_GET["data"];
if (isset($_GET["mesa"])) $value = $_GET["mesa"];
if (isset($_GET["pizza"])) $value = $_GET["pizza"];
if (isset($_GET["valor"])) $value = $_GET["valor"];
if (isset($_GET["pago"])) $value = $_GET["pago"];
echo "<input type=\"text\" id=\"valor\" name=\"valor\" value=\"".$value."\" size=\"20\" pattern=\"[A-Z\s]+$\"> \n";

echo '<script>';
echo 'var valor = document.querySelector("#valor");';
echo 'valor.addEventListener("input", function () {';
echo 'valor.value = valor.value.toUpperCase();';
echo '});';
echo '</script>';

$parameters = array();
if (isset($_GET["orderby"])) $parameters[] = "orderby=".$_GET["orderby"];
if (isset($_GET["offset"])) $parameters[] = "offset=".$_GET["offset"];
echo "<a href=\"\" onclick=\"value = document.getElementById('valor').value.trim().replace(/ +/g, '+'); result = '".strtr(implode("&", $parameters), " ", "+")."'; result = ((value != '') ? document.getElementById('campo').value+'='+value+((result != '') ? '&' : '') : '')+result; this.href ='pizzariaD.php'+((result != '') ? '?' : '')+result;\">&#x1F50E;</a><br>\n";
echo "<br>";

echo "<table border=\"1\">";
echo "<tr>";
echo "<td><a href=\"pizzariaE.php\">&#x1F4C4;</a></td>";
echo "<td><b>Número</b> <a href=\"".url("orderby", "numero+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "numero+desc")."\">&#x25B4;</a></td>";
echo "<td><b>Data</b> <a href=\"".url("orderby", "data+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "data+desc")."\">&#x25B4;</a></td>";
echo "<td><b>Mesa</b> <a href=\"".url("orderby", "mesa+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "mesa+desc")."\">&#x25B4;</a></td>";
echo "<td colspan=2><b>Pizzas</b> <a href=\"".url("orderby", "pizza+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "pizza+desc")."\">&#x25B4;</a></td>";
echo "<td><b>Valor</b> <a href=\"".url("orderby", "valor+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "valor+desc")."\">&#x25B4;</a></td>";
echo "<td colspan=3><b>Pago</b> <a href=\"".url("orderby", "pago+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "pago+desc")."\">&#x25B4;</a></td>";
echo "<td></td>";
echo "</tr>";

$where = array();
if (isset($_GET["numero"])) $where[] = "numero like '%".strtr($_GET["numero"], " ", "%")."%'";
if (isset($_GET["data"])) $where[] = "date(data) = date('".$_GET["data"]."')";
if (isset($_GET["mesa"])) $where[] = "mesa like '%".strtr($_GET["mesa"], " ", "%")."%'";
if (isset($_GET["pizza"])) $where[] = "pizza like '%".strtr($_GET["pizza"], " ", "%")."%'";
if (isset($_GET["valor"])) $where[] = "valor like '%".strtr($_GET["valor"], " ", "%")."%'";
if (isset($_GET["pago"])) $where[] = "pago like '%".strtr($_GET["pago"], " ", "%")."%'";

$where = (count($where) > 0) ? "where ".implode(" and ", $where) : "";

$total = $db->query("select count(*) as total from comanda ".$where)->fetchArray()["total"];

$orderby = (isset($_GET["orderby"])) ? $_GET["orderby"] : "numero asc";

$offset = (isset($_GET["offset"])) ? max(0, min($_GET["offset"], $total-1)) : 0;
$offset = $offset-($offset%$limit);


$results = $db->query("select numero, 
case cast (strftime('%w', data) as integer)
  when 0 then 'Dom'
  when 1 then 'Seg'
  when 2 then 'Ter'
  when 3 then 'Qua'
  when 4 then 'Qui'
  when 5 then 'Sex'
  else 'Sáb' end || ' ' || strftime('%d/%m/%Y', data)  as data, mesa, pago from comanda" . $where . " order by " . $orderby . " limit " . $limit . " offset " . $offset);

// $results = $db->query("select numero, 
// case cast (strftime('%w', data) as integer)
//   when 0 then 'Sunday'
//   when 1 then 'Monday'
//   when 2 then 'Tuesday'
//   when 3 then 'Wednesday'
//   when 4 then 'Thursday'
//   when 5 then 'Friday'
//   else 'Saturday' end as data, mesa, pago, count(pizza.codigo) as pizza from comanda join pizza on pizza.comanda = comanda.numero ".$where." order by ".$orderby." limit ".$limit." offset ".$offset);

while ($row = $results->fetchArray()){
	echo "<tr>";
	echo '<td>'.($row["pago"] > 0 ? '' : "<a href=\"pizzariaF.php?numero=".$row["numero"]."\">&#x1F4DD;</a>").'</td>';
	echo "<td>".$row["numero"]."</td>";
	echo "<td>".$row["data"]."</td>";
	echo "<td>";
	$results2 = $db->query("select mesa.nome as mesa from comanda join mesa on comanda.mesa = mesa.codigo where comanda.numero= ".$row["numero"]);
	while ($row2 = $results2->fetchArray()){
		echo $row2["mesa"];
	}
	echo "</td>";

	$results3 = $db->query("select count(codigo) as pizza from pizza join comanda on pizza.comanda = comanda.numero where comanda.numero= ".$row["numero"]);
	while ($row3 = $results3->fetchArray()){
		echo "<td>";
		echo $row3["pizza"];
		echo "</td>";
		echo '<td>'.($row3["pizza"] > 0 ? "<a href=\"pizzariaG.php?numero=".$row["numero"]."\">&#128064;</a>" : '').'</td>';
	}
	echo "<td>\n";
	
	$results4 = $db->query("select sum(tmp.preco) as total
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
		where comanda.numero = ".$row["numero"]." group by pizza.codigo) as tmp;");
	while ($row4 = $results4->fetchArray()){
	echo "R$ ".($row4["total"] == "" ? "0,0" : (str_replace(".",",",$row4["total"])));
	}
	echo "</td>\n";

	
	echo "<td>".($row["pago"] > 0 ? 'Sim':'Não')."</td>\n";
	while ($row3 = $results3->fetchArray()){
		echo "<td>".($row["pago"] == 0 &&$row3["pizza"] > 0 ?  "<a href=\"pagarComanda.php?numero=".$row["numero"]."\">&#128181;</a>" : '')."</td>";
		echo "<td>".($row["pago"] == 0 &&$row3["pizza"] > 0 ?  "<a href=\"pagarComanda.php?numero=".$row["numero"]."\">&#128179;</a>" : '')."</td>";
		echo '<td>'.($row3["pizza"] == 0 ? "<a href=\"deleteComanda.php?numero=".$row["numero"]."\" onclick=\"return(confirm('Excluir comanda de número ".$row["numero"]."?'));\">&#x1F5D1;</a>" : '').'</td>';
	}
	echo "</tr>\n";
}


echo "</table>\n";
echo "<br>\n";

for ($page = 0; $page < ceil($total/$limit); $page++) {
	echo "<span  class=\"paginacao\">".(($offset == $page*$limit) ? ($page+1) : "<a href=\"".url("offset", $page*$limit)."\">".($page+1)."</a>")."</span> ";
}

$db->close();
?>
</body>
</html>
