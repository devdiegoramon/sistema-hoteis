<?php
session_start();
$config = [
    'host' => 'localhost',
    'user' => 'admin',
    'pass' => '',
    'db'   => 'sistema_hoteis_prosync'
];

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['db']};charset=utf8", $config['user'], $config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atendimento - Painel do Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 37.5rem;
            width: 100%;
            padding: 1.25rem;
        }
        .logo {
            text-align: center;
            margin-bottom: 1rem;
        }
        .logo img {
            width: 10rem;
            height: 10rem;
            background-color: #d1d5db;
            display: block;
            border-radius: 50%;
            color: #6b7280;
            font-size: 1rem;
            text-align: center;
            margin: 0 auto;
        }
        .card {
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.375rem rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #3b82f6;
            color: #ffffff;
            padding: 1.25rem;
            text-align: center;
        }
        .card-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .card-header p {
            margin: 0.625rem 0 0;
            font-size: 1rem;
            opacity: 0.9;
        }
        .card-content {
            padding: 1.25rem;
        }
        .service-button {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            text-decoration: none;
            color: #1f2937;
            transition: all 0.3s ease;
        }
        .service-button:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }
        .service-icon {
            width: 2rem;
            height: 2rem;
            margin-right: 1rem;
            fill: #3b82f6;
        }
        .service-text {
            flex-grow: 1;
        }
        .service-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0 0 0.25rem;
        }
        .service-description {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }
        .chevron-right {
            width: 1.25rem;
            height: 1.25rem;
            fill: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 160 160'%3E%3Ccircle cx='80' cy='80' r='80' fill='%23d1d5db'/%3E%3Ctext x='50%' y='50%' font-family='Arial, sans-serif' font-size='14' fill='%236b7280' text-anchor='middle' alignment-baseline='middle'%3ELOGO%3C/text%3E%3C/svg%3E" alt="Logo">
        </div>
        <div class="card">
            <div class="card-header">
                <h1>Atendimento</h1>
                <p>Solicite serviços para sua hospedagem</p>
            </div>
            <div class="card-content">
                <a href="limpeza.php" class="service-button">
                    <svg class="service-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 14V20C19 20.5523 18.5523 21 18 21H6C5.44772 21 5 20.5523 5 20V14H3V12L5 7H19L21 12V14H19ZM7 14V19H17V14H7ZM6 5V3H18V5H6ZM7 16H9V18H7V16ZM11 16H13V18H11V16ZM15 16H17V18H15V16Z"></path></svg>
                    <div class="service-text">
                        <h2 class="service-title">Limpeza do Quarto</h2>
                        <p class="service-description">Solicite a limpeza do seu quarto</p>
                    </div>
                    <svg class="chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13.1714 12.0007L8.22168 7.05093L9.63589 5.63672L15.9999 12.0007L9.63589 18.3646L8.22168 16.9504L13.1714 12.0007Z"></path></svg>
                </a>
                <a href="servico_quarto.php" class="service-button">
                    <svg class="service-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.9999 2.45703C18.9499 3.33703 20.6099 6.39703 20.1299 9.45703L17.9999 9.04703C18.3599 6.76703 17.1599 4.54703 14.9999 3.86703V8.99703L6.99989 4.99703V3.86703C4.83989 4.54703 3.63989 6.76703 3.99989 9.04703L1.86989 9.45703C1.38989 6.39703 3.04989 3.33703 5.99989 2.45703C7.47989 1.99703 9.52989 1.99703 10.9999 2.45703V2.99703L15.9999 5.99703V2.45703ZM18.9999 13.0001C18.9999 16.8661 15.8659 20.0001 11.9999 20.0001C8.13389 20.0001 4.99989 16.8661 4.99989 13.0001C4.99989 9.13407 8.13389 6.00007 11.9999 6.00007C15.8659 6.00007 18.9999 9.13407 18.9999 13.0001ZM16.9999 13.0001C16.9999 10.2391 14.7609 8.00007 11.9999 8.00007C9.23889 8.00007 6.99989 10.2391 6.99989 13.0001C6.99989 15.7611 9.23889 18.0001 11.9999 18.0001C14.7609 18.0001 16.9999 15.7611 16.9999 13.0001ZM12.9999 13.0001V9.00007C11.3429 9.00007 9.99989 10.3431 9.99989 12.0001C9.99989 13.6571 11.3429 15.0001 12.9999 15.0001C13.5569 15.0001 14.0739 14.8241 14.5049 14.5181L12.9999 13.0001Z"></path></svg>
                    <div class="service-text">
                        <h2 class="service-title">Serviço de Quarto</h2>
                        <p class="service-description">Faça seu pedido de comida e bebida</p>
                    </div>
                    <svg class="chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13.1714 12.0007L8.22168 7.05093L9.63589 5.63672L15.9999 12.0007L9.63589 18.3646L8.22168 16.9504L13.1714 12.0007Z"></path></svg>
                </a>
                <a href="manutencao.php" class="service-button">
                    <svg class="service-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M14.7 6.29998C14.3 5.89998 13.7 5.89998 13.3 6.29998L12 7.59998L10.7 6.29998C10.3 5.89998 9.7 5.89998 9.3 6.29998C8.9 6.69998 8.9 7.29998 9.3 7.69998L11.3 9.69998C11.5 9.89998 11.7 9.99998 12 9.99998C12.3 9.99998 12.5 9.89998 12.7 9.69998L14.7 7.69998C15.1 7.29998 15.1 6.69998 14.7 6.29998ZM5 16.9999C5 16.4477 5.44772 15.9999 6 15.9999H18C18.5523 15.9999 19 16.4477 19 16.9999C19 17.5522 18.5523 17.9999 18 17.9999H6C5.44772 17.9999 5 17.5522 5 16.9999ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20Z"></path></svg>
                    <div class="service-text">
                        <h2 class="service-title">Manutenção</h2>
                        <p class="service-description">Reporte problemas no seu quarto</p>
                    </div>
                    <svg class="chevron-right" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13.1714 12.0007L8.22168 7.05093L9.63589 5.63672L15.9999 12.0007L9.63589 18.3646L8.22168 16.9504L13.1714 12.0007Z"></path></svg>
                </a>
            </div>
        </div>
    </div>
</body>
</html>

