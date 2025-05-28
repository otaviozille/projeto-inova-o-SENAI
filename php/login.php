<?php
session_start();
if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha_hash'])) {

    include_once '../config/conn.php';

    // Coleta os dados do formulário
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha_hash']);

    // Verifica se o email existe no banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();



    // Verifica se um registro foi encontrado
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verifica se a senha fornecida corresponde à hash armazenada
        if ($senha === $usuario['senha'] or password_verify($senha, $usuario['senha'])) {
            $_SESSION['email'] = $email;
            $_SESSION['senha_hash'] = $senha;
            $_SESSION['nome'] = $usuario['nome'];
            // $_SESSION['nivel_usuario'] = $usuario['nivel_usuario'];
            // $_SESSION['tema'] = $usuario['tema'];

            header("Location: ../projetoPrincipal/index.php");
            exit();
        } else {
            unset($_SESSION['email']);
            unset($_SESSION['senha_hash']);
            echo "<script>alert('Senha incorreta!');</script>";
            header("Location: ../login/login.html");
            exit();
        }
    } else {
        echo "<script>alert('Usuário não encontrado!');</script>";
        header("Location: ../login/login.html");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../login/login.html');
    exit();
}
