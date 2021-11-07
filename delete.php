<html>
<body>
<?php
if (isset($_GET["codigo"])) {
	$db = new SQLite3("pizzaria.db");
	$results = $db->query("select * from pizzasabor where sabor= " . $_GET["codigo"]);
	if($row = $results->fetchArray()) {
		echo "<p style=\"color:red\"> O sabor foi utilizado em uma pizza, não pode ser eliminado!</p>";
	} else {
		$db->exec("delete from saboringrediente where sabor = " .$_GET["codigo"]);
		$db->exec("delete from sabor where sabor.codigo = ".$_GET["codigo"]);
		echo $db->changes()." Pizza(s) excluída(s)";
	}
	$db->close();
}
?>
</body>
<script>
setTimeout(function () { window.open("pizzariaA.php","_self"); }, 3000);
</script>
</html>

