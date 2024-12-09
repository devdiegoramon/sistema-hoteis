<div class="row mt-2 vendas" id="row-vendas">
    <div class="col-md-6 ">
        <div class="input-daterange input-group" data-provide="datepicker">
            <input type="text" autocomplete="off" class="input-sm form-control form-control-sm date datepicker" placeholder="<?= dataBrasil($primeirodia) ?>" name="start" id="start">
            <span class="input-group-addon range-to" style="margin: 0 5px;">até</span>
            <input type="text" autocomplete="off" class="input-sm form-control form-control-sm date datepicker" placeholder="<?= dataBrasil($ultimodia) ?>" name="end" id="end" data-date-format="dd/mm/yyyy">
            <a class="btn btn-sm btn-primary" onclick="rangedVendas()"> <i class="fa-solid fa-magnifying-glass"></i> Buscar</a>
        </div>
    </div>
</div>
<div class="row mt-2 vendas" id="row-vendas">

    <div class="col-lg-12 col-md-12 col-sm-12 grafico">
        <div class="card mb-3 shadow">
            <div class="card-header">Itens mais vendidos</div>
            <div class="card-body"> 
                <div class="row p-1 pt-2"> 
                    <div class="col-md-6 col-sm-12 pb-4" > 
                        <small> Total:  <b> <span id="valor-quantidade"> </span> </b></small> <br>

                    </div>
                  
                    <div  style="position: relative; height:35vh;"> 
                        <canvas id="chart-vendidos"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-md-12 col-sm-12 grafico">
        <div class="card mb-3 shadow">
            <div class="card-header">Lucro de vendas</div>
            <div class="card-body"> 
                <div class="row p-1 pt-2"> 
                    <div class="col-md-6 col-sm-12 pb-4" > 
                        <small> Total: <span id="valor-grafico"> </span></small> <br>
                    </div>
                    
                    <canvas id="chart-lucroVendas"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7  col-md-12 col-sm-12 grafico">
        <div class="card mb-3 shadow">
            <div class="card-header">Vendas</div>
            <div class="card-body"> 
                <div class="row d-flex justify-content-lg-center justify-content-sm-start"> 

                </div>
                <div class="table-responsive mt-4"> 
                    <table id="datatable-vendas" class="table-vendas table table-secondary table-striped pagination-sm"> 
                        <thead> 
                        <th> Reserva </th>
                        <th> Produto </th>
                        <th> Quantidade </th>
                        <th> Valor final </th>
                        <th> Data </th>
                        <th> Ação </th>
                        </thead>
                        <tbody class="tbody-produtos"> 

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>