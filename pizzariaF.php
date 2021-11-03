<html>
<title>Cadastro de Comandas</title>
<body>
<?php

if (isset($_POST["Inclui"])) {
	$error = "";
	if ($error == "") {
		$db = new SQLite3("pizzaria.db");
		$db->exec('PRAGMA foreign_keys = ON');
		$db->exec("insert into comanda (numero, data, mesa, pago) values (".$_POST['Numero'].", DATE('now'), ".$_POST['mesa'].", false)");
		echo "Comanda de número ".$_POST['Numero']." incluída!<br>\n";
		$db->close();
	}else{
		echo "<font color=\"red\">".$error."</font>";
	}
}
else {
    $db = new SQLite3("pizzaria.db");
    $numero = $db->query("select comanda.numero as numero from comanda where numero = ".$_GET["numero"])->fetchArray() ;
    $tamanho = $db->query("select tamanho.nome as tamanho from tamanho");
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    echo '<form name="insertComanda" action="pizzariaE.php" method="POST">';
    echo '<table>';
    echo '<caption style="text-align: left;"><h1>Inclusão de Pizza</h1></caption>';
    echo '<tbody>';
    echo '<tr>';
    echo '<td><label for="numero">Numero</label></td>';
    echo '<td><input type="number" name="numero" id="numero" value="'.$numero["numero"].'" readonly></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><label for="data">Data</label></td>';
    echo '<td><input type="text" name="data" id="data" readonly value="'.ucfirst(strftime('%a %d/%m/%y', strtotime('today'))).'"></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><label for="tamanho">Tamanho</label></td>';
    echo '<td>';
    echo '<select name="tamanho" id="tamanho">';
    while ($t = $tamanho->fetchArray()){
      echo "<option value=\"".$t["codigo"]."\">".$t["tamanho"]."</option>";
    }
    echo '</select>';
    echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td><label for="borda">Borda</label></td>';
echo '<td>';
echo '<select name="borda" id="borda">';
echo '<option value="sim">Sim</option>';
echo '<option value="nao">Não</option>';
echo '</select>';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td><label for="sabor">Sabor</label></td>';
echo '<td>';
echo '<select name="sabor" id="sabor" onchange="tipo(this.value)">';
echo '<option value="0" disabled selected>Selecionar Tipo</option>';
$results = $db->query("select * from tipo");
  while ($row = $results->fetchArray()){
  echo "<option value=\"".$row["codigo"]."\">".$row["nome"]."</option>";
}
echo '</select>';
echo '<select class="sabores" name="salgadatrad" id="salgadatrad" data-value="1" hidden>';
echo '<option value="0" disabled selected>Selecionar Sabor</option>';
$salgadatrad = $db->query("select sabor.nome as nome from sabor join tipo on sabor.tipo = tipo.codigo where tipo.codigo=1");
  while ($strad = $salgadatrad->fetchArray()){
  echo "<option value=\"".$strad["codigo"]."\">".$strad["nome"]."</option>";
}
echo '</select>';
echo '<select class="sabores" name="salgadaesp" id="salgadaesp" data-value="2" hidden>';
echo '<option value="0" disabled selected>Selecionar Sabor</option>';
$salgadaesp = $db->query("select sabor.nome as nome from sabor join tipo on sabor.tipo = tipo.codigo where tipo.codigo=2");
  while ($sesp = $salgadaesp->fetchArray()){
  echo "<option value=\"".$sesp["codigo"]."\">".$sesp["nome"]."</option>";
}
echo '</select>';
echo '<select class="sabores" name="docetrad" id="docetrad" data-value="3" hidden>';
echo '<option value="0" disabled selected>Selecionar Sabor</option>';
$docetrad = $db->query("select sabor.nome as nome from sabor join tipo on sabor.tipo = tipo.codigo where tipo.codigo=3");
  while ($dtrad = $docetrad->fetchArray()){
  echo "<option value=\"".$dtrad["codigo"]."\">".$dtrad["nome"]."</option>";
}
echo '</select>';
echo '<input name="adicionar" type="button" value="+" onclick="add(visible(sabores))">';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td><label for="sabores">Sabores</label></td>';
echo '<td>';
echo '<table id="lista"></table>';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td><input type="submit" name="inclui" value="Inclui"></td>';
echo '</tr>';
echo '</tbody>';
echo '</table>';
echo '</form>';
echo '<form name="saborPizza" action="pizzariaA.php" method="post" hidden>';
echo '<input type="number" name="pizza" id=pizza" readonly>';
echo '</form>';
echo '<script>';
echo 'const armazena = [];';
echo 'const sabores = document.querySelectorAll(".sabores");';
echo 'function tipo(val) {';
echo 'sabores.forEach(sel => {';
echo 'const aux = sel.dataset.value;';
echo 'sel.hidden = (aux === val) ? false : true;';
echo '});';
echo '}';
echo 'function add(element) {';
echo 'if (element.selectedIndex === 0) {';
echo 'return;';
echo '}';
echo 'const value = element.options[element.selectedIndex].text;';
echo 'if (armazena.indexOf(value) !== -1) {';
echo 'return;';
echo '} else {';
echo 'armazena.push(value);';
echo '}';
echo 'lista(armazena);';
echo '}';
echo 'function del(that) {';
echo 'const value = that.parentElement.previousElementSibling.innerHTML;';
echo 'armazena.splice(armazena.indexOf(value), 1);';
echo 'lista(armazena);';
echo '}';
echo 'function lista(list) {';
echo 'const table = list.map(i => {';
echo 'return `<tr><td>${i}</td><td><input type="button" value="-" onclick="del(this)"></td></tr>`';
echo '});';
echo 'return document.getElementById("lista").innerHTML = table.join("");';
echo '}';
echo 'function visible(sabores) {';
echo 'let element;';
echo 'sabores.forEach(sabor => {';
echo 'if (!sabor.hidden) {';
echo 'return element = sabor;';
echo '}';
echo '});';
echo 'return element;';
echo '}';
echo '</script>';
	}

?>
</body>
<?php


if (isset($_POST["Inclui"])) {
	echo "<script>setTimeout(function () { window.open(\"pizzariaD.php\",\"_self\"); }, 3000);</script>";
}


?>
</html>