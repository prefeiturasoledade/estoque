<?php

/* --- AVISO DE ATENÇÃO --- */
// Seguir essa ordem de inclusão de arquivos,
//visando o melhor funcionamento do sistema:

// 1. Arquivo de segurança
require_once "../assets/secure/acesso.php";

// 2. Base de dados
require_once "../../config/pdo_database.php";

// 3. Cabeçalho da página
require_once "../assets/includes/cabecalho.php";

// 4. Funções Gerais
require_once "../assets/functions/tools.php";

// Em caso de problemas, contate o administrador
//ou o desenvolvedor do sistema.
/* --- FIM AVISO DE ATENÇÃO --- */

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title>Departamento de Tecnologia da Informação – Busca de Equipamentos</title>
</head>

<body>

    <br><br><br><br><br>

    <?php 

    $tipo = strip_tags(trim($_POST['tipo']));
    $descricao = strip_tags(trim($_POST['descricao']));
    $marca = strip_tags(trim($_POST['marca']));
    $secretarias = strip_tags(trim($_POST['secretarias']));
        
    try {
        $sql = $pdo->prepare("SELECT SUM(valor_un * quantidade) AS valor, COUNT(id_produtos) AS total FROM produtos WHERE 
        secretarias LIKE :secretarias AND tipo LIKE :tipo AND descricao LIKE :descricao AND marca LIKE :marca ");
        $sql->bindValue(":tipo", "%".$tipo."%");
        $sql->bindValue(":descricao", "%".$descricao."%");
        $sql->bindValue(":marca", "%".$marca."%");
        $sql->bindValue(":secretarias", "%".$secretarias."%");
        
        $sql->execute();
       
    } catch (Exception $e) {
        echo 'Exceção: ',  $e->getMessage(), "\n";
    }

    while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        $total = $row['total'];
        $valor = number_format($row['valor'],2,",",".");
    };

    ?>

    <div class="container">
        <h4><b>Resultado da Busca por Equipamentos</b></h4>
        <span>Busca de equipamentos disponíveis no Departamento de Tecnologia da Informação da Prefeitura Municipal de Soledade/RS. Em caso de dúvidas,
            contate o administrador ou o desenvolvedor do sistema.</span>
        <hr><br>
        <span>Estoque encontrado: <b><?php echo $total; ?></b></span>
        <br>
        <span>Valor do estoque encontrado: <b>R$ <?php echo $valor; ?></b></span>                   
        <br>
    </div>
    
    <br><br>

    <div class="container">

    <span><a title="Voltar" class="btn btn-blue btn-sm" href="index.php" class="nounderline">Retornar</a></span>

    <br><br>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr>
                        <th scope="col">Tipo</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">R$</th>
                        <th scope="col">Quant.</th>
                        <th scope="col">Secretaria</th>
                        <th scope="col">Responsável</th>
                        <?php 
                        if ($_SESSION['nivel'] == "Mestre") {
                            echo "
                                <th></th>
                                <th></th>
                                <th></th>
                            ";
                        }
                        ?>
                    </tr>

                    <?php

                    $sql = $pdo->prepare("SELECT * FROM produtos WHERE secretarias LIKE :secretarias AND tipo LIKE :tipo 
                    AND descricao LIKE :descricao AND marca LIKE :marca ");
                    $sql->bindValue(":tipo", "%".$tipo."%");
                    $sql->bindValue(":descricao", "%".$descricao."%");
                    $sql->bindValue(":marca", "%".$marca."%");
                    $sql->bindValue(":secretarias", "%".$secretarias."%");
                    
                    $sql->execute();
                    $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

                    if ($rows) {
                        foreach ($rows as $row) {

                    ?>

                            <tr>
                                <td><?php echo strip_tags($row['tipo']); ?></td>
                                <td><?php echo strip_tags($row['marca']); ?></td>
                                <td><?php echo strip_tags($row['modelo']); ?></td>
                                <td><?php echo strip_tags($row['descricao']); ?></td>
                                <td align="right"><?php $valor = strip_tags($row['valor_un']); echo number_format($valor, 2, ',', '.'); ?></td>
                                <td align="right"><?php echo strip_tags($row['quantidade']); ?></td>
                                <td><?php echo strip_tags($row['secretarias']); ?></td>
                                <td><?php echo strip_tags($row['responsavel']); ?></td>

                                <?php 
                                if($_SESSION['nivel'] == "Mestre") {
                                    echo "
                                    <td>
                                        <a title='Dispensar' class='mt btn btn-blue' href='dispensar.php?dispensaid={$row['id_produtos']}' class='nounderline'>
                                        <i class='bi bi-send'></i></a>
                                    </td>
                                    <td>
                                        <a title='Editar' class='mt btn btn-orange' href='editar.php?editaid={$row['id_produtos']}' class='nounderline'>
                                        <i class='bi bi-pencil-square'></i></a>
                                    </td>
                                    <td>
                                        <a title='Apagar' onclick='return apaga();' class='mt btn btn-dark' href='actions/act_apagar.php?apagaid={$row['id_produtos']}' class='nounderline'>
                                        <i class='bi bi-trash'></i></a>
                                    </td> 
                                    ";
                                }
                                ?>

                            </tr>

                    <?php
                        }
                    } else {
                        echo "bahh";
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>

    <?php 
    
    // Requere o rodapé da página
    require_once '../assets/includes/rodape.php'; 
    
    ?>

</body>

</html>