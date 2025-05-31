<?php
session_start();
include_once '../config/conn.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../login/login.html');
    exit();
}

$email = $_SESSION['email'];

$logado = $_SESSION['email'];
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desenvolvimento Comunitário</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="projeto.css">
</head>

<body>

    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1>Desenvolvimento Comunitário</h1>
            <p class="lead">Empoderando comunidades através de dados.</p>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Comunidades</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#formulario">Inserir Dados</a></li>
                    <li class="nav-item"><a class="nav-link" href="#dashboard">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <!-- Formulário para Inserção de Dados -->
        <section id="formulario" class="container my-5">
            <h2 class="text-center mb-4">Inserir Dados</h2>
            <form class="p-4 border rounded shadow" method="POST" action="inserir_dados.php">
                <div class="mb-3">
                    <label for="comunidade" class="form-label">Comunidade:</label>
                    <input type="text" class="form-control" id="comunidade" name="comunidade" required>
                </div>
                <div class="mb-3">
                    <label for="educacao" class="form-label">Acesso à Educação:</label>
                    <select class="form-select" id="educacao" name="educacao" required>
                        <option value="bom">Bom</option>
                        <option value="moderado">Moderado</option>
                        <option value="ruim">Ruim</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="agua" class="form-label">Acesso à Água:</label>
                    <select class="form-select" id="agua" name="agua" required>
                        <option value="bom">Bom</option>
                        <option value="moderado">Moderado</option>
                        <option value="ruim">Ruim</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="renda" class="form-label">Renda Familiar Média (R$):</label>
                    <input type="number" class="form-control" id="renda" name="renda" required>
                </div>
                <div class="mb-3">
                    <label for="saude" class="form-label">Condições de Saúde:</label>
                    <select class="form-select" id="saude" name="saude" required>
                        <option value="bom">Bom</option>
                        <option value="moderado">Moderado</option>
                        <option value="ruim">Ruim</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="moradia" class="form-label">Condições de Moradia:</label>
                    <select class="form-select" id="moradia" name="moradia" required>
                        <option value="bom">Bom</option>
                        <option value="moderado">Moderado</option>
                        <option value="ruim">Ruim</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="emprego" class="form-label">Taxa de Emprego:</label>
                    <select class="form-select" id="emprego" name="emprego" required>
                        <option value="alto">Alto</option>
                        <option value="moderado">Moderado</option>
                        <option value="baixo">Baixo</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary w-100">Enviar Dados</button>
            </form>
        </section>

        <!-- Dashboard -->
        <section id="dashboard" class="container my-5">
            <h2 class="text-center mb-4">Dashboard</h2>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <canvas id="chart1"></canvas>
                </div>
                <div class="col-lg-6 mb-4">
                    <canvas id="chart2"></canvas>
                </div>
                <div class="col-lg-6 mb-4">
                    <canvas id="chart3"></canvas>
                </div>
                <div class="col-lg-6 mb-4">
                    <canvas id="chart4"></canvas>
                </div>
                <div class="col-lg-6 mb-4">
                    <canvas id="chart5"></canvas>
                </div>
                <div class="col-lg-6 mb-4">
                    <canvas id="chart6"></canvas>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Petrobras - Desenvolvimento Comunitário</p>
    </footer>
    <!-- Custom JS -->
    <script src="script.js"></script>
</body>

</html>