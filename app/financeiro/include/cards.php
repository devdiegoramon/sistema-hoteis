<div class="row pb-3"> 
    <div class="col-lg-3 col-md-6 col-sm-12 mt-3">
        <div class="bg-success shadow text-white card-dash">
            <div class="row line-card p-3">
                <div class="col-4"> 
                    <i style="font-size: 2.5rem;" class="fa-solid fa-piggy-bank"></i>
                    <small class="desc-card"> Recebido </small> <br>
                </div>
                <div class="col-8"> 
                    <span ><?= nomeMes(date("m")) ?></span>
                    <p><span class="value-card"> <i class="fa-solid fa-coins"></i> R$ <?= converteReal($valorFinalMes) ?></span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 mt-3">
        <div class="bg-blue text-white card-dash ">
            <div class="row line-card p-3">
                <div class="col-4"> 
                    <i style="font-size: 2.5rem;" class="fas fa-cash-register"></i>
                    <small class="desc-card"> Previsto </small> <br>
                </div>
                <div class="col-8"> 
                    <span class=""><?= nomeMes(date("m")) ?></span>
                    <p><span class="value-card"> + R$ <?= $valorPreMes ?> </nobr></span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 mt-3">
        <div class="bg-success shadow text-white card-dash">
            <div class="row line-card p-3">
                <div class="col-4"> 
                    <i style="font-size: 2.5rem;" class="fa-solid fa-vault"></i>
                    <small class="desc-card"> Recebido </small> <br>
                </div>
                <div class="col-8"> 
                    <span> Ano de <?= date("Y") ?> </span> <br>
                    <p><span class="value-card"><i class="fa-solid fa-coins"></i> R$ <?= $valorAno ?></nobr></span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 mt-3">
        <div class="bg-blue text-white card-dash">
            <div class="row line-card p-3">
                <div class="col-4"> 
                    <i style="font-size: 2.3rem;" class="fa-solid fa-circle-dollar-to-slot"></i>
                    <small class="desc-card"> Previsto </small> <br>
                </div>
                <div class="col-8"> 
                    <span> Ano de <?= date("Y") ?> </span> <br>
                    <p><span class="value-card">+ R$ <?= $valorPreAno ?></nobr></span></p>
                </div>
            </div>
        </div>
    </div>
</div> 
</div>