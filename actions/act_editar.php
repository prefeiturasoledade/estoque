<?php

// Só usuários logados (sem isto qualquer um alterava o estoque pelo link)
require_once "../../assets/secure/acesso.php";

require_once "../../../config/pdo_database.php";
require_once "../../assets/functions/tools.php";

$id_produtos = strip_tags(trim($_GET['alteraid']));

if(isset($_POST['editar'])){
    
  $id_produtos = strip_tags(trim($_POST['id_produtos']));
  $tipo = strip_tags(trim($_POST['tipo']));
  $descricao = strip_tags(trim($_POST['descricao']));
  $modelo = strip_tags(trim($_POST['modelo']));
  $marca = strip_tags(trim($_POST['marca']));
  $quantidade = strip_tags(trim($_POST['quantidade']));
  $secretarias = strip_tags(trim($_POST['secretarias']));

  $valor_un = acertaValorBanco(strip_tags(trim($_POST['valor_un'])));

  $sql = $pdo->prepare("UPDATE produtos SET tipo = :tipo, descricao = :descricao, modelo = :modelo, marca = :marca, quantidade = :quantidade, 
    secretarias = :secretarias, valor_un = :valor_un WHERE id_produtos = :id_produtos");
  $sql->bindParam(':tipo', $tipo);
  $sql->bindParam(':descricao', $descricao);
  $sql->bindParam(':modelo', $modelo);
  $sql->bindParam(':marca', $marca);
  $sql->bindParam(':quantidade', $quantidade);
  $sql->bindParam(':secretarias', $secretarias);
  $sql->bindParam(':valor_un', $valor_un);
  $sql->bindParam(':id_produtos', $id_produtos);  

  if ($sql->execute()) {
    header('Location: ../');
  } else {
    echo "<script>alert('Oops... Não foi possível concluir a operação. Contate o admnistrador ou o desenvolvedor do sistema.'); window.location.href='https://pmsoledaders.inf.br/admin/site/estoque/';</script>";
  }
}

?>
