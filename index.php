<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="app/assets/bootstrap-5.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>INNJOY</title>
</head>

<body>
    <div class='body py-5'>
        <div class="row container-login shadow-sm">
            <div class="col-md-6 p-4 left">
                <div class='d-flex justify-content-center'>
                    <img style='height:3.5rem;' src="assets/img/PROSYNC TECH.png" alt="">
                </div>
                <h2 class='slogan'> Simplificando a gestão de hotéis e pousadas. </h2>
                <form action="verLogin/verLogin.php" method="POST" class="my-4 py-4 border-top border-bottom">
                    <?php
                    if (isset($_GET['text'])) {
                        if (isset($_GET['type']) and $_GET['type'] == '1') {
                            $text = 'text-danger';
                        } else {
                            $text = 'text-success';
                        }
                        echo "<h5 class='mensagem $text text-center'> " . $_GET['text'] . "</h5>";
                    }
                    ?>
                    <div class="mt-2">
                        <input type="text" name="login" id="login" class="input-login" placeholder="Login">
                        <input type="password" name="senha" id="senha" class="input-login mt-3" placeholder="Senha">
                    </div>

                    <button class='btn btn-secondary w-100 rounded-0 mt-3' name="btn-login"> Acessar </button>
                </form>
                <div>
                    <h2 class='slogan'>
                        Entre em contato <br>
                        <span style="color: #2A5867;"> diegoramonsm@gmail.com | (81) 9 9463-7328</span>
                    </h2>
                </div>

            </div>
            <div class="col-md-6 p-4 right">
                <div class='h-100'>
                    <h1 class="text-inn">Bem-vindo ao Painel Administrativo</h1>
                    <p> Nosso sistema foi desenvolvido para <b>facilitar e simplificar a administração do seu hotel ou pousada.</b><br><br> Com ele, você pode gerenciar reservas, acompanhar check-ins e check-outs, organizar a disponibilidade de quartos, controlar finanças e muito mais, tudo em um só lugar. </p>
                    <p> Otimize seu tempo e foque no que realmente importa: proporcionar uma experiência incrível para seus hóspedes.</p>
                </div>
            </div>
        </div>
    </div>
</body>
<!--bootstrap-->
<script src="app/assets/bootstrap-5.1.3/js/bootstrap.js"></script>
<script src="app/assets/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
<!--jquery-->
<script src="app/assets/js/jquery-3.6.0.min.js"></script>
<script src="https://kit.fontawesome.com/26f2848625.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

</html>