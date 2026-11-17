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
    <title>Departamento de Tecnologia da Informação – Estoque</title>
</head>

<body>
    <br><br><br><br><br>

    <div class="container">
        <!-- Apresentação -->
        <h4><b>Estoque</b></h4>
        <span>Estoque de equipamentos disponíveis no Departamento de Tecnologia da Informação, sendo utilizados por todas as secretarias da 
            Prefeitura Municipal de Soledade, no Rio Grande do Sul. <i>A apresentação dos itens pode variar, seja por embalagem ou mesmo marca.</i><br>
        </span><hr><br>

        <!-- Contas de quantidade e valor -->
        <?php
        $sql = "SELECT SUM(valor_un * quantidade) AS valor, COUNT(id_produtos) AS total FROM produtos";
        $stmt = $pdo->prepare($sql); $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $total = $row['total'];
            $valor = number_format($row['valor'],2,",",".");
        }
        ?>

        <span><?php echo "Produtos no estoque: <b>$total</b>"; ?></span><br>
        <span><?php echo "Valor do estoque: <b>R$ $valor</b>"; ?></span>                   
        <br><br>
    </div>
    
    <br>

    <div class="container">

        <a title="Novo" class="btn btn-orange btn-sm nounderline mb-2 cel" href="adicionar.php">Adicionar Equipamento</a>

        <?php 
        
        if($_SESSION['nivel'] == "Mestre") {           
            echo "
            <span><a title='Dispensados' class='btn btn-blue-outline btn-sm nounderline mb-2 cel2' href='dispensados.php'>Dispensados</a>
            ";
        }
        ?>

        <span>

            <div class="col-md-8 busca">
                <form action="buscar.php" method="post">
                    <div class="row">
                        <input class="form-control form-control-sm mr-sm-2" type="hidden" name="quantidade">
                        <div class="col busca-div">
                            <input title="Tipo" class="form-control form-control-sm mr-sm-2" type="search" placeholder="Tipo" aria-label="Pesquisar" name="tipo">
                        </div>
                        <div class="col busca-div">
                            <input title="Descrição" class="form-control form-control-sm mr-sm-2" type="search" placeholder="Descrição" aria-label="Pesquisar" name="descricao">
                        </div>
                        <div class="col busca-div">
                            <input title="Marca" class="form-control form-control-sm mr-sm-2" type="search" placeholder="Marca" aria-label="Pesquisar" name="marca">
                        </div>
                        <div class="col busca-div">
                            <select title="Secretaria" class="form-select form-select-sm basic" name="secretarias" id="secretarias">
                                <option disabled selected hidden value="">Secretaria</option>
                                <?php
                                $sql = $pdo->prepare("SELECT sec_title FROM secretarias ORDER BY sec_title ASC");
                                $sql->execute();
                                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <option value="<?php echo $linha['sec_title'] ?>"><?php echo $linha['sec_title'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col" style="display:flex;">
                            <button title="Pesquisar" class="btn btn-orange-outline btn-sm my-sm-0" style="margin-right:10px;" type="submit">Pesquisar</button>
                            <button title="Limpar" class="btn btn-orange-outline btn-sm my-sm-0" type="reset">Limpar</button>
                        </div>
                    </div>
                </form>

            </div>

        </span>

        <br><br><br><br>

        <div class="table-responsive tabs">
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
                        if($_SESSION['nivel'] == "Mestre") {
                            
                            echo "
                            <th></th>
                            <th></th>
                            <th></th>
                            ";
                        }
                        ?>
                    </tr>

                    <?php
                    
                                      
                    $sql = $pdo->prepare("SELECT * FROM produtos ORDER BY tipo ASC");
                    $sql->execute();
                    $publishers = $sql->fetchAll(PDO::FETCH_ASSOC);

                    if ($publishers) {
                        foreach ($publishers as $publisher) {

                    ?>

                            <tr>
                                <td><?php echo strip_tags($publisher['tipo']); ?></td>
                                <td><?php echo strip_tags($publisher['marca']); ?></td>
                                <td><?php echo strip_tags($publisher['modelo']); ?></td>
                                <td><?php echo strip_tags($publisher['descricao']); ?></td>
                                <td align="right"><?php $valor = strip_tags($publisher['valor_un']); echo number_format($valor, 2, ',', '.'); ?></td>
                                <td align="right"><?php echo strip_tags($publisher['quantidade']); ?></td>
                                <td><?php echo strip_tags($publisher['secretarias']); ?></td>
                                <td><?php echo strip_tags($publisher['responsavel']); ?></td>
                                
                                <?php 
                                if($_SESSION['nivel'] == "Mestre") {
                                    echo "
                                    <td>
                                        <a title='Dispensar' class='mt btn btn-blue' href='dispensar.php?dispensaid={$publisher['id_produtos']}' class='nounderline'>
                                        <i class='bi bi-send'></i></a>
                                    </td>
                                    <td>
                                        <a title='Editar' class='mt btn btn-orange' href='editar.php?editaid={$publisher['id_produtos']}' class='nounderline'>
                                        <i class='bi bi-pencil-square'></i></a>
                                    </td>
                                    <td>
                                        <a title='Apagar' onclick='return apaga();' class='mt btn btn-dark' href='actions/act_apagar.php?apagaid={$publisher['id_produtos']}' class='nounderline'>
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