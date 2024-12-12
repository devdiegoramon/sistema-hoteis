<?php
// painel/index.php
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Resetando margens e padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            width: 100%;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        header p {
            font-size: 1.1em;
        }

        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }

        section {
            text-align: center;
            width: 100%;
        }

        section h2 {
            font-size: 1.6em;
            margin-bottom: 20px;
        }

        .group-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        /* Estilo dos botões */
        button {
            background-color: #007bff;
            color: white;
            padding: 15px;
            font-size: 1.2em;
            margin: 10px 0;
            width: 100%;
            max-width: 300px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Responsividade para mobile-first */
        @media (min-width: 1024px) {
            header {
                padding: 30px;
            }

            header h1 {
                font-size: 2.2em;
            }

            section h2 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Painel do Cliente</h1>
        <p>Bem-vindo ao seu painel de serviços. Selecione o serviço desejado.</p>
    </header>

    <main>
        <section>
            <h2>Serviços Disponíveis</h2>

            <div class="group-button">
                <button onclick="window.location.href='frigobar/index.php'">Acessar Frigobar</button>
                <button onclick="window.location.href='estacionamento/index.php'">Consultar Estacionamento</button>
                <button onclick="window.location.href='financeiro/index.php'">Ver Pagamentos</button>
                <button onclick="window.location.href='atendimento/index.php'">Chamar Atendimento</button>
            </div>
        </section>
    </main>
</body>
</html>