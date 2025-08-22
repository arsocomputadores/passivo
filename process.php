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
        } else {
            // Novo cadastro - gerar número do passivo automaticamente
            // Usar transação para garantir numeração consecutiva
            $conn->beginTransaction();
            
            try {
                // Buscar o maior número do passivo existente (removendo pontos para comparação)
                $queryMax = "SELECT MAX(CAST(REPLACE(numero_passivo, '.', '') AS UNSIGNED)) as max_passivo FROM alunos WHERE REPLACE(numero_passivo, '.', '') REGEXP '^[0-9]+$'";
                $stmtMax = $conn->prepare($queryMax);
                $stmtMax->execute();
                $result = $stmtMax->fetch(PDO::FETCH_ASSOC);
                
                // Definir próximo número do passivo (mínimo 28908)
                $proximoPassivoNumero = max(28908, ($result['max_passivo'] ?? 28907) + 1);
                
                // Formatar o número com ponto (ex: 28.909)
                $proximoPassivoFormatado = number_format($proximoPassivoNumero, 0, '', '.');
                
                $stmt = $conn->prepare("INSERT INTO alunos (nome, data_nascimento, numero_passivo, caixa, matricula) VALUES (:nome, :dataNascimento, :numeroPassivo, :caixa, :matricula)");
                $stmt->bindParam(':numeroPassivo', $proximoPassivoFormatado);
                $stmt->bindParam(':matricula', $matricula);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':dataNascimento', $dataNascimento);
                $stmt->bindParam(':caixa', $caixa);
                $stmt->execute();
                
                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            }
        }

        if ($id) {
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':dataNascimento', $dataNascimento);
            $stmt->bindParam(':caixa', $caixa);
            $stmt->execute();
        }

        header("Location: ./home.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}