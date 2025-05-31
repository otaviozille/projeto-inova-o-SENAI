<?php
include_once '../config/conn.php';

// Coletar dados do formulário de cadastro
$comunidade = $_POST['comunidade'];
$educacao = $_POST['educacao'];
$agua = $_POST['agua'];
$renda = $_POST['renda'];
$saude = $_POST['saude'];
$moradia = $_POST['moradia'];
$emprego = $_POST['emprego'];

// Inserir dados no banco de dados
$sql = "INSERT INTO comunidades (comunidade, educacao, agua, renda, saude, moradia, emprego) VALUES ('$comunidade', '$educacao', '$agua', '$renda', '$saude', '$moradia', '$emprego')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Cadastro Realizado!!')</script>";
    header("Location: index.php");
    exit();
} else {
    echo "Erro ao cadastrar: " . $conn->error;
}

// Fechar a conexão
$conn->close();
