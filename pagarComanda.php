<html>
<body>
<?php
if (isset($_GET["numero"])) {
	$db = new SQLite3("pizzaria.db");
	$db->exec("PRAGMA foreign_keys = ON");
	$db->exec("UPDATE COMANDA SET PAGO=true WHERE NUMERO = ".$_GET["numero"]);
	echo "Comanda paga!";
	$db->close();
}
?>
</body>
<script>
setTimeout(function () { window.open("pizzariaD.php","_self"); }, 2000);
</script>
</html>

