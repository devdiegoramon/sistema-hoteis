<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            max-width: 37.5rem; /* 600px */
            width: 100%;
            padding: 1.25rem; /* 20px */
        }
        .logo {
            text-align: center;
            margin-bottom: 1rem; /* Espaço abaixo da logo */
        }
        .logo img {
            width: 10rem; /* 160px */
            height: 10rem; /* 160px */
            background-color: #d1d5db; /* Cinza suave */
            display: block;
            border-radius: 50%; /* Circular */
            color: #6b7280;
            font-size: 1rem; /* 16px */
            text-align: center;
            margin: 0 auto; /* Centraliza horizontalmente */
        }
        .card {
            background-color: #ffffff;
            border-radius: 0.75rem; /* 12px */
            box-shadow: 0 0.25rem 0.375rem rgba(0, 0, 0, 0.1); /* 4px 6px */
            overflow: hidden;
        }
        .card-header {
            background-color: #3b82f6;
            color: #ffffff;
            padding: 1.25rem; /* 20px */
            text-align: center;
        }
        .card-header h1 {
            margin: 0;
            font-size: 1.5rem; /* 24px */
            font-weight: 700;
        }
        .card-header p {
            margin: 0.625rem 0 0; /* 10px */
            font-size: 1rem; /* 16px */
            opacity: 0.9;
        }
        .card-content {
            padding: 1.25rem; /* 20px */
        }
        .service-button {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem; /* 8px */
            padding: 1rem; /* 16px */
            margin-bottom: 0.75rem; /* 12px */
            text-decoration: none;
            color: #1f2937;
            transition: all 0.3s ease;
        }
        .service-button:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }
        .service-icon {
            width: 2rem; /* 32px */
            height: 2rem; /* 32px */
            margin-right: 1rem; /* 16px */
            fill: #3b82f6;
        }
        .service-text {
            flex-grow: 1;
        }
        .service-title {
            font-size: 1.125rem; /* 18px */
            font-weight: 600;
            margin: 0 0 0.25rem; /* 4px */
        }
        .service-description {
            font-size: 0.875rem; /* 14px */
            color: #6b7280;
            margin: 0;
        }
        .chevron-right {
            width: 1.25rem; /* 20px */
            height: 1.25rem; /* 20px */
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
                <h1>Painel do Cliente</h1>
                <p>Bem-vindo ao seu painel de serviços. Como podemos ajudar hoje?</p>
            </div>
            <div class="card-content">
                <?php
                $services = [
                    [
                        'title' => 'Frigobar',
                        'description' => 'Consulte itens e faça pedidos',
                        'icon' => '<path d="M18 8h-1V6c0-1.1-.9-2-2-2H5C3.9 4 3 4.9 3 6v5c0 2.76 2.24 5 5 5h4c2.76 0 5-2.24 5-5v-1h1c1.65 0 3-1.35 3-3s-1.35-3-3-3zm-3 3c0 1.66-1.34 3-3 3H8c-1.66 0-3-1.34-3-3V6h10v5zm3-2h-1V6h1c.55 0 1 .45 1 1s-.45 1-1 1zm-4 8H5c-.55 0-1 .45-1 1s.45 1 1 1h10c.55 0 1-.45 1-1s-.45-1-1-1z"></path>',
                        'link' => 'frigobar/index.php'
                    ],
                    [
                        'title' => 'Atendimento',
                        'description' => 'Solicite ajuda ou serviços',
                        'icon' => '<path d="M12 22c1.1 0 2-0.9 2-2h-4c0 1.1 0.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32v-0.68c0-0.83-0.67-1.5-1.5-1.5s-1.5 0.67-1.5 1.5v0.68c-2.87 0.68-4.5 3.25-4.5 6.32v5l-2 2v1h16v-1l-2-2z"></path>',
                        'link' => 'atendimento/index.php'
                    ]
                ];

                foreach ($services as $service): ?>
                    <a href="<?php echo $service['link']; ?>" class="service-button">
                        <svg class="service-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <?php echo $service['icon']; ?>
                        </svg>
                        <div class="service-text">
                            <h2 class="service-title"><?php echo $service['title']; ?></h2>
                            <p class="service-description"><?php echo $service['description']; ?></p>
                        </div>
                        <svg class="chevron-right" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.707 18.707l6-6c0.391-0.391 0.391-1.024 0-1.414l-6-6c-0.391-0.391-1.024-0.391-1.414 0s-0.391 1.024 0 1.414l5.293 5.293-5.293 5.293c-0.391 0.391-0.391 1.024 0 1.414s1.024 0.391 1.414 0z"></path>
                        </svg>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
