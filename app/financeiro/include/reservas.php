<div class="row reservas mt-2">
    <div class='col-md-6'>
        <div class=" input-daterange input-group" data-provide="datepicker">
            <input type="text" autocomplete="off" class="input-sm form-control form-control-sm date datepicker" placeholder="<?= dataBrasil($primeirodia) ?>" name="start-reservas" id="start-reservas">
            <span class="input-group-addon range-to" style="margin: 0 5px;">até</span>
            <input type="text" autocomplete="off" class="input-sm form-control form-control-sm date datepicker" placeholder="<?= dataBrasil($ultimodia) ?>" name="end-reservas" id="end-reservas" data-date-format="dd/mm/yyyy">
            <a class="btn btn-sm btn-primary" onclick="rangedReservas()"> <i class="fa-solid fa-magnifying-glass"></i> Buscar</a>
        </div>
    </div>
</div> 
<div class="row reservas mt-2">
    <div class="col-lg-12 col-md-12 col-sm-12 grafico">
        <div class="card mb-3 shadow">
            <div class="card-header">Lucro de reservas por acomodações</div>
            <div class="card-body"> 
                <div class="row p-1 pt-2"> 
                    <div class="col-md-6 col-sm-12 pb-4" > 
                        <small> Total: <span id="valor-graficoRes"> </span></small> <br>
                    </div>
                  
                    <div  style="position: relative; height:35vh;"> 
                        <canvas id='chart-lucroReservas'> </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12  col-md-12 col-sm-12 grafico">
        <div class="card mb-3 shadow">
            <div class="card-header">Reservas</div>
            <div class="card-body"> 
                <div class="row d-flex justify-content-lg-center justify-content-sm-start"> 

                </div>
                <div class="table-responsive mt-4"> 
                    <table id="datatable" class="table-vendas table table-secondary table-striped pagination-sm"> 
                        <thead> 
                            <tr>
                                <th> Reserva </th>
                                <th> Acomodação </th>
                                <th> Cliente </th>
                                <th> Ranged </th>
                                <th> Situação </th>
                                <th> Valor final </th>
                                <th> Ação </th>
                            </tr>
                        </thead>
                        <tbody class="tbody-reservas"> 

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>