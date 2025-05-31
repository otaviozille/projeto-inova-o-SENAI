<?php
include_once '../config/conn.php';

$stmt = $conn->prepare("INSERT INTO comunidades (comunidade, educacao, agua, renda, saude, moradia, emprego) VALUES (?, ?, ?, ?, ?, ?, ?)");

if ($stmt) {
    $stmt->bind_param(
        "sssdsss",
        $_POST['comunidade'],
        $_POST['educacao'],
        $_POST['agua'],
        $_POST['renda'],
        $_POST['saude'],
        $_POST['moradia'],
        $_POST['emprego']
    );

    if ($stmt->execute()) {
        echo "<script>
            alert('Dados inseridos com sucesso!');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Erro na preparação da query: " . $conn->error;
}

$conn->close();
