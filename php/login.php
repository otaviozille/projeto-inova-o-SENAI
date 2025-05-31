<?php
session_start();
if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    include_once('../config/conn.php');

    $email = trim($_POST['email']);
    $senha = trim($_POST['password']);

    // Verifica se o email existe no banco de dados
    $sql = "SELECT id, nome, email, password FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verifica a senha usando password_verify
        if (password_verify($senha, $usuario['password'])) {
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['nome'] = $usuario['nome'];

            echo "<script>
                alert('Login realizado com sucesso!');
                window.location.href = '../projetoPrincipal/index.php';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('Senha incorreta!');
                window.location.href = '../login/login.html';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('Usuário não encontrado!');
            window.location.href = '../login/login.html';
        </script>";
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../login/login.html');
    exit();
}
