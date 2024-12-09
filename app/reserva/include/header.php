<?php
if (!isset($_GET['id'])) {
    header("Location: ../../index.php?text=Sem Acesso&type=1");
}
?>
<div class="row d-flex justify-content-md-between"> 
    <div class="col-6 d-flex align-items-start mb-3"> 
        <?php
        if ($rowReserva[12] != 'c') {
            echo $statusCheckIn;
            echo $statusCheckOut;
        } else {
            echo "<span class='my-badge badge bg-danger'> Reserva cancelada </span>";
        }
        ?> 
    </div>
    <!--//VERIFICAÇÃO DE BOTÕES DE CHECKIN-->
    <div class="col-md-6 d-flex justify-content-md-end justify-content-sm-start"> 
        <?php if ($rowReserva[12] !== 'c') { ?>
            <div> 
                <?php
                if ($rowReserva['datacheckin'] == null) {
                    if (date("Y-m-d") >= $rowReserva['entradaprevista']) {
                        //REALIZAR CHECKIN
                        echo "<a onclick='realizarCheckin($idreserva)' class='btn btn-sm btn-success' > Realizar Check-in </a>";
                    } else {
                        //NÃO É POSSÌVEL REALIZAR CHECKIN
                        echo '<a onclick="checkinFake()" disabled class="btn btn-sm btn-secondary" > Check-in </a>';
                    }
                } else {
                    //DADOS DO CHECKIN APÒS ELE SER REALIZADO
                    echo "<a onclick='dadosCheckin($idreserva)' class='btn btn-sm btn-secondary'> Dados do Check-in </a>";
                }
                ?>
            </div>

            <div style="margin:0 10px;"> 
                <?php
                //VERIFICAÇÃO DE BOTÕES DE CHECKOUT
                if ($rowReserva['datacheckin'] != null) {
                    if ($rowReserva['datacheckout'] != null) {
                        ?>
                        <a onclick="dadosCheckout(<?= $idreserva ?>)" class = "btn btn-sm btn-secondary" > Dados do Check-out </a>
                    <?php } else {
                        ?>
                        <a onclick="realizarCheckout(<?= $idreserva . ',' . $rowReserva['idconsumo'] ?>)" class="btn btn-sm btn-success" > Realizar Check-out </a>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
        ?>
        <div> 
            <?php
            //VERIFICAÇÃO DE BOTÕES DE CANCELAR RESERVA
            if ($rowReserva[12] !== 'c' and $rowReserva[12] !== 'f') {
                /**
                 * Caso o já tenha sido realizado o checkin.
                 * Será aberto outro modal com solicitando senha de administrador para cancelar.
                 */
                if ($rowReserva['datacheckin'] == null and $rowReserva['datacheckout'] == null) {
                    echo '<a class="btn btn-danger btn-sm" onclick="mCancelarReserva()"> Cancelar reserva </a>';
                } else {
                    echo '<a class="btn btn-danger btn-sm" onclick="mCancelarReservaAdministrador()"> Cancelar reserva </a>';
                }
                ?> 

                <!--MODAL DE CONFIRMAR CANCELAMETNO;-->
                <div class="modal" tabindex="-1" id="mCancelarReserva">
                    <div class="modal-dialog">
                        <div class="modal-content p-3">
                            <div class="row">
                                <iframe src="https://embed.lottiefiles.com/animation/29407"></iframe>
                                <h5> Essa reserva será cancelada, deseja mesmo continuar? </h5>
                            </div>
                            <div class="row pt-3 border-top "> 
                                <div class="col-6"> 
                                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NÃO</button>
                                </div>
                                <div class="col-6"> 
                                    <a href="include/cancelarReserva.php?idreserva=<?= $idreserva ?>" style="float:right;" type="button" class="btn btn-primary">SIM</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--MODAL DE CONFIRMAR CANCELAMETNO;-->
                <div class="modal" tabindex="-1" id="mCancelarReservaAdministrador">
                    <div class="modal-dialog">
                        <div class="modal-content p-3">
                            <div class="row">
                                <iframe src="https://embed.lottiefiles.com/animation/29407"></iframe>
                                <h5> Essa reserva será cancelada, deseja mesmo continuar? </h5>
                            </div>
                            <form class='p-4 border shadow-sm' id='form_confirmação' action='include/aCancelarReservaAdm.php?idreserva=<?= $idreserva ?>' method='POST'> 
                                <h6 class='text-center'> Informe usuário e senha de administrador</h6>
                                <div class="form-group"> 
                                    <label> Usuário </label>
                                    <input name='loginadministrador' type="text" class="form-control" placeholder='Login de administrador' required>
                                </div>
                                <div class="form-group"> 
                                    <label> Senha </label>
                                    <input name='senha' type="password" class="form-control" placeholder='Senha de administrador' required>
                                </div>
                            </form>
                            <div class="pt-3" id='botao_confirmacao'> 
                                <button style="float:right;" form='form_confirmação' type="submit" class="btn btn-primary">Continuar</button>
                            </div>
                            <div class="row pt-3" id='botao_prosseguir'> 
                                <div class="col-6"> 
                                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">NÃO</button>
                                </div>
                                <div class="col-6"> 
                                    <a onclick='abrirConfirmacao()' style="float:right;" type="button" class="btn btn-primary">SIM</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
//VERIFICAÇÃO DE BOTÕES DE CHECKOUT
    if ($rowReserva[12] != 'c') {
        ?>
<!--        <div class="col-12 mt-2"> 
            <span class="text-black-50 float-end" style="font-size:80%;"> * Só é possível cancelar a reserva enquanto o check-in <b>não</b> foi realizado </span>
        </div>-->
    <?php } ?>
</div>