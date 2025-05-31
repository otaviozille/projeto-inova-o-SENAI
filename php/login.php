<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    include_once('../config/conn.php');

    $email = trim($_POST['email']);
    $senha = trim($_POST['password']);

    $sql = "SELECT id, nome, email, password, nivel_usuario FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha, $usuario['password'])) {
            session_regenerate_id(true);
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['nivel_usuario'] = $usuario['nivel_usuario'];

            switch ($usuario['nivel_usuario']) {
                case 'Admin':
                    header("Location: ../painel-admin/painel.php");
                    break;
                case 'Morador':
                    header("Location: ../page-user/index.php");
                    break;
                default:
                    header("Location: ../login/login.html");
                    break;
            }
            exit();
        } else {
            header("Location: ../login/login.html?erro=senha_incorreta");
            exit();
        }
    } else {
        header("Location: ../login/login.html?erro=usuario_nao_encontrado");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: ../login/login.html?erro=campos_vazios');
    exit();
}

