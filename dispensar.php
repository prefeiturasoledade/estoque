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
    <title>Departamento de Tecnologia da Informação – Dispensar Equipamento</title>
</head>

<body>
<?php
    if ($_SESSION['nivel'] !== "Mestre") {
        echo "<script>alert('Oops... Parece que você não tem permissão para acessar essa página. Se achar que é um erro, contate o administrador do sistema.'); window.location.href='https://pmsoledaders.inf.br/admin/site/'</script>";
    }
    ?>

    <br><br><br><br><br>

    <?php
    
    $id = strip_tags($_GET['dispensaid']);

    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_produtos = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $publisher = $stmt->fetch(PDO::FETCH_ASSOC);
    
    ?>

    <div class="container">
        <h4><b>Dispensar Equipamento</b></h4>
        <h6>Escolha o destino do produto disponível no Departamento de Tecnologia da Informação da Prefeitura Municipal de Soledade/RS. Em caso de dúvidas,
            contate o administrador ou o desenvolvedor do sistema.</h6>
        <hr>
    </div><br>

    <div class="container">
        <form action="actions/act_dispensar.php" method="post">
            <div class="row">
                <div class="col-md-12">
                    <input class="form-control" type="hidden" name="id_produtos" value='<?php echo $publisher['id_produtos'] ?>'>
                    <input class="form-control" type="hidden" name="quantidade" value='<?php echo $publisher['quantidade'] ?>'>
                    <input class="form-control" type="hidden" name="valor_un" value='<?php echo $publisher['valor_un'] ?>'>

                    <h6><b>Equipamento:</b></h6>

                    <label for="tipo">TIPO: <i><?php echo $publisher['tipo'] ?></i></label><br>
                    <label for="marca">MARCA: <i><?php echo $publisher['marca'] ?></i></label><br>
                    <label for="modelo">MODELO: <i><?php echo $publisher['modelo'] ?></i></label><br>
                    <label for="descricao">DESCRIÇÃO: <i><?php echo $publisher['descricao'] ?></i></label><br>
                    <label for="valor_un">VALOR UNITÁRIO: <i><?php $valor = strip_tags($publisher['valor_un']);
                                                                echo 'R$ ' . number_format($valor, 2, ',', '.'); ?></i></label><br>

                    <label for="secretarias">SECRETARIA: <i><?php echo $publisher['secretarias'] ?></i></label><br><br>
                    <label for="quantidade">QUANTIDADE ATUAL: <i><?php echo $publisher['quantidade'] ?></i></label><br><br>

                    <label for="setor"><b>Origem <span class="text-danger">*</span></b></label><br>
                    <select title="Secretaria" class="form-select basic" name="secretarias" id="secretarias">
                        <option disabled selected hidden value=""></option>
                        <?php
                        $sql = $pdo->prepare("SELECT sec_title FROM secretarias ORDER BY sec_title ASC");
                        $sql->execute();
                        while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <option value="<?php echo $linha['sec_title'] ?>"><?php echo $linha['sec_title'] ?></option>
                        <?php
                        }
                        ?>
                    </select><br>

                    <label for="setor"><b>Destino <span class="text-danger">*</span></b></label><br>
                    <select class="form-select basic" name="setor" id="setor" required>
                        <option disabled hidden selected value=""></option>
                        <?php
                        $sql = $pdo->prepare("SELECT loc_title FROM locais ORDER BY loc_title ASC");
                        $sql->execute();
                        while ($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <option value="<?php echo $linha['loc_title'] ?>"><?php echo $linha['loc_title'] ?></option>
                        <?php
                        }
                        ?>
                    </select><br>

                    <label for="solicitante"><b>Solicitante <span class="text-danger">*</span></b></label><br>
                    <input class="form-control" type="text" name="solicitante"  required>
                    <br>
                    <label for="quantidade_d"><b>Quantidade a dispensar <span class="text-danger">*</span></b></label><br>
                    <input class="form-control" type="number" name="quantidade_d"  required>

                    <input type="hidden" name="responsavel" value="<?php echo $_SESSION['nome']; ?>" required>

                    <br><br>
                    <input class="btn btn-orange" type="submit" name="dispensar" value="Dispensar">
                    <input class="btn btn-orange-outline" type="reset" name="limpa" value="Limpar formulário">
                    <a class="btn btn-blue" style="float:right;" href="index.php" name="voltar">Voltar</a>
                    <br><br><br>
                </div>
            </div>
        </form>
    </div>

    <?php 
    
    // Requere o rodapé da página
    require_once '../assets/includes/rodape.php'; 
    
    ?>

</body>

</html>