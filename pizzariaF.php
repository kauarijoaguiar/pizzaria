<html>

<head>
  <title>Cadastro de Comandas</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php
  if (isset($_POST["Inclui"])) {

    $error = "";
    if ($error == "") {
      $db = new SQLite3("pizzaria.db");
      $db->exec('PRAGMA foreign_keys = ON');
      $db->exec("insert into pizza (comanda, tamanho, borda) values (" . $_POST["numero"] . ",'" . $_POST['Tamanho'] . "'," . $_POST['borda'] . ")");

      $pizza = $db->lastInsertRowID();
      $sabores = explode(",", $_POST["componenteSabores"]);

      foreach ($sabores as $sabor) {
        $db->exec("insert into pizzasabor (pizza, sabor) values (" . $pizza . "," . $sabor . ")");
      }

      echo "Pizza incluída na comanda " . $_POST['numero'] . "!<br>\n";
      $db->close();
    } else {
      echo "<font color=\"red\">" . $error . "</font>";
    }
  } else {

    $db = new SQLite3("pizzaria.db");
    $numero = $_GET["numero"];
    $tamanho = $db->query("select tamanho.nome as tamanho, codigo from tamanho");
    $bordas = $db->query("select codigo, nome from borda");
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    echo '<form name="insertPizzaNaComanda" action="pizzariaF.php?numero=' . $_GET["numero"] . '" method="POST">';
    echo '<table>';
    echo '<caption style="text-align: left;"><h1>Inclusão de Pizza</h1></caption>';
    echo '<tbody>';
    echo '<tr>';
    echo '<td><label for="numero">Numero</label></td>';
    echo '<td><input type="number" style="border:none; background-color: transparent; font-size: 15px" name="numero" id="numero" value="' . $numero . '" readonly></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><label for="data">Data</label></td>';
    echo '<td><input type="text" name="data" id="data" readonly style="border:none; background-color: transparent; font-size: 15px" value="' . ucfirst(strftime('%a %d/%m/%y', strtotime('today'))) . '"></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><label for="Tamanho">Tamanho</label></td>';
    echo '<td>';
    echo '<select name="Tamanho" id="Tamanho">';
    while ($t = $tamanho->fetchArray()) {
      echo "<option value=\"" . $t["codigo"] . "\">" . ucfirst(strtolower($t["tamanho"])) . "</option>";
    }
    echo '</select>';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><label for="borda">Borda</label></td>';
    echo '<td>';
    echo '<select name="borda" id="borda">';
    echo '<option value="null">Não</option>';
    while ($borda = $bordas->fetchArray()) {
      echo "<option value=\"" . $borda["codigo"] . "\">" . ucfirst(strtolower($borda["nome"])) . "</option>";
    }
    echo '</select>';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><label for="sabor">Sabor</label></td>';
    echo '<td>';
    echo '<select name="sabor" id="sabor" onchange="tipo(this.value)">';
    echo '<option value="0" disabled selected>Selecionar Tipo</option>';
    $results = $db->query("select * from tipo");
    while ($row = $results->fetchArray()) {
      echo "<option value=\"" . $row["codigo"] . "\">" . ucfirst(strtolower($row["nome"])) . "</option>";
    }
    echo '</select>';
    echo '<select class="sabores" name="salgadatrad" id="salgadatrad" data-value="1" hidden>';
    echo '<option value="0" disabled selected>Selecionar Sabor</option>';
    $salgadatrad = $db->query("select sabor.nome as nome, sabor.codigo from sabor join tipo on sabor.tipo = tipo.codigo where tipo.codigo=1");
    while ($strad = $salgadatrad->fetchArray()) {
      echo "<option value=\"" . $strad["codigo"] . "\">" . ucfirst(strtolower($strad["nome"])) . "</option>";
    }
    echo '</select>';
    echo '<select class="sabores" name="salgadaesp" id="salgadaesp" data-value="2" hidden>';
    echo '<option value="0" disabled selected>Selecionar Sabor</option>';
    $salgadaesp = $db->query("select sabor.nome as nome, sabor.codigo from sabor join tipo on sabor.tipo = tipo.codigo where tipo.codigo=2");
    while ($sesp = $salgadaesp->fetchArray()) {
      echo "<option value=\"" . $sesp["codigo"] . "\">" . ucfirst(strtolower($sesp["nome"])) . "</option>";
    }
    echo '</select>';
    echo '<select class="sabores" name="docetrad" id="docetrad" data-value="3" hidden>';
    echo '<option value="0" disabled selected>Selecionar Sabor</option>';
    $docetrad = $db->query("select sabor.nome as nome, sabor.codigo from sabor join tipo on sabor.tipo = tipo.codigo where tipo.codigo=3");
    while ($dtrad = $docetrad->fetchArray()) {
      echo "<option value=\"" . $dtrad["codigo"] . "\">" . ucfirst(strtolower($dtrad["nome"])) . "</option>";
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
    echo '<td><input type="submit" name="Inclui" value="Inclui" onClick="preencheSabores()"></td>';
    echo '<td><input type="text" id="componenteSabores" name="componenteSabores" hidden value=""></td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '</form>';
    echo '<script>';
    echo 'const armazena = [];';
    echo 'const sabores = document.querySelectorAll(".sabores");';
    echo 'function indexOfArray(array, item) {';
    echo '    for (var i = 0; i < array.length; i++) {';
    echo '        if (array[i][0] == item[0] && array[i][1] == item[1]) {';
    echo '            return i;   ';
    echo '        }';
    echo '    }';
    echo '    return -1;';
    echo '}';
    echo 'function tipo(val) {';
    echo 'console.log(armazena);';

    echo 'sabores.forEach(sel => {';
    echo 'const aux = sel.dataset.value;';
    echo 'sel.hidden = (aux === val) ? false : true;';
    echo '});';
    echo '}';
    echo 'function add(element) {';
    echo 'console.log(armazena);';
    echo 'if (element.selectedIndex === 0) {';
    echo 'return;';
    echo '}';
    echo 'const value = element.options[element.selectedIndex].value;';
    echo 'const text = element.options[element.selectedIndex].text;';
    echo 'const object = [value, text];';
    echo 'if (indexOfArray(armazena, object) !== -1) {';
    echo 'return;';
    echo '} else {';
    echo 'armazena.push(object);';
    echo '}';
    echo 'lista(armazena);';
    echo '}';
    echo 'function del(that) {';

    echo 'const value = that.parentElement.previousElementSibling.attributes[0].nodeValue;';
    echo 'const text = that.parentElement.previousElementSibling.innerHTML;';
    echo 'const object = [value, text];';
    echo 'console.log("armazena");';
    echo 'console.log(armazena);';
    echo 'console.log("obj");';
    echo 'console.log(object);';
    echo 'console.log("index");';
    echo 'console.log(indexOfArray(armazena, object));';
    echo 'armazena.splice(indexOfArray(armazena, object), 1);';
    echo 'lista(armazena);';
    echo '}';
    echo 'function lista(list) {';
    echo 'const table = list.map(i => {';
    echo 'return `<tr><td value="${i[0]}"class="saborEscolhido">${i[1]}</td><td><input type="button" value="-" onclick="del(this)"></td></tr>`';
    echo '});';
    echo 'return document.getElementById("lista").innerHTML = table.join("");';
    echo '}';
    echo 'function visible(sabores) {';
    echo 'console.log(armazena);';
    echo 'let element;';
    echo 'sabores.forEach(sabor => {';
    echo 'if (!sabor.hidden) {';
    echo 'return element = sabor;';
    echo '}';
    echo '});';
    echo 'return element;';
    echo '}';
    echo 'function preencheSabores(){';
    echo 'let componenteSabores = document.getElementById("componenteSabores");';
    echo 'let saboresEscolhidos = document.querySelectorAll(".saborEscolhido");';
    echo 'componenteSabores.value="";';
    echo 'saboresEscolhidos.forEach(sabor => {';
    echo 'if (!sabor.hidden) {';
    echo 'console.log(sabor);';
    echo 'componenteSabores.value=componenteSabores.value + (componenteSabores.value == "" ? "" : ",") + sabor.attributes[0].nodeValue;';
    echo '}';
    echo '});';
    echo  '}';
    echo 'document.insertPizzaNaComanda.onsubmit = (evt) => {';
    echo 'if (armazena.length <= 0) {';
    echo 'evt.preventDefault();';
    echo '}';
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