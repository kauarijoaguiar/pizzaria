<html>
<body>
<?php
if (isset($_GET["codigo"])) {
	$db = new SQLite3("pizzaria.db");
	$db->exec("PRAGMA foreign_keys = ON");
	$db->exec("delete from sabor where sabor.codigo = ".$_GET["codigo"]);
	echo $db->changes()." pessoa(s) excluída(s)";
	$db->close();
}
?>
</body>
<script>
setTimeout(function () { window.open("pizzariaA.php","_self"); }, 3000);
</script>
</html>

