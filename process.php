<?php
require_once './db_connection.php';

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $dataNascimento = $_POST['dataNascimento'];
        $numeroPassivo = $_POST['numeroPassivo'];
        $caixa = $_POST['caixa'];
        $matricula = isset($_POST['matricula']) ? $_POST['matricula'] : '';
        $id = $_POST['id'];

        if ($id) {
            $stmt = $conn->prepare("UPDATE alunos SET nome = :nome, data_nascimento = :dataNascimento, numero_passivo = :numeroPassivo, caixa = :caixa  WHERE id = :id");
            $stmt->bindParam(':id', $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO alunos (nome, data_nascimento, numero_passivo, caixa, matricula) VALUES (:nome, :dataNascimento, :numeroPassivo, :caixa, :matricula)");
            $stmt->bindParam(':matricula', $matricula);
        }

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':dataNascimento', $dataNascimento);
        $stmt->bindParam(':numeroPassivo', $numeroPassivo);
        $stmt->bindParam(':caixa', $caixa);
        $stmt->execute();

        header("Location: ./home.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}