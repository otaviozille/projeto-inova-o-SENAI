<?php
session_start();
include_once '../config/conn.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../login/login.html');
    exit();
}

$email = $_SESSION['email'];
$logado = $_SESSION['email'];

$ufs = [
    'AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB',
    'PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'
];

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comunidade = $_POST['comunidade'] ?? '';
    $cidade     = $_POST['cidade'] ?? '';
    $estado     = $_POST['estado'] ?? '';
    $educacao   = $_POST['educacao'] ?? '';
    $agua       = $_POST['agua'] ?? '';
    $renda      = $_POST['renda'] ?? '';
    $saude      = $_POST['saude'] ?? '';
    $moradia    = $_POST['moradia'] ?? '';
    $emprego    = $_POST['emprego'] ?? '';

    $stmt = $conn->prepare('INSERT INTO comunidades (comunidade, cidade, estado, educacao, agua, renda, saude, moradia, emprego) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    if ($stmt) {
        $stmt->bind_param('sssssssss', $comunidade, $cidade, $estado, $educacao, $agua, $renda, $saude, $moradia, $emprego);
        if ($stmt->execute()) {
            $mensagem = '<div class="alert alert-success mt-3">Comunidade cadastrada com sucesso!</div>';
        } else {
            $mensagem = '<div class="alert alert-danger mt-3">Erro ao cadastrar: ' . htmlspecialchars($stmt->error) . '</div>';
        }
        $stmt->close();
    } else {
        $mensagem = '<div class="alert alert-danger mt-3">Erro: ' . htmlspecialchars($conn->error) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Desenvolvimento Comunitário</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="projeto.css" />
</head>

<body>
  <div class="wrapper">
    <header class="topo">
      <div class="container cabecalho-topo">
        <img src="../painel-admin/petrobras.png" alt="Logo Petrobras" class="logo-petro" />
        <div class="titulo-topo">
          <h2 class="subtitulo-pequeno">
            DESENVOLVIMENTO COMUNITÁRIO<br />
            <span>Empoderando as comunidades do entorno</span>
          </h2>
        </div>
        <div class="botoes-header">
          <button id="toggle-theme" class="theme-btn" aria-label="Alternar tema">☀️</button>
          <button class="btn-logout">
            <a style="text-decoration: none; color: white;" href="../config/logout.php">Logout</a>
          </button>
        </div>
      </div>
      <!-- Título principal -->
      <h1 class="titulo-pagina">Inserir Dados da Comunidade</h1>
    </header>

    <main class="main-form">
      <section id="formulario" class="container">
        <form class="p-4 border rounded shadow" method="POST" action="">
          <div class="mb-3">
            <label for="comunidade" class="form-label">Nome da Comunidade:</label>
            <input type="text" class="form-control" id="comunidade" name="comunidade" required>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="cidade" class="form-label">Cidade:</label>
              <input type="text" class="form-control" id="cidade" name="cidade" required>
            </div>
            <div class="col-md-6">
              <label class="form-label d-block">Estado (UF):</label>
              <button type="button" class="btn btn-outline-secondary mb-2" id="btn-estado" data-bs-toggle="collapse" data-bs-target="#lista-estados" aria-expanded="false" aria-controls="lista-estados">Selecionar estado</button>
              <div class="estado-grid collapse" id="lista-estados">
                <?php foreach ($ufs as $i => $uf): ?>
                  <input type="radio" class="btn-check" name="estado" id="uf-<?= $uf ?>" value="<?= $uf ?>" <?= $i === 0 ? 'required' : '' ?> autocomplete="off">
                  <label class="btn btn-outline-primary" for="uf-<?= $uf ?>"><?= $uf ?></label>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="educacao" class="form-label">Acesso à Educação:</label>
              <select class="form-select" id="educacao" name="educacao" required>
                <option value="bom">Bom</option>
                <option value="moderado">Moderado</option>
                <option value="ruim">Ruim</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="agua" class="form-label">Acesso à Água:</label>
              <select class="form-select" id="agua" name="agua" required>
                <option value="bom">Bom</option>
                <option value="moderado">Moderado</option>
                <option value="ruim">Ruim</option>
              </select>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="renda" class="form-label">Renda Familiar Média (R$):</label>
              <input type="number" class="form-control" id="renda" name="renda" placeholder="Ex: 2500" required>
            </div>
            <div class="col-md-6">
              <label for="saude" class="form-label">Condições de Saúde:</label>
              <select class="form-select" id="saude" name="saude" required>
                <option value="bom">Bom</option>
                <option value="moderado">Moderado</option>
                <option value="ruim">Ruim</option>
              </select>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label for="moradia" class="form-label">Condições de Moradia:</label>
              <select class="form-select" id="moradia" name="moradia" required>
                <option value="bom">Bom</option>
                <option value="moderado">Moderado</option>
                <option value="ruim">Ruim</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="emprego" class="form-label">Taxa de Emprego:</label>
              <select class="form-select" id="emprego" name="emprego" required>
                <option value="alto">Alto</option>
                <option value="moderado">Moderado</option>
                <option value="baixo">Baixo</option>
              </select>
            </div>
          </div>

          <button type="submit" name="submit" class="btn btn-primary w-100">Enviar Dados</button>
          <?php if ($mensagem) echo $mensagem; ?>
        </form>
      </section>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const btnEstado = document.getElementById('btn-estado');
    const radiosEstado = document.querySelectorAll('#lista-estados input');
    radiosEstado.forEach(radio => {
      radio.addEventListener('change', () => {
        btnEstado.textContent = radio.value;
        const collapse = bootstrap.Collapse.getInstance(document.getElementById('lista-estados'));
        if (collapse) collapse.hide();
      });
    });

    const themeBtn = document.getElementById('toggle-theme');
    const body = document.body;

    function updateTheme(isDark) {
      body.classList.toggle('dark', isDark);
      themeBtn.textContent = isDark ? '🌙' : '☀️';
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    themeBtn.addEventListener('click', () => {
      const isDark = !body.classList.contains('dark');
      updateTheme(isDark);
    });

    updateTheme(localStorage.getItem('theme') === 'dark');
  </script>
</body>
</html>
