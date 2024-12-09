<?php
include "../../config/connMysql.php";
include "../../config/config.php";
include "../../include/func.php";
$idfuncionario = $_POST['idfuncionario'];
$sqlFuncionario = "SELECT nome,
                        login,
                        dtnascimento,
                        cpf,
                        nivel
                    FROM funcionario
                    WHERE idlogin = {$idfuncionario}";
$resultFuncionario = mysqli_query($con, $sqlFuncionario);
$rowFuncionario = mysqli_fetch_array($resultFuncionario);

$selectedN1 = '';
$selectedN2 = '';
if ($rowFuncionario[4] == 1) {
    $selectedN1 = 'selected';
} else {
    $selectedN2 = 'selected';
}
?>

<div class="modal-header">
    <h5 class="modal-title "> <?= $rowFuncionario[0] ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body col-12">
    <form class="row g-2" id="formulario-edtiar" action="include/aFuncionario.php" method="POST">
        <input value="<?= $idfuncionario ?>" type="text" name="idfuncionario" hidden="">
        <div class="col-md-12 mt-1"> 
            <label class="form-label" for="nome"> Nome completo </label>
            <input value="<?= $rowFuncionario[0] ?>" autocomplete="off" required class="form-control form-control-sm" type="text" name="nome">
        </div>

        <div class="col-md-6 mt-1"> 
            <label class="form-label" for="dtnasc"> Data de nascimento </label>
            <input value="<?= dataBrasil($rowFuncionario[2]) ?>" autocomplete="off" required class="form-control form-control-sm date datepicker" type="text" name="dtnasc">
        </div>

        <div class="col-md-6 mt-1"> 
            <label class="form-label" for="cpf"> CPF </label>
            <input value="<?= $rowFuncionario[3] ?>" autocomplete="off" required class="form-control form-control-sm cpf" type="text" name="cpf">
        </div>

        <div class="col-md-12 mt-1"> 
            <label class="form-label" for="nivel"> Função </label>
            <select required class="form-select form-select-sm" name="nivel">
                <option <?= $selectedN2 ?> value="2"> Funcionário </option>
                <option <?= $selectedN1 ?> value="1"> Administrador </option>
            </select>
        </div>

        <div class="col-md-6 mt-1"> 
            <label class="form-label" for="login"> Login </label>
            <input value="<?= $rowFuncionario[1] ?>" autocomplete="off" required class="form-control form-control-sm" type="text" name="login">
        </div>

        <div class="col-md-6 mt-2"> 
            <div class="form-check form-switch">
                <input onchange="verificaCheck()" class="form-check-input" type="checkbox" id="checkSenha">
                <label class="form-check-label" for="checkSenha"> Alterar senha </label>
            </div>
            <input disabled required class="form-control form-control-sm mt-1" id="senha" type="password" name="senha" placeholder="Nova Senha"> 
        </div>
    </form>
</div>
<div class="modal-footer">
    <button class="btn btn-sm btn-success" type="submit" name="editar" form="formulario-edtiar"> Salvar </button>
</div>

<script>

    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.date').mask('00/00/0000');
    $('.phone').mask('(00) 00000-0000');

    $('.datepicker').datepicker({
        language: "pt-BR",
        format: "dd/mm/yyyy",
        endDate: 'd'
    });

    function verificaCheck() {
        let check = $('#checkSenha');
        if (check.is(':checked')) {
            $('#senha').prop("disabled", false);
        } else {
            $('#senha').prop("disabled", true);
        }
    }
</script> 