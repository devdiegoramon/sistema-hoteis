<div class="modal" id="modalPagamentos" tabindex="-1" aria-labelledby="modalPagamentos" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Formas de pagamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="resposta">  </div>
                <form class="col-md-12 g-2 formulario-tipo" autocomplete='off' id="formulario_forma" method="POST">
                    <div class="input-group">
                        <input class="form-control form-control-sm mr-3" type="text" name="nome" id="nome_forma" placeholder="Ex.: Dinheiro">
                        <button class="btn btn-sm btn-success accordion" type="button" name="cadastrar" id="cadastrar_formaPagamento"> Cadastrar </button>
                    </div>
                </form>
                <table class="table">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Nome </th>
                            <th> Status </th>
                            <th> Editar </th>
                        </tr>
                    </thead>
                    <tbody id="tbody_formaPagamento">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>