<?php
require_once '../vendor/autoload.php';
require_once '../config/conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $response = ['success' => false, 'message' => ''];

  try {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception('Email inválido');
    }

    // Verificar se o email existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      throw new Exception('Email não encontrado no sistema');
    }

    // Gerar token único
    $token = bin2hex(random_bytes(32));
    $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Salvar token no banco
    $stmt = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_expira = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expira, $email);

    if (!$stmt->execute()) {
      throw new Exception('Erro ao gerar token de recuperação');
    }

    // Configurar PHPMailer
    $mail = new PHPMailer(true);
    $config = require '../config/mail.php';

    $mail->isSMTP();
    $mail->Host = $config['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['username'];
    $mail->Password = $config['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['port'];
    $mail->CharSet = 'UTF-8';

    // Configurar email
    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($email);
    $mail->isHTML(true);

    $resetLink = "http://{$_SERVER['HTTP_HOST']}/projetoInovacao/redefinir-senha/redefinir-senha.php?token=" . $token;

    $mail->Subject = 'Recuperação de Senha - Petrobras';
    $mail->Body = "
            <h1>Recuperação de Senha</h1>
            <p>Foi solicitada a recuperação de senha para sua conta.</p>
            <p>Clique no link abaixo para redefinir sua senha:</p>
            <p><a href='{$resetLink}'>{$resetLink}</a></p>
            <p>Este link expira em 1 hora.</p>
            <p>Se você não solicitou esta recuperação, ignore este email.</p>
        ";

    $mail->send();
    $response = ['success' => true, 'message' => 'Email de recuperação enviado com sucesso'];
  } catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
  }

  header('Content-Type: application/json');
  echo json_encode($response);
  exit;
}
