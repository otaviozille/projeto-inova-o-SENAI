<?php
require_once '../config/conn.php';

$token = htmlspecialchars(trim($_GET['token'] ?? ''));
if (empty($token)) {
  header('Location: ../login/login.html');
  exit;
}

// Verificar se o token existe e não expirou
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE reset_token = ? AND reset_expira > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  header('Location: ../login/login.html?error=token_invalido');
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Redefinir Senha - Petrobras</title>
  <link rel="stylesheet" href="../login/login.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
    rel="stylesheet" />
    <link rel="icon" href="../imgs/fav.icon.png" type="image/x-icon">
</head>

<body>
  <button id="toggle-theme" class="theme-btn" aria-label="Alternar tema">
    ☀️
  </button>

  <div class="login-container">
    <div class="login-box">
      <h2>Redefinir Senha</h2>

      <form
        id="redefinirSenhaForm"
        action="../php/processar-redefinir-senha.php"
        method="POST">
        <input
          type="hidden"
          name="token"
          value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>" />

        <div class="input-group">
          <label for="password">Nova Senha</label>
          <div class="password-wrapper">
            <input type="password" id="password" name="password" required>
            <i class="fas fa-eye toggle-password"></i>
          </div>
          <small class="error-message"></small>
        </div>

        <div class="input-group">
          <label for="confirm-password">Confirme a Nova Senha</label>
          <div class="password-wrapper">
            <input
              type="password"
              id="confirm-password"
              name="confirm-password"
              required>
            <i class="fas fa-eye toggle-password"></i>
          </div>
          <small class="error-message"></small>
        </div>

        <button type="submit" class="login-btn">Redefinir Senha</button>
      </form>
    </div>
  </div>

  <script src="redefinir-senha.js"></script>
</body>

</html>
