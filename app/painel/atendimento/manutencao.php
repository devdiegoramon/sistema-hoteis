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

// Captura o ID do quarto da URL
$id_quarto = isset($_GET['id_quarto']) ? (int) $_GET['id_quarto'] : 0;

if ($id_quarto == 0) {
    die("ID do quarto inválido.");
}

// Associar o cliente ao quarto (substitua essa lógica conforme necessário)
$cliente_id = 123; // Este valor deve ser obtido de alguma lógica de associação de quarto e cliente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_problema = $_POST['tipo_problema'];
    $descricao = $_POST['descricao'];

    try {
        // Inserir a solicitação com o cliente_id e id_quarto
        $stmt = $pdo->prepare("INSERT INTO solicitacao_manutencao (cliente_id, tipo_problema, descricao, data_solicitacao, status, id_quarto) 
                               VALUES (?, ?, ?, NOW(), 'Pendente', ?)");
        $stmt->execute([$cliente_id, $tipo_problema, $descricao, $id_quarto]);
        $message = "Solicitação de manutenção realizada com sucesso!";
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
    <title>Manutenção - Painel do Cliente</title>
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
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }
        .form-group select, .form-group textarea {
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
                <h1>Manutenção</h1>
                <p>Reporte problemas no seu quarto</p>
            </div>
            <div class="card-content">
                <form method="POST">
                    <div class="form-group">
                        <label for="tipo_problema">Tipo de Problema:</label>
                        <select id="tipo_problema" name="tipo_problema" required>
                            <option value="">Selecione o tipo de problema</option>
                            <option value="Elétrico">Elétrico</option>
                            <option value="Hidráulico">Hidráulico</option>
                            <option value="Mobília">Mobília</option>
                            <option value="Ar Condicionado">Ar Condicionado</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição do Problema:</label>
                        <textarea id="descricao" name="descricao" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Enviar Solicitação</button>
                </form>
                <?php if (isset($message)): ?>
                    <div class="message"><?php echo $message; ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
