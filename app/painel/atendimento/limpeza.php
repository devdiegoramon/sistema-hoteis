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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $observacoes = $_POST['observacoes'];

    try {
        $stmt = $pdo->prepare("INSERT INTO solicitacao_limpeza (cliente_id, observacoes, data_solicitacao, status) VALUES (?, ?, NOW(), 'Pendente')");
        $stmt->execute([$observacoes]);
        $message = "Solicitação de limpeza realizada com sucesso!";
    } catch (PDOException $e) {
        $message = "Erro ao processar a solicitação: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Limpeza de Quarto - Painel do Cliente</title>
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
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }
        .form-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.25rem;
        }
        .btn-submit {
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .message {
            margin-top: 1rem;
            padding: 0.5rem;
            background-color: #e5e7eb;
            border-radius: 0.25rem;
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
                <h1>Limpeza de Quarto</h1>
                <p>Solicite a limpeza do seu quarto</p>
            </div>
            <div class="card-content">
                <form method="POST">
                    <div class="form-group">
                        <label for="observacoes">Observações (opcional):</label>
                        <textarea id="observacoes" name="observacoes" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Solicitar Limpeza</button>
                </form>
               
            </div>
        </div>
    </div>
</body>
</html>

