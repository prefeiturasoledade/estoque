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
    <title>Departamento de Tecnologia da Informação – Novo Equipamento</title>
</head>

<body>
<?php
    if ($_SESSION['nivel'] !== "Mestre") {
        echo "<script>alert('Oops... Parece que você não tem permissão para acessar essa página. Se achar que é um erro, contate o administrador do sistema.'); window.location.href='https://pmsoledaders.inf.br/admin/site/'</script>";
    }
    ?>

    <br><br><br><br><br>

    <div class="container">
        <h4><b>Incluir Equipamento</b></h4>
        <h6>Inclua um novo equipamento no sistema de gerenciamento de estoque do Departamento de Tecnologia da Informação da Prefeitura Municipal de Soledade/RS. Em caso de dúvidas,
            contate o administrador ou o desenvolvedor do sistema.
        </h6>
        <hr>
    </div><br>

    <div>
        <form action="actions/act_adicionar.php" method="post">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">

                        <label for="tipo"><b>Tipo <span class="text-danger">*</span></b></label>
                        <input class="form-control" type="text" name="tipo" required>
                        <br>
                        <label for="marca"><b>Marca <span class="text-danger">*</span></b></label>
                        <input class="form-control" type="text" name="marca" required>
                        <br>
                        <label for="modelo"><b>Modelo <span class="text-danger">*</span></b></label>
                        <input class="form-control" type="text" name="modelo" required>
                        <br>
                        <label for="descricao"><b>Descrição <span class="text-danger">*</span></b></label>
                        <input class="form-control" type="text" name="descricao" required>
                        <br>
                        <label for="valor_un"><b>Valor Unitário <span class="text-danger">*</span></b></label>
                        <input class="form-control" type="text" name="valor_un" required>
                        <br>
                        <label for="quantidade"><b>Quantidade <span class="text-danger">*</span></b></label>
                        <input class="form-control" type="number" name="quantidade" required>
                        <br>
                        <label for="secretarias"><b>Secretaria <span class="text-danger">*</span></b></label>
                        <select class="form-select basic" name="secretarias" id="secretarias" required>
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
                        </select><br><br>

                        <input type="hidden" name="responsavel" value="<?php echo $_SESSION['nome'] ?>">

                        <input class="btn btn-orange" type="submit" name="adicionar" value="Inserir">
                        <input class="btn btn-orange-outline" type="reset" name="limpa" value="Limpar formulário">
                        <a title="Voltar" class="btn btn-blue" style="float:right;" href="index.php" name="voltar">Voltar</a>
                        <br><br><br>
                    </div>
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