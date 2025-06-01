<?php
require_once '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = htmlspecialchars(trim($_POST['token'] ?? ''));
  $password = htmlspecialchars(trim($_POST['password'] ?? ''));
  $response = ['success' => false, 'message' => ''];

  try {
    if (empty($token) || empty($password)) {
      throw new Exception('Dados inválidos');
    }

    if (strlen($password) < 6) {
      throw new Exception('A senha deve ter no mínimo 6 caracteres');
    }

    // Verificar se o token é válido e não expirou
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE reset_token = ? AND reset_expira > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      throw new Exception('Token inválido ou expirado');
    }

    // Hash da nova senha
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Atualizar senha e limpar token
    $stmt = $conn->prepare("UPDATE usuarios SET password = ?, reset_token = NULL, reset_expira = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $hash, $token);

    if (!$stmt->execute()) {
      throw new Exception('Erro ao atualizar senha');
    }

    header('Location: ../login/login.html?success=senha_atualizada');
    exit;
  } catch (Exception $e) {
    header('Location: ../redefinir-senha/redefinir-senha.html?error=' . urlencode($e->getMessage()));
    exit;
  }
}

// Verificar token na URL
$token = htmlspecialchars(trim($_GET['token'] ?? ''));
if (empty($token)) {
  header('Location: ../login/login.html');
  exit;
}
