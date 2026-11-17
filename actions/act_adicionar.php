<?php

require_once "../../../config/pdo_database.php";

if(isset($_POST['adicionar'])){

    $tipo = strip_tags(trim($_POST['tipo']));
    $descricao = strip_tags(trim($_POST['descricao']));
    $modelo = strip_tags(trim($_POST['modelo']));
    $marca = strip_tags(trim($_POST['marca']));
    $quantidade = strip_tags(trim($_POST['quantidade']));
    $secretarias = strip_tags(trim($_POST['secretarias']));
    $responsavel_c = strip_tags(trim($_POST['responsavel']));

    $valor_un = strip_tags(trim($_POST['valor_un']));
    $valor_un = str_replace('.', '', $valor_un);
    $valor_un = str_replace(',', '.', $valor_un);
    $valor_un = floor($valor_un * 100) / 100; 

    $sql = $pdo->prepare("INSERT INTO produtos (tipo, descricao, modelo, marca, quantidade, secretarias, valor_un, responsavel) VALUES (:tipo,:descricao,:modelo,:marca,:quantidade, :secretarias, :valor_un, :responsavel)");
    $sql->bindParam(':tipo', $tipo);
    $sql->bindParam(':descricao', $descricao);
    $sql->bindParam(':modelo', $modelo);
    $sql->bindParam(':marca', $marca);
    $sql->bindParam(':quantidade', $quantidade);
    $sql->bindParam(':secretarias', $secretarias);
    $sql->bindParam(':valor_un', $valor_un);
    $sql->bindParam(':responsavel', $responsavel_c);
    
    if ($sql->execute()) {
      header('Location: ../');
    } else {
      echo "<script>alert('Oops... Não foi possível concluir a operação. Contate o admnistrador ou o desenvolvedor do sistema.'); window.location.href='https://pmsoledaders.inf.br/admin/site/estoque/';</script>";
    }
}

?>