<html>
<body>
<?php
/*
	select ingrediente.nome as nomeingrediente, sabor.nome as saboringrediente from ingrediente
	join saboringrediente on saboringrediente.ingrediente=ingrediente.codigo
	join sabor on saboringrediente.sabor=sabor.codigo

*/
function url($campo, $valor) {
	$result = array();
	if (isset($_GET["nome"])) $result["nome"] = "nome=".$_GET["nome"];
	if (isset($_GET["tipo"])) $result["tipo"] = "tipo=".$_GET["tipo"];
	if (isset($_GET["ingrediente"])) $result["ingrediente"] = "ingrediente=".$_GET["ingrediente"];
	if (isset($_GET["orderby"])) $result["orderby"] = "orderby=".$_GET["orderby"];
	if (isset($_GET["offset"])) $result["offset"] = "offset=".$_GET["offset"];
	$result[$campo] = $campo."=".$valor;
	return("pizzariaA.php?".strtr(implode("&", $result), " ", "+"));
}

$db = new SQLite3("pizzaria.db");
$db->exec("PRAGMA foreign_keys = ON");

$limit = 5;

echo "<h1>Cadastro de Sabores</h1>\n";

echo "<select id=\"campo\" name=\"campo\">\n";
echo "<option value=\"nome\"".((isset($_GET["nome"])) ? " selected" : "").">Nome</option>\n";
echo "<option value=\"tipo\"".((isset($_GET["tipo"])) ? " selected" : "").">Tipo</option>\n";
echo "<option value=\"ingrediente\"".((isset($_GET["ingrediente"])) ? " selected" : "").">Ingrediente</option>\n";
echo "</select>\n"; 

$value = "";
if (isset($_GET["nome"])) $value = $_GET["nome"];
if (isset($_GET["tipo"])) $value = $_GET["tipo"];
if (isset($_GET["ingrediente"])) $value = $_GET["ingrediente"];
echo "<input type=\"text\" id=\"valor\" name=\"valor\" value=\"".$value."\" size=\"20\"> \n";

$parameters = array();
if (isset($_GET["orderby"])) $parameters[] = "orderby=".$_GET["orderby"];
if (isset($_GET["offset"])) $parameters[] = "offset=".$_GET["offset"];
echo "<a href=\"\" onclick=\"value = document.getElementById('valor').value.trim().replace(/ +/g, '+'); result = '".strtr(implode("&", $parameters), " ", "+")."'; result = ((value != '') ? document.getElementById('campo').value+'='+value+((result != '') ? '&' : '') : '')+result; this.href ='select.php'+((result != '') ? '?' : '')+result;\">&#x1F50E;</a><br>\n";
echo "<br>\n";

echo "<table border=\"1\">\n";
echo "<tr>\n";
echo "<td><a href=\"pizzariaB.php\">&#x1F4C4;</a></td>\n";
echo "<td><b>Nome</b> <a href=\"".url("orderby", "nome+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "nome+desc")."\">&#x25B4;</a></td>\n";
echo "<td><b>tipo</b> <a href=\"".url("orderby", "tipo+asc")."\">&#x25BE;</a> <a href=\"".url("orderby", "tipo+desc")."\">&#x25B4;</a></td>\n";
echo "<td><b>ingrediente</b></td>\n";
echo "<td></td>\n";
echo "</tr>\n";

$where = array();
if (isset($_GET["nome"])) $where[] = "nome like '%".strtr($_GET["nome"], " ", "%")."%'";
if (isset($_GET["tipo"])) $where[] = "tipo = '".$_GET["tipo"]."'";
if (isset($_GET["ingrediente"])) $where[] = "ingrediente = '".$_GET["ingrediente"]."'";
$where = (count($where) > 0) ? "where ".implode(" and ", $where) : "";

$total = $db->query("select count(*) as total from sabor ".$where)->fetchArray()["total"];

$orderby = (isset($_GET["orderby"])) ? $_GET["orderby"] : "codigo asc";

$offset = (isset($_GET["offset"])) ? max(0, min($_GET["offset"], $total-1)) : 0;
$offset = $offset-($offset%$limit);

$results = $db->query("select * from sabor ".$where." order by ".$orderby." limit ".$limit." offset ".$offset);
while ($row = $results->fetchArray()){
	echo "<tr>\n";
	echo "<td><a href=\"pizzariaC.php?codigo=".$row["nome"]."\">&#x1F4DD;</a></td>\n";
	echo "<td>".$row["nome"]."</td>\n";
	echo "<td>".$row["tipo"]."</td>\n";
	//echo "<td>".$row["ingredientes"]."</td>\n";
	echo "<td><a href=\"delete.php?codigo=".$row["nome"]."\" onclick=\"return(confirm('Excluir ".$row["nome"]."?'));\">&#x1F5D1;</a></td>\n";
	echo "</tr>\n";
}

echo "</table>\n";
echo "<br>\n";

for ($page = 0; $page < ceil($total/$limit); $page++) {
	echo (($offset == $page*$limit) ? ($page+1) : "<a href=\"".url("offset", $page*$limit)."\">".($page+1)."</a>")." \n";
}

$db->close();
?>
</body>
</html>




