<html>
<body>
<?php


$db = new SQLite3("pizzaria.db");
$db->exec("PRAGMA foreign_keys = ON");
$results = $db->query("select ingrediente.nome as ingrediente,sabor.nome as sabor, tipo.nome as tipo from sabor join saboringrediente on saboringrediente.sabor=sabor.codigo join ingrediente on saboringrediente.ingrediente=ingrediente.codigo join tipo on sabor.tipo = tipo.codigo where sabor.codigo=1");
//var_dump($results -> fetchArray());
/*
echo "<pre>";
while ($row = $results->fetchArray()){
    echo "<br>";
    var_dump($row);
}
echo "</pre>";
*/
echo "<select name=\"ingredientes\" id=\"ingredientes\">";
while ($row = $results->fetchArray()){
    echo "<option value=\"".$row["ingrediente"]."\">".$row["ingrediente"]."</option>";
}
echo "</select>";

?>
</body>
</html>