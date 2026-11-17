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
    <title>Departamento de Tecnologia da Informação – Buscar Equipamentos Dispensados</title>
</head>

<body>

    <br><br><br><br><br>

    <?php

    $data = strip_tags(trim($_POST['data_retirada']));    
    $tipo = strip_tags(trim($_POST['tipo']));
    $marca = strip_tags(trim($_POST['marca']));
    $setor = strip_tags(trim($_POST['loc_title']));
    $quantidade = strip_tags(trim($_POST['quantidade_d'])); 
    $responsavel = strip_tags(trim($_POST['responsavel']));
    $secretaria = strip_tags(trim($_POST['secretarias']));    

    $sql = "SELECT quantidade_d FROM vw_dispensados WHERE secretaria LIKE '%$secretaria%' AND setor LIKE '%$setor%' AND tipo LIKE '%$tipo%' AND data_retirada LIKE '%$data%' AND marca LIKE '%$marca%'";
    $stmt = $pdo->query($sql);
    $stmt->execute(array());
        $i = 0;
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $teste2 = $row['quantidade_d'];
        $arrayquant[$i] = $teste2;
        $i++;                       
    }
    $quant = somaQuant($arrayquant);

    ?>

    <div class="container">
        <h4><b>Resultado da Busca por Equipamentos Dispensados</b></h4>
        <span>Busca dos produtos dispensados pelo Departamento de Tecnologia da Informação da Prefeitura Municipal de Soledade/RS. Em caso de dúvidas,
            contate o administrador ou o desenvolvedor do sistema.</span>
        <hr><br>
        <span>Produtos encontrados: <b><?php echo $quant; ?></b></span>                  
        <br>   
    </div>
    
    <br>

    <div class="container">

        <br>

        <span><a title="Voltar" class="btn btn-blue btn-sm nounderline" href="dispensados.php">Retornar</a><span>

        <br><br>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr>
                        <th scope="col">Data</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Quantidade</th>
                        <th scope="col">Origem</th>
                        <th scope="col">Destino</th>
                        <th scope="col">Solicitante</th>
                        <th scope="col">Responsável</th>
                        <?php 
                            if ($_SESSION['nivel'] == "Mestre") {
                                echo "
                                    <th></th>
                                ";
                            }
                        ?>
                    </tr>

                    <?php

                    $query = ("SELECT * FROM vw_dispensados WHERE secretaria LIKE '%$secretaria%' AND setor LIKE '%$setor%' AND tipo LIKE '%$tipo%' AND data_retirada LIKE '%$data%' AND marca LIKE '%$marca%' ORDER BY data_retirada DESC");
                    $resultado = $pdo->prepare($query);
                    $resultado->execute();

                    $rows = $resultado->fetchAll(PDO::FETCH_ASSOC);

                    if ($rows) {
                        foreach ($rows as $row) {

                    ?>

                            <tr>
                                <td><?php echo acertaData($row['data_retirada']); ?></td>
                                <td><?php echo strip_tags($row['tipo']); ?></td>
                                <td><?php echo strip_tags($row['marca']); ?></td>
                                <td><?php echo strip_tags($row['modelo']); ?></td>
                                <td align="right"><?php echo strip_tags($row['quantidade_d']); ?></td>
                                <td><?php echo strip_tags($row['secretaria']); ?></td>
                                <td><?php echo strip_tags($row['setor']); ?></td>
                                <td><?php echo strip_tags($row['solicitante']); ?></td>
                                <td><?php echo strip_tags($row['responsavel']); ?></td>

                                <?php 
                                if ($_SESSION['nivel'] == "Mestre") {
                                    echo "
                                    <td>
                                    <a title='Apagar' onclick='return apaga();' class='btn btn-dark' href='actions/act_apagar_dispensados.php?apagaid={$row['id_dispensados']}' class='nounderline'>
                                        <i class='bi bi-trash'></i></a>
                                    </td>
                                    
                                    ";
                                }
                                ?>

                            </tr>

                    <?php
                        }
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