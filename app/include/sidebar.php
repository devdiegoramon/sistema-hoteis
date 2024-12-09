<header class="header body-pd" id="header">
    <div class="header_toggle"> <i class='bx bx-menu bx-x' id="header-toggle"></i> </div>
    <div>
        <a class="btn btn-dark-blue btn-sm" href="<?= BASED ?>/config/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Sair</i></span> </a> 
    </div>
</header>
<?php
$sqlLogo = "select logonome, 
                   logoserver
            from logo";
$resultLogo = mysqli_query($con, $sqlLogo);
?>
<div class="l-navbar show-nav" id="nav-bar">
    <nav class="nav width-nav" id='conteudo-nav'>
        <div> 
            <?php
            if (mysqli_num_rows($resultLogo) > 0) {
                $logo = mysqli_fetch_array($resultLogo)[0];
                ?>
                <a style="margin-bottom: -15px ;" class="nav_logo mt-2"> <img  style="max-width:150px;" id="nav_logo" src="<?= BASED ?>/hospedagem/arquivos/logo/<?= $logo ?>" width="width" height="height" alt="logo da pousada"/> </a>
            <?php } ?>
            <a href="<?= BASED ?>/home" style="margin-top:15px;" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Home</span> </a> 
            <a href="<?= BASED ?>/cliente/" class="nav_link"> <i class='bx bx-user nav_icon'></i> <span class="nav_name">Clientes</span> </a> 
            <a href="<?= BASED ?>/estacionamento/" class="nav_link"> <i class='bx bx-car nav_icon'></i> <span class="nav_name">Estacionamento</span>  </a> 
            <a href="<?= BASED ?>/acomodacao/" class="nav_link"> <i class='bx bx-hotel nav_icon'></i> <span class="nav_name">Acomodações</span> </a> 
            <a href="<?= BASED ?>/estoque/" class="nav_link"> <i class='bx bx-cabinet nav_icon'></i> <span class="nav_name">Estoque</span> </a> 
            <a href="<?= BASED ?>/frigobar/" class="nav_link"> <i class='bx bx-fridge'></i> <span class="nav_name">Frigobar</span> </a> 
            <a href="<?= BASED ?>/reserva/" class="nav_link"> <i class='bx bx-calendar-event nav_icon'></i> <span class="nav_name">Reservas</span> </a> 
            <?php if ($_SESSION['nivel'] == 1) { ?>
                <a href="<?= BASED ?>/financeiro/" class="nav_link"> <i class='bx bx-money-withdraw nav_icon'></i> <span class="nav_name">Financeiro</span> </a> 
                <a href="<?= BASED ?>/pagamentos/" class="nav_link"> <i class='bx bxs-credit-card-alt nav_icon'></i><span class="nav_name">Pagamentos</span> </a> 
                <a href="<?= BASED ?>/hospedagem/" class="nav_link"> <i class="bx bxs-home nav_icon"></i><span class="nav_name">Pousada / Hotel</span> </a> 
                <a href="<?= BASED ?>/admin/" class="nav_link"> <i class='bx bxs-lock-open nav_icon'></i> <span class="nav_name">Administrador</span> </a> 
            <?php } ?>
<!--<a href="<?= BASED ?>/relatorios/" class="nav_link"> <i class='bx bx-file nav_icon'></i> <span class="nav_name">Relatórios</span> </a>--> 
        </div>
    </nav>
    <div class="sair nav"> 
    </div> 
</div>