<div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <img style='height: 2rem; margin-right: 15px' src="img/cliente.png"> <h5 class="modal-title"> Cadastro de cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-2 py-1" id="formulario-cliente" action="include/gCliente.php" method="POST">
                    <div class="col-md-12">
                        <label class="form-label" for="nome"> Nome completo <small class='text-danger'> * </small> </label>
                        <input class="form-control form-control-sm" required type="text" name="nome" autocomplete="off" placeholder="Ex.: Finn Cleyde" title="Nome do cliente">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="dtnasc">Data de Nascimento <small class='text-danger'> * </small> </label>
                        <input class="form-control form-control-sm date datepicker" required type="text" name="dtnasc" title="Data de Nascimento" autocomplete="off" placeholder="Ex.: 00/00/0000" title="Data de Nascimento do cliente">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="cpf"> Cpf <small class='text-danger'> * </small> </label>
                        <input class="form-control form-control-sm cpf" required type="text" name="cpf" placeholder="Ex.: 000-000-000.00" title="Cpf do cliente">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="telefone"> Telefone - Celular <small class='text-danger'> * </small> </label>
                        <input class="form-control form-control-sm phone" required type="text" name="telefone" placeholder="Ex.: (00) 0000-00000" title="Telefone do cliente">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email"> Email <small class='text-danger'> * </small> </label>
                        <input class="form-control form-control-sm" required type="email" name="email" placeholder="Ex.: finnCleyde@email.com" title="Email do cliente">
                    </div>
                    <div class="col-md-6 ">
                        <label class="form-label" for="estado"> Estado <small class='text-danger'> * </small> </label>
                        <select  class="form-select form-select-sm" required name="estado" id="estado"  title="Estado onde cliente reside"> 

                        </select>
                    </div>
                    <div class="col-md-6 ">
                        <label class="form-label" for="Cidade"> Cidade <small class='text-danger'> * </small> </label>
                        <select  class="form-select form-select-sm" required name="cidade" id="cidade" title="Cidade onde cliente reside"> 
                            <option disabled selected> Selecione um estado</option> 
                        </select>
                    </div>
                </form>
                <div class='mt-2'> 
                    <small class='text-danger'>* Obrigatório </small>
                </div>
            </div>
            <div class="modal-footer"> 
                <button class="btn btn-sm btn-success" type="submit" name="cadastrar" form="formulario-cliente"><i class="fa-regular fa-floppy-disk"></i> Gravar </button>
            </div>
        </div>
    </div>
</div>