<?php
session_start();
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    session_destroy();
    header("Location: views/home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Tela de Login</h2>
        <form method="post" action="controllers/login.php">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Entrar">
            <?php
            if (isset($_SESSION['message'])) {
                echo "<p class='error'>" . $_SESSION['message'] . "</p>";
                unset($_SESSION['message']);
            }
            ?>
        </form>
    </div>
</body>
</html>