<?php
include_once '../config/conn.php';

$comunidades = [];
$sql = "SELECT id, comunidade FROM comunidades";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $comunidades[] = $row;
  }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Sistema de Cadastro Petrobras">
  <title>Cadastro Petrobras</title>
  <link rel="stylesheet" href="cadastro.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="icon" href="../imgs/fav.icon.png" type="image/x-icon">
</head>

<body>
  <!-- Botão de alternância de tema -->
  <button id="darkModeToggle" class="theme-toggle" aria-label="Alternar tema">☀️</button>

  <main class="container">
    <div class="auth-card">
      <div class="brand-header">
        <h1 class="brand-title">Cadastro Petrobras</h1>
      </div>

      <form id="cadastroForm" class="auth-form" method="POST" action="../php/cadastro.php">
        <div class="form-group">
          <label for="nome">Nome completo</label>
          <input type="text" id="nome" name="nome" required placeholder="Ex: João da Silva" />
        </div>

        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" required placeholder="exemplo@email.com" />
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf" required data-mask="000.000.000-00" placeholder="000.000.000-00" />
          </div>

          <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="tel" id="telefone" name="telefone" required data-mask="(00) 00000-0000" placeholder="(00) 00000-0000" />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" required minlength="6" placeholder="Mínimo 6 caracteres" />
          </div>

          <div class="form-group">
            <label for="confirm-password">Confirmação de senha</label>
            <input type="password" id="confirm-password" name="confirm-password" required placeholder="Repita sua senha" />
          </div>
        </div>

        <div class="form-group">
          <label for="comunidade">Comunidade</label>
          <select id="comunidade" name="comunidade" required>
            <option value="">Selecione sua comunidade</option>
            <?php foreach ($comunidades as $comunidade): ?>
              <option value="<?= htmlspecialchars($comunidade['id']) ?>">
                <?= htmlspecialchars($comunidade['comunidade']) ?>
              </option>
            <?php endforeach; ?>
            <option value="outra">Outra (não encontrada)</option>
          </select>
          <small class="form-hint">Caso sua comunidade não esteja listada, selecione "Outra" e cadastre no painel depois.</small>
        </div>

        <div class="form-actions">
          <button type="submit" name="submit" class="btn-primary">Criar conta</button>
          <div class="auth-links">
            <a href="../login/login.html" class="text-link">Já tem conta? Faça login</a>
          </div>
        </div>
      </form>
    </div>
  </main>

  <script src="https://unpkg.com/imask"></script>
  <script src="cadastro.js" defer></script>
</body>

</html>