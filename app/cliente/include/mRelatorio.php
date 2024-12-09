<div class="modal fade" id="modalRelatorio" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Relatório de Clientes </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-check mb-3">
                    <input class="form-check-input" onclick="todoPeriodo()" type="checkbox" checked value="todo" name="todoPeriodo" id="todoPeriodo">
                    <label class="form-check-label" for="flexCheckDefault">
                        Todo periodo
                    </label>
                </div>
                <form class="row g-2" id='relatorio-cli' target="_blank" action="include/gRelatorioCliente.php" method="POST">
                    <div class="col-md-12 pb-2">
                        <label class="form-label">Range</label>                                    
                        <div class="input-daterange input-group" data-provide="datepicker">
                            <input type="text" disabled autocomplete="off" required class="input-sm form-control form-control-sm date datepicker" name="start" id="start" data-date-format="dd/mm/yyyy">
                            <span class="input-group-addon range-to" style="margin: 0 5px;">até</span>
                            <input type="text" disabled autocomplete="off" required class="input-sm form-control form-control-sm date datepicker" name="end" id="end" data-date-format="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class='col-md-12'>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" checked type="radio" name="clientes" id="todosCli" value="todos">
                            <label class="form-check-label" for="todosCli">Todos</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="clientes" id="ativosCli" value="ativos">
                            <label class="form-check-label" for="ativosCli">Ativos</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="clientes" id="inativosCli" value="inativos">
                            <label class="form-check-label" for="inativosCli">Inativos</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class='modal-footer'> 
                <button form='relatorio-cli' name="gerar" class='btn btn-success btn-sm'> Gerar </button>
            </div>
        </div>
    </div>
</div>