<?php

// Só usuários logados (sem isto qualquer um alterava o estoque pelo link)
require_once "../../assets/secure/acesso.php";

require_once "../../../config/pdo_database.php";

$id_produtos = strip_tags(trim($_GET['dispensaid']));

if(isset($_POST['dispensar'])){
    
    $id_produtos = strip_tags(trim($_POST['id_produtos']));
    $quantidade = strip_tags(trim($_POST['quantidade']));
    $quantidade_d = strip_tags(trim($_POST['quantidade_d']));
    $solicitante = strip_tags(trim($_POST['solicitante']));
    $secretaria = strip_tags(trim($_POST['secretarias']));
    $setor = strip_tags(trim($_POST['setor']));
    $responsavel = strip_tags(trim($_POST['responsavel']));

    $data_retirada = date("Y-m-d");

    if ($quantidade_d > $quantidade) {
        echo "<script>alert('Oops... A quantidade dispensada é maior do que o estoque disponível!'); document.location='../index.php';</script>";
    } else {
        $vem = $quantidade - $quantidade_d;
    }

    $sql = $pdo->prepare("UPDATE produtos SET quantidade = :vem WHERE id_produtos = :id_produtos");
    $sql->bindParam(':vem', $vem);
    $sql->bindParam(':id_produtos', $id_produtos);

    if ($sql->execute()) {
        
      $sql = $pdo->prepare("INSERT INTO dispensados (data_retirada, solicitante, quantidade_d, secretaria, setor, n_id_produtos, responsavel) 
        VALUES (:data_r, :solicitante, :quant_d, :secretaria, :setor, :id_produtos, :responsavel)");
      $sql->bindParam(':data_r', $data_retirada);
      $sql->bindParam(':solicitante', $solicitante);
      $sql->bindParam(':quant_d', $quantidade_d);
      $sql->bindParam(':secretaria', $secretaria);
      $sql->bindParam(':setor', $setor);
      $sql->bindParam(':id_produtos', $id_produtos);
      $sql->bindParam(':responsavel', $responsavel);

      if ($sql->execute()) {
        header('Location: ../dispensados.php');
      } else {
        echo "<script>alert('Oops... Não foi possível concluir a operação. Contate o admnistrador ou o desenvolvedor do sistema.'); window.location.href='https://pmsoledaders.inf.br/admin/site/estoque/';</script>";
      }

    } else {
      echo "<script>alert('Oops... Não foi possível concluir a operação. Contate o admnistrador ou o desenvolvedor do sistema.'); window.location.href='https://pmsoledaders.inf.br/admin/site/estoque/';</script>";
    }
}

?>
