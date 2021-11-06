<html>
<body>
<?php
function url($campo, $valor) {
	$result = array();
	if (isset($_GET["numero"])) $result["numero"] = "numero=".$_GET["numero"];

	if (isset($_GET["orderby"])) $result["orderby"] = "orderby=".$_GET["orderby"];
	if (isset($_GET["offset"])) $result["offset"] = "offset=".$_GET["offset"];
	$result[$campo] = $campo."=".$valor;
	return("select.php?".strtr(implode("&", $result), " ", "+"));
}

$db = new SQLite3("pizzaria.db");
$db->exec("PRAGMA foreign_keys = ON");

$limit = 5;

echo "<h1>Cadastro de pessoas</h1>\n";

echo "<select id=\"campo\" name=\"campo\">\n";
echo "<option value=\"numero\"".((isset($_GET["numero"])) ? " selected" : "").">Numero</option>\n";

echo "</select>\n"; 

$value = "";
if (isset($_GET["numero"])) $where[] = "numero like '%".strtr($_GET["numero"], " ", "%")."%'";

echo "<input type=\"text\" id=\"valor\" name=\"valor\" value=\"".$value."\" size=\"20\"> \n";

$parameters = array();
if (isset($_GET["orderby"])) $parameters[] = "orderby=".$_GET["orderby"];
if (isset($_GET["offset"])) $parameters[] = "offset=".$_GET["offset"];
echo "<a href=\"\" onclick=\"value = document.getElementById('valor').value.trim().replace(/ +/g, '+'); result = '".strtr(implode("&", $parameters), " ", "+")."'; result = ((value != '') ? document.getElementById('campo').value+'='+value+((result != '') ? '&' : '') : '')+result; this.href ='partes.php'+((result != '') ? '?' : '')+result;\">&#x1F50E;</a><br>\n";
echo "<br>\n";

echo "<table border=\"1\">\n";
echo "<tr>\n";
echo "<td><a href=\"insert.php\">&#x1F4C4;</a></td>\n";
echo "<td><b>numero</b> <a href=\"".url("orderby", "numero+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "numero+desc")."\">&#x25B4;</a></td>\n";
echo "<td></td>\n";
echo "</tr>\n";

$where = array();
if (isset($_GET["numero"])) $where[] = "numero = ".$_GET["numero"];

$where = (count($where) > 0) ? "where ".implode(" and ", $where) : "";

//$total = $db->query("select count(*) as total from comanda ".$where)->fetchArray()["total"];

$orderby = (isset($_GET["orderby"])) ? $_GET["orderby"] : "numero asc";

$offset = (isset($_GET["offset"])) ? max(0, min($_GET["offset"], $total-1)) : 0;
$offset = $offset-($offset%$limit);

$results = $db->query("select * from comanda".$where." order by ".$orderby." limit ".$limit." offset ".$offset);

while ($row = $results->fetchArray()){
    echo "<tr>";
	echo '<td>'.($row["pago"] > 0 ? '' : "<a href=\"pizzariaF.php?numero=".$row["numero"]."\">&#x1F4DD;</a>").'</td>';
	echo "<td>".$row["numero"]."</td>";
	echo "<td>".$row["data"]."</td>";
}
echo "<td>\n";
echo "</table>\n";
echo "<br>\n";

for ($page = 0; $page < ceil($total/$limit); $page++) {
	echo (($offset == $page*$limit) ? ($page+1) : "<a href=\"".url("offset", $page*$limit)."\">".($page+1)."</a>")." \n";
}

$db->close();
?>
</body>
</html>

