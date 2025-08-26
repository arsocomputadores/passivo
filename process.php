<?php
require_once './db_connection.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $dataNascimento = $_POST['dataNascimento'];
        $caixa = $_POST['caixa'];
        $matricula = $_POST['matricula'];
        $id = $_POST['id'];

        if ($id) {
            // Atualização - não modifica o número do passivo
            $stmt = $conn->prepare("UPDATE alunos SET nome = :nome, data_nascimento = :dataNascimento, caixa = :caixa, matricula = :matricula WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':matricula', $matricula);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':dataNascimento', $dataNascimento);
            $stmt->bindParam(':caixa', $caixa);
            $stmt->execute();
            
            // Redirecionar com mensagem de sucesso para atualização
            header("Location: ./form.php?msg=updated");
            exit();
        } else {
            // Novo cadastro - gerar número do passivo automaticamente
            // Usar transação para garantir numeração consecutiva
            $conn->beginTransaction();
            
            try {
                // Buscar o maior número do passivo existente
                $queryMax = "SELECT MAX(numero_passivo) as max_passivo FROM alunos WHERE numero_passivo REGEXP '^[0-9]+$'";
                $stmtMax = $conn->prepare($queryMax);
                $stmtMax->execute();
                $result = $stmtMax->fetch(PDO::FETCH_ASSOC);
                
                // Definir próximo número do passivo (mínimo 28912)
                $proximoPassivoNumero = max(28912, (int)($result['max_passivo'] ?? 28911) + 1);
                
                // Inserir novo registro com número do passivo como inteiro
                $stmt = $conn->prepare("INSERT INTO alunos (nome, data_nascimento, numero_passivo, caixa, matricula) VALUES (:nome, :dataNascimento, :numeroPassivo, :caixa, :matricula)");
                $stmt->bindParam(':numeroPassivo', $proximoPassivoNumero);
                $stmt->bindParam(':matricula', $matricula);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':dataNascimento', $dataNascimento);
                $stmt->bindParam(':caixa', $caixa);
                $stmt->execute();
                
                $conn->commit();
                
                // Redirecionar com mensagem de sucesso para novo cadastro
                // Sucesso no cadastro
                header("Location: ./form.php?msg=success&passivo=" . $proximoPassivoNumero);
                exit();
                
                // Sucesso na atualização
                header("Location: ./form.php?msg=updated");
                exit();
                
                // Erro
                header("Location: ./form.php?msg=error&details=" . urlencode($e->getMessage()));
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
        }
    }
} catch (PDOException $e) {
    // Redirecionar com mensagem de erro
    header("Location: ./home.php?msg=error&details=" . urlencode($e->getMessage()));
    exit();
}