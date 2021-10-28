<html>
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

echo "<h1>Cadastro de pessoas</h1>\n";

echo "<select id=\"campo\" name=\"campo\">\n";
echo "<option value=\"numero\"".((isset($_GET["numero"])) ? " selected" : "").">Numero</option>\n";
echo "<option value=\"data\"".((isset($_GET["data"])) ? " selected" : "").">Data</option>\n";
echo "<option value=\"mesa\"".((isset($_GET["mesa"])) ? " selected" : "").">Mesa</option>\n";
echo "<option value=\"pizza\"".((isset($_GET["pizza"])) ? " selected" : "").">Pizza</option>\n";
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
echo "<input type=\"text\" id=\"valor\" name=\"valor\" value=\"".$value."\" size=\"20\"> \n";

$parameters = array();
if (isset($_GET["orderby"])) $parameters[] = "orderby=".$_GET["orderby"];
if (isset($_GET["offset"])) $parameters[] = "offset=".$_GET["offset"];
echo "<a href=\"\" onclick=\"value = document.getElementById('valor').value.trim().replace(/ +/g, '+'); result = '".strtr(implode("&", $parameters), " ", "+")."'; result = ((value != '') ? document.getElementById('campo').value+'='+value+((result != '') ? '&' : '') : '')+result; this.href ='select.php'+((result != '') ? '?' : '')+result;\">&#x1F50E;</a><br>\n";
echo "<br>\n";

echo "<table border=\"1\">\n";
echo "<tr>\n";
echo "<td><a href=\"pizzariaE.php\">&#x1F4C4;</a></td>\n";
echo "<td><b>numero</b> <a href=\"".url("orderby", "numero+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "numero+desc")."\">&#x25B4;</a></td>\n";
echo "<td><b>data</b> <a href=\"".url("orderby", "data+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "data+desc")."\">&#x25B4;</a></td>\n";
echo "<td><b>mesa</b> <a href=\"".url("orderby", "mesa+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "mesa+desc")."\">&#x25B4;</a></td>\n";
echo "<td><b>pizza</b> <a href=\"".url("orderby", "pizza+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "pizza+desc")."\">&#x25B4;</a></td>\n";
echo "<td><b>valor</b> <a href=\"".url("orderby", "valor+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "valor+desc")."\">&#x25B4;</a></td>\n";
echo "<td><b>pago</b> <a href=\"".url("orderby", "pago+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "pago+desc")."\">&#x25B4;</a></td>\n";
echo "<td></td>\n";
echo "</tr>\n";

$where = array();
if (isset($_GET["numero"])) $where[] = "numero like '%".strtr($_GET["numero"], " ", "%")."%'";
if (isset($_GET["data"])) $where[] = "date(data) = date('".$_GET["data"]."')";
if (isset($_GET["mesa"])) $where[] = "mesa like '%".strtr($_GET["mesa"], " ", "%")."%'";
if (isset($_GET["pizza"])) $where[] = "pizza like '%".strtr($_GET["pizza"], " ", "%")."%'";
if (isset($_GET["valor"])) $where[] = "valor like '%".strtr($_GET["valor"], " ", "%")."%'";
if (isset($_GET["pago"])) $where[] = "pago like '%".strtr($_GET["pago"], " ", "%")."%'";

$where = (count($where) > 0) ? "where ".implode(" and ", $where) : "";

$total = $db->query("select count(*) as total from comanda ".$where)->fetchArray()["total"];

$orderby = (isset($_GET["orderby"])) ? $_GET["orderby"] : "codigo asc";

$offset = (isset($_GET["offset"])) ? max(0, min($_GET["offset"], $total-1)) : 0;
$offset = $offset-($offset%$limit);
/*
$results = $db->query("select * from sabor ".$where." order by ".$orderby." limit ".$limit." offset ".$offset);
while ($row = $results->fetchArray()) {
	echo "<tr>\n";
	echo "<td><a href=\"pizzariaF.php?codigo=".$row["codigo"]."\">&#x1F4DD;</a></td>\n";
	echo "<td>".$row["codigo"]."</td>\n";
	echo "<td>".$row["nome"]."</td>\n";
	echo "<td>".$row["tipo"]."</td>\n";
	echo "<td>".$row["ingrediente"]."</td>\n";
	echo "<td><a href=\"delete.php?codigo=".$row["codigo"]."\" onclick=\"return(confirm('Excluir ".$row["nome"]."?'));\">&#x1F5D1;</a></td>\n";
	echo "</tr>\n";
}
*/
echo "</table>\n";
echo "<br>\n";

for ($page = 0; $page < ceil($total/$limit); $page++) {
	echo (($offset == $page*$limit) ? ($page+1) : "<a href=\"".url("offset", $page*$limit)."\">".($page+1)."</a>")." \n";
}

$db->close();
?>
</body>
</html>

