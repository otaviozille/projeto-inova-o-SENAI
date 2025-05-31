<?php
session_start();
include_once '../config/conn.php';

if (!isset($conn)) {
    die('Erro: conexão $conn não definida.');
}

// Recebe dados do formulário
$nome       = $_POST['nome'] ?? '';
$email      = $_POST['email'] ?? '';
$cpf        = $_POST['cpf'] ?? '';
$telefone   = $_POST['telefone'] ?? '';
$comunidade = $_POST['comunidade'] ?? '';
$senha      = $_POST['password'] ?? '';

// Hash da senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Converte comunidade para inteiro
$comunidade = (int)$comunidade;

// Insere no banco usando prepared statement
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, cpf, telefone, comunidade_id, password) VALUES (?, ?, ?, ?, ?, ?)");
if ($stmt) {
    $stmt->bind_param("ssssis", $nome, $email, $cpf, $telefone, $comunidade, $senhaHash);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>
        alert('Cadastro realizado com sucesso!');
        window.location.href = '../login/login.html';
        </script>";
        exit();
    } else {
        header("Location: ../cadastro/cadastro.html?error=erro_cadastro");
        exit();
    }

    $stmt->close();
} else {
    echo "Erro na preparação da query: " . $conn->error;
}

$conn->close();
