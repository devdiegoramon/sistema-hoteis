<div class="modal fade" id="modalRelatorio" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Relatório de reservas </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-2" method="POST" action="relatorioReserva.php">
                    <div class='col-md-12'>
                        <label class="form-label"> Acomodação </label>
                        <select name="acomodacao" class='form-select form-select-sm'> 
                            <?php
                            $sqlSelectAcomodacao = "SELECT idacomodacao, nome FROM acomodacao";
                            $resultSelectAcomodacao = mysqli_query($con, $sqlSelectAcomodacao);
                            if (mysqli_num_rows($resultSelectAcomodacao) > 0) {
                                echo "<option value='todas'> Todas </option>";
                                while ($rowAcomodacao = mysqli_fetch_array($resultSelectAcomodacao)) {
                                    echo "<option value='$rowAcomodacao[0]'> $rowAcomodacao[1] </option> ";
                                }
                            } else {
                                echo "<option disabled> $rowAcomodacao[1] </option> ";
                            }
                            ?>
                        </select> 
                    </div>
                    <div style='margin-left: 5px' class="col-md-12 form-check mt-2 mb-1">
                        <input class="form-check-input" onclick="todoPeriodoRelatorio()" type="checkbox" checked value="todo" name="todoPeriodo" id="todoPeriodo">
                        <label class="form-check-label" for="flexCheckDefault">
                            Todo periodo
                        </label>
                    </div>
                    <div class="col-md-12 pb-2">
                        <label class="form-label">Range</label>                                    
                        <div class="input-daterange input-group" data-provide="datepicker">
                            <input type="text" disabled required autocomplete="off" class="input-sm form-control form-control-sm date datepicker-rel" name="start" id='startrel'  data-date-format="dd/mm/yyyy">
                            <span class="input-group-addon range-to" style="margin: 0 5px;">até</span>
                            <input type="text" disabled required autocomplete="off" class="input-sm form-control form-control-sm date datepicker-rel" name="end" id='endrel' data-date-format="dd/mm/yyyy">
                        </div>
                    </div>
                    <div> 
                        <button class='btn btn-sm btn-success float-end'> Continuar </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>