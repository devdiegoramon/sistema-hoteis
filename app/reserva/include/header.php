<?php
if (!isset($_GET['id'])) {
    header("Location: ../../index.php?text=Sem Acesso&type=1");
    exit();
}

date_default_timezone_set('America/Recife');
$hoje = date('Y-m-d');
// Garante que $rowReserva e seus índices existem antes de usá-los
$statusReserva = $rowReserva[12] ?? null;
$entradaPrevista = $rowReserva['entradaprevista'] ?? null;
$idreserva = $_GET['id']; // Geralmente o id da reserva é pego do GET
$idconsumo = $rowReserva['idconsumo'] ?? null;

?>

<div class="mt-3">
    <?php
    if ($statusReserva == 'p' && $entradaPrevista && $hoje < $entradaPrevista) {
        echo alerta("Não é possível realizar check-in antes da data de entrada", 2);
    }
    ?>
</div>

<div class="row d-flex justify-content-md-between">
    <div class="col-md-6 d-flex align-items-start mb-3">
        <?php
        if ($statusReserva == 'p') {
            echo statusReserva('p', 1);
        } elseif ($statusReserva == 'i') {
            echo statusReserva('i', 1);
        } elseif ($statusReserva == 'f') {
            echo statusReserva('f', 1);
            if (isset($rowReserva['datacheckout']) && $rowReserva['datacheckout']) { // Verifica se check-out foi feito
                 echo statusReserva('f', 2);
            }
        } elseif ($statusReserva == 'c') {
            echo "<span class='my-badge badge bg-danger'> Reserva cancelada </span>";
        }
        ?>
    </div>

    <div class="col-md-6 d-flex justify-content-md-end justify-content-sm-start">
        <?php if ($statusReserva == 'p' && $entradaPrevista) { ?>
            <div style="margin-right: 10px;">
                <?php
                if ($hoje >= $entradaPrevista) {
                    echo "<a onclick='realizarCheckin(" . $idreserva . ")' class='btn btn-sm btn-success'> <i class='fa-solid fa-check'></i> Realizar Check-in </a>";
                } else {
                    echo "<button disabled class='btn btn-sm btn-success'> <i class='fa-solid fa-check'></i> Realizar Check-in </button>";
                }
                ?>
            </div>
            <div>
                <a class="btn btn-danger btn-sm" onclick="mCancelarReserva()"> <i class="fa-solid fa-trash"></i> Cancelar reserva </a>
            </div>

        <?php } elseif ($statusReserva == 'i') { ?>
            <div style="margin-right: 10px;">
                <?php
                 echo '<a onclick="realizarCheckout(' . $idreserva . ',' . ($idconsumo ?? 'null') . ')" class="btn btn-sm btn-warning"> <i class="fa-solid fa-right-from-bracket"></i> Realizar Check-out </a>';
                ?>
            </div>
             <div>
                <a class="btn btn-danger btn-sm" onclick="mCancelarReservaAdministrador()"> <i class="fa-solid fa-trash"></i> Cancelar reserva </a>
            </div>
        <?php } ?>
    </div>
</div>
<hr>

<?php if ($statusReserva == 'p' || $statusReserva == 'i') { ?>
    <div class="modal" tabindex="-1" id="mCancelarReserva">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Confirmar Cancelamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe width="150" height="150" src="https://embed.lottiefiles.com/animation/29407" style="border:none;"></iframe>
                    <h5> Essa reserva será cancelada, deseja mesmo continuar? </h5>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                    <a href="include/cancelarReserva.php?idreserva=<?= $idreserva ?>" type="button" class="btn btn-danger">Sim, cancelar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="mCancelarReservaAdministrador">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <div class="modal-header border-0">
                     <h5 class="modal-title">Confirmar Cancelamento</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                 <div class="modal-body">
                    <form id='form_confirmacao_adm' action='include/aCancelarReservaAdm.php?idreserva=<?= $idreserva ?>' method='POST'>
                        <h6 class='text-center'> Para cancelar uma reserva já iniciada, informe o usuário e senha de administrador:</h6>
                        <div class="form-group mt-3">
                            <label> Usuário </label>
                            <input name='loginadministrador' type="text" class="form-control" placeholder='Login de administrador' required>
                        </div>
                        <div class="form-group mt-2">
                            <label> Senha </label>
                            <input name='senha' type="password" class="form-control" placeholder='Senha de administrador' required>
                        </div>
                    </form>
                 </div>
                 <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                    <button form='form_confirmacao_adm' type="submit" class="btn btn-danger">Sim, cancelar</button>
                 </div>
            </div>
        </div>
    </div>
<?php } ?>