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
    <title>Departamento de Tecnologia da Informação – Equipamentos Dispensados</title>
</head>

<body>
    <br><br><br><br><br>

    <?php
        $sql = $pdo->prepare("SELECT quantidade_d FROM vw_dispensados");
        $sql->execute();
        
        $i = 0;
        while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $teste2 = $row['quantidade_d'];
            $arrayquant[$i] = $teste2;
            $i++;                       
        }
        $quant = somaQuant($arrayquant);
    ?>

    <div class="container">
        <h4><b>Equipamentos Dispensados</b></h4>
        <span>Produtos alocados pelo Departamento de Tecnologia da Informação da Prefeitura Municipal de Soledade/RS. Em caso de dúvidas,
            contate o administrador ou o desenvolvedor do sistema.</span><hr><br>
        <span><?php echo "Produtos dispensados: <b>$quant</b>"; ?></span>                  
        <br>        
    </div>
    
    <br>
    <br>

    <div class="container">

    <span><a title="Estoque" class="btn btn-orange btn-sm nounderline mb-2" href="index.php">Retornar ao Estoque</a>

        <span>
            <div class="col-md-9 busca">
                <form action="buscar_dispensados.php" method="post">
                    <div class="row">
                        <input class="form-control form-control-sm mr-sm-2" type="hidden" name="quantidade">
                        <div class="col busca-div">
                            <input title="Data" class="form-control form-control-sm mr-sm-2" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Data Dispensação" aria-label="Pesquisar" name="data_retirada">
                        </div>
                        <div class="col busca-div">
                            <input title="Tipo" class="form-control form-control-sm mr-sm-2" type="search" placeholder="Tipo" aria-label="Pesquisar" name="tipo">
                        </div>
                        <div class="col busca-div">
                            <input title="Marca" class="form-control form-control-sm mr-sm-2" type="search" placeholder="Marca" aria-label="Pesquisar" name="marca">
                        </div>
                        <div class="col busca-div">
                            <select title="Secretaria" class="form-select form-select-sm basic" name="secretarias" id="secretarias">
                                <option disabled selected hidden value="">Origem</option>
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
                        <div class="col busca-div">
                            <select title="Setor" class="form-select form-select-sm basic" name="loc_title" id="loc_title">
                                <option disabled selected hidden value="">Destino</option>
                                <?php
                                $sql = $pdo->prepare("SELECT loc_title FROM locais ORDER BY loc_title ASC");
                                $sql->execute();
                                while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <option value="<?php echo $linha['loc_title'] ?>"><?php echo $linha['loc_title'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col" style="display:flex;">
                            <button title="Pesquisar" class="mt btn btn-orange-outline btn-sm my-sm-0 mt-1" style="margin-right:10px;" type="submit">Pesquisar</button>
                            <button title="Limpar" class="mt btn btn-orange-outline btn-sm my-sm-0 mt-1" type="reset">Limpar</button>
                        </div>
                    </div>
                </form>

            </div>

        </span>

        <br><br><br>
        <br>

        <div class="table-responsive tabs">
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

                        <?php
                        
                        $sql = $pdo->prepare("SELECT * FROM vw_dispensados ORDER BY data_retirada DESC");
                        $sql->execute();
                        $publishers = $sql->fetchAll(PDO::FETCH_ASSOC);

                        if ($publishers) {
                            foreach ($publishers as $publisher) {

                        ?>

                    <tr>
                        <td><?php echo acertaData($publisher['data_retirada']); ?></td>
                        <td><?php echo strip_tags($publisher['tipo']); ?></td>
                        <td><?php echo strip_tags($publisher['marca']); ?></td>
                        <td><?php echo strip_tags($publisher['modelo']); ?></td>
                        <td align="right"><?php echo strip_tags($publisher['quantidade_d']); ?></td>
                        <td><?php echo strip_tags($publisher['secretaria']); ?></td>
                        <td><?php echo strip_tags($publisher['setor']); ?></td>
                        <td><?php echo strip_tags($publisher['solicitante']); ?></td>
                        <td><?php echo strip_tags($publisher['responsavel']); ?></td>

                        <?php 
                        if ($_SESSION['nivel'] == "Mestre") {
                            echo "
                            <td>
                            <a title='Apagar' onclick='return apaga();' class='btn btn-dark' href='actions/act_apagar_dispensados.php?apagaid={$publisher['id_dispensados']}' class='nounderline'>
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