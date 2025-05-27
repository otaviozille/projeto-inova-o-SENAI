<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "comunidades";

// Criar conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Coletar dados do formulário de cadastro
$comunidade = $_POST['comunidade'];
$educacao = $_POST['educacao'];
$agua = $_POST['agua'];
$renda = $_POST['renda'];
$saude = $_POST['saude'];
$moradia = $_POST['moradia'];
$emprego = $_POST['emprego'];

// Inserir dados no banco de dados
$sql = "INSERT INTO dados (comunidade, educacao, agua, renda, saude, moradia, emprego) VALUES ('$comunidade', '$educacao', '$agua', '$renda', '$saude', '$moradia', '$emprego')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Cadastro Realizado!!')</script>";
    header("Location: projeto.html");
    exit();
} else {
    echo "Erro ao cadastrar: " . $conn->error;
}

// Fechar a conexão
$conn->close();
?>