<?php
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header("Location: ../../verLogin/index.php");
    exit();
}

require_once '../../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_SESSION['cliente_id'];
    $tipo_servico = $_POST['tipo_servico'];
    $descricao = $_POST['descricao'];

    try {
        $stmt = $pdo->prepare("INSERT INTO solicitacao_outros_servicos (cliente_id, tipo_servico, descricao, data_solicitacao, status) VALUES (?, ?, ?, NOW(), 'Pendente')");
        $stmt->execute([$cliente_id, $tipo_servico, $descricao]);
        $message = "Solicitação de serviço realizada com sucesso!";
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
    <title>Outros Serviços - Painel do Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Include the same styles as in the previous pages */
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
                <h1>Outros Serviços</h1>
                <p>Solicite serviços adicionais</p>
            </div>
            <div class="card-content">
                <form method="POST">
                    <div class="form-group">
                        <label for="tipo_servico">Tipo de Serviço:</label>
                        <select id="tipo_servico" name="tipo_servico" required>
                            <option value="">Selecione o tipo de serviço</option>
                            <option value="Serviço de Quarto">Serviço de Quarto</option>
                            <option value="Reserva de Restaurante">Reserva de Restaurante</option>
                            <option value="Lavanderia">Lavanderia</option>
                            <option value="Transporte">Transporte</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição do Serviço:</label>
                        <textarea id="descricao" name="descricao" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Solicitar Serviço</button>
                </form>
                <?php if ($message): ?>
                    <div class="message"><?php echo $message; ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

