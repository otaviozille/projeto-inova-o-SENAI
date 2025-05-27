<?php
include_once __DIR__ . '/../config/php/conn.php';  // incluir conexão

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

// Insere no banco usando prepared statement
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, cpf, telefone, comunidade, senha) VALUES (?, ?, ?, ?, ?, ?)");
if ($stmt) {
    $stmt->bind_param("ssssss", $nome, $email, $cpf, $telefone, $comunidade, $senhaHash);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar.";
    }

    $stmt->close();
} else {
    echo "Erro na preparação da query: " . $conn->error;
}

$conn->close();
