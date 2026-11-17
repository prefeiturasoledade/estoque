<?php 

// Só usuários logados (sem isto qualquer um alterava o estoque pelo link)
require_once "../../assets/secure/acesso.php";

require_once "../../../config/pdo_database.php";

if(isset($_GET['apagaid'])){

  $id = strip_tags(trim($_GET['apagaid']));

  $sql = $pdo->prepare("DELETE FROM produtos WHERE id_produtos = :id");
  $sql->bindParam(':id', $id);

  if ($sql->execute()) {
    header('Location: ../');
  } else {
    echo "<script>alert('Oops... Não foi possível concluir a operação. Contate o admnistrador ou o desenvolvedor do sistema.'); window.location.href='https://pmsoledaders.inf.br/admin/site/estoque/';</script>";
  }

}

?>
