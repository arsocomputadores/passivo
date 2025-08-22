<?php
session_start();
require_once './db_connection.php';

if (isset($_POST['submit'])) {
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $data_nascimento = isset($_POST['data_nascimento']) ? $_POST['data_nascimento'] : '';

    $query = "SELECT * FROM alunos WHERE nome LIKE :nome";
    $params = [':nome' => "%$nome%"];

    if (!empty($data_nascimento)) {
        $data_nascimento_formatada = DateTime::createFromFormat('d/m/Y', $data_nascimento);
        if ($data_nascimento_formatada) {
            $query .= " OR data_nascimento = :data_nascimento";
            $params[':data_nascimento'] = $data_nascimento_formatada->format('Y-m-d');
        }
    }

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resultados da Pesquisa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            padding: 20px;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .result-card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-color-1 { background-color: #e6f3ff; }
        .card-color-2 { background-color: #e6ffe6; }
        .card-color-3 { background-color: #fff2e6; }
        .card-color-4 { background-color: #ffe6e6; }
        .card-color-5 { background-color: #f2e6ff; }
        .card-color-6 { background-color: #e6ffff; }

        .card-header h2 {
            margin: 0;
            font-size: 1.2em;
            color: #2c3e50;
        }

        .info-item {
            margin: 10px 0;
        }

        .label {
            font-weight: bold;
            color: #7f8c8d;
        }

        .no-results {
            text-align: center;
            padding: 20px;
            font-size: 1.2em;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <h1>Resultados da Pesquisa</h1>
    
    <?php if (!empty($result)): ?>
        <div class="results-grid">
            <?php foreach ($result as $key => $row): ?>
                <div class="result-card card-color-<?php echo ($key % 6) + 1; ?>">
                    <div class="card-header">
                        <h2><?php echo htmlspecialchars($row['nome']); ?></h2>
                        <div class="card-actions">
                            <span>
                                <a href="./form.php?id=<?php echo $row['id']?>">✎</a>   
                            </span>
                        </div>
                    </div>
                    <div class="result-info">
                        <div class="info-item">
                            <span class="label">Data de Nascimento:</span>
                            <span class="value">
                                <?php 
                                $data_nascimento = new DateTime($row['data_nascimento']);
                                echo $data_nascimento->format('d/m/Y'); 
                                ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="label">Nº Passivo:</span>
                            <span class="value">
                                <?php echo number_format($row['numero_passivo'], 0, '', '.'); ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="label">Caixa:</span>
                            <span class="value"><?php echo htmlspecialchars($row['caixa']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-results">Nenhum resultado encontrado.</p>
    <?php endif; ?>
</body>
</html>