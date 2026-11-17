<?php 

// Só usuários logados (sem isto qualquer um alterava o estoque pelo link)
require_once "../../assets/secure/acesso.php";

require_once "../../../config/pdo_database.php";

if(isset($_GET['apagaid'])){

    $id_dispensa = strip_tags(trim($_GET['apagaid']));

    $sql = $pdo->prepare("SELECT produtos.id_produtos,produtos.quantidade,produtos.tipo, dispensados.id_dispensados, dispensados.n_id_produtos, dispensados.quantidade_d 
    FROM produtos JOIN dispensados WHERE produtos.id_produtos = dispensados.n_id_produtos AND dispensados.id_dispensados = $id_dispensa; ");
    $sql->execute();
    
    $result = $sql->fetch(PDO::FETCH_ASSOC);

    if($result['quantidade_d'] != 0) {   
    
        $altera = $result['id_produtos'];
        $valor1 = $result['quantidade_d'];
        $valor2 = $result['quantidade'];

        $total = $valor2 + $valor1;
        
        $sql = $pdo->prepare("UPDATE produtos SET quantidade = $total WHERE id_produtos = $altera");
        $sql->execute();

        $sql = $pdo->prepare("DELETE FROM dispensados WHERE id_dispensados = $id_dispensa");

        if ($sql->execute()) {
            header('Location: ../dispensados.php');
        } else {
            echo "<script>alert('Oops... Não foi possível concluir a operação. Contate o admnistrador ou o desenvolvedor do sistema.'); window.location.href='https://pmsoledaders.inf.br/admin/site/estoque/dispensados.php';</script>";
        }

    } else {
        echo "<script>alert('Dispensação está zero! Não altera as quantidades do estoque.'); document.location='../dispensados.php'</script>";
        $sql = $pdo->prepare("DELETE FROM dispensados WHERE id_dispensados = $id_dispensa");
        $sql->execute();
    }   

   }
?>
