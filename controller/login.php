<?php

require_once '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE nome = :user AND senha = :pass");
    $stmt->bindValue(':user', $user, PDO::PARAM_STR);
    $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        session_start();
        $_SESSION['login'] = true; 
        $_SESSION['login_time'] = time();
        header("Location: ../home.php");
        exit();
    } else {
        session_start();
        $_SESSION['message'] = "Usuário ou senha inválidos.";
        header("Location: ../index.php");
        exit();
    }
}