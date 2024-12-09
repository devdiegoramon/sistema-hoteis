<div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agendar / reservar uma acomodação </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-2" id="formulario-reserva" action="include/gReserva.php" method="POST">
                    <div class="col-md-12 pb-2 border-bottom">
                        <label class="form-label">Range</label>                                    
                        <div class="input-daterange input-group" data-provide="datepicker">
                            <input type="text" autocomplete="off" class="input-sm form-control form-control-sm date datepicker" onchange="resetRange()" name="start" id="start" data-date-format="dd/mm/yyyy">
                            <span class="input-group-addon range-to" style="margin: 0 5px;">até</span>
                            <input type="text" autocomplete="off" class="input-sm form-control form-control-sm date datepicker" onchange="resetRange()" name="end" id="end" data-date-format="dd/mm/yyyy">
                        </div>
                        <div class="row">
                            <div class="col-md-6"> 
                                <span class="badge bg-secondary mt-3" id="resposta-verificacao">  </span>
                            </div>
                            <div class="col-md-6 d-flex justify-content-md-end justify-content-sm-start"> 
                                <a onclick="verificarDisp()" class="badge bg-success text-white mt-3 mb-2" style="cursor:pointer;"> verificar disponibilidade</a>
                            </div>
                        </div>
                    </div>
                    <div id="conteudo-reserva"> 
                        <div class="col-md-12 mt-3">
                            <label class="form-label" for="acomodacao"> Acomodações disponíveis </label>
                            <select class="form-select form-select-sm select2" onchange="calculaEstadia()" disabled required name="acomodacao" id="select-acomodacao" title="Acomodação para reserva">

                            </select>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label" for="cpf"> Cliente (Nome e CPF)  </label>
                            <select class="form-select form-select-sm select2" required name="cliente" title="Cpf do cliente">
                                <?php
                                $sqlConsultaCli = "SELECT idcliente, nome, cpf FROM cliente WHERE ativo = 's'";
                                $resultConsultaCli = mysqli_query($con, $sqlConsultaCli);
                                if (mysqli_num_rows($resultConsultaCli) > 0) {
                                    echo '<option selected value="" class="form-option">Selecione uma opção</option>';
                                    while ($rowCli = mysqli_fetch_array($resultConsultaCli)) {
                                        echo '<option value="' . $rowCli[0] . '" class="form-option">' . $rowCli[1] . ' - ' . $rowCli[2] . '</option>';
                                    }
                                } else {
                                    echo '<option value="" class="form-option">Nenhum cliente cadastrado</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12 mt-3"> 
                            <label class="form-label" for="obs">Observação </label>
                            <textarea name="obs" disabled class="form-control" id="obs" rows="2"></textarea>
                        </div>
                        <div class="row"> 
                            <div class="col-md-8 mt-3">
                                <label class="form-label" id="label-hospedes" for="hospedes">Número de Hospedes </label>
                                <input class="form-control form-control-sm" disabled required type="number" id="hospedes" name="hospedes" title="Quantidade de hospedes">
                            </div>
                            <div class=" mt-3 col-md-4"> 
                                <label class="form-label" i for="diarias"> Diárias </label>
                                <input class="form-control form-control-sm" name="diarias" value="<?= $diarias ?>" id="diarias" readonly> 
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label" for="Cidade"> Valor da diária </label>
                                <div class="input-group input-group-sm mb-3">
                                    <span class="input-group-text" id="basic-addon1">R$</span>
                                    <input class="form-control form-control-sm money" name="valordiaria" value="" id="valordiaria" onkeyup='calculaValorFinal()'> 
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label" for="Cidade"> Valor da Estadia </label>
                                <div class="input-group input-group-sm mb-3">
                                    <span class="input-group-text" id="basic-addon1">R$</span>
                                    <input class="form-control form-control-sm" readonly required id="valor-estadia" name="valor" title="Quantidade de hospedes">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-end mt-3">
                            <button class="btn btn-sm btn-success" disabled type="submit" name="cadastrar" form="formulario-reserva" id="btn-reserva"> Cadastrar </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>