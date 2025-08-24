<?php
session_start();
require_once './db_connection.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['login_time'])) {
    $session_duration = time() - $_SESSION['login_time'];
    if ($session_duration > 3600) {
        session_destroy();
        header("Location: index.php");
        exit();
    }
}

$result = [];
if (isset($_POST['submit'])) {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $data_nascimento = isset($_POST['data_nascimento']) ? trim($_POST['data_nascimento']) : '';

    $query = "SELECT * FROM alunos WHERE 1=1";
    $params = [];
    
    $conditions = [];
    
    if (!empty($nome)) {
        $conditions[] = "nome LIKE :nome";
        $params[':nome'] = "%$nome%";
    }
    
    if (!empty($data_nascimento)) {
        // Validar formato dd/mm/aaaa
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $data_nascimento, $matches)) {
            $dia = $matches[1];
            $mes = $matches[2];
            $ano = $matches[3];
            
            // Verificar se a data é válida
            if (checkdate($mes, $dia, $ano)) {
                $data_formatada = "$ano-$mes-$dia";
                $conditions[] = "data_nascimento = :data_nascimento";
                $params[':data_nascimento'] = $data_formatada;
            }
        }
    }
    
    if (!empty($conditions)) {
        $query .= " AND (" . implode(" OR ", $conditions) . ")";
    } else {
        // Se não há condições, não retornar nada
        $query .= " AND 1=0";
    }

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Consulta Passivo CEF 411</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.7;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 1;
            }
        }

        .container {
            position: relative;
            z-index: 2;
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            display: flex;
            gap: 2rem;
            flex: 1;
            align-items: flex-start; /* Adicionar esta linha */
        }

        .left-sidebar {
            width: 400px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            min-height: fit-content; /* Adicionar esta linha */
        }

        .search-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.8s ease-out 0.2s both;
            flex-shrink: 0; /* Adicionar esta linha */
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            flex-shrink: 0; /* Adicionar esta linha */
            margin-top: 1rem; /* Adicionar esta linha */
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            animation: slideUp 0.8s ease-out;
        }

        .header h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .main-content {
            display: flex;
            gap: 2rem;
            flex: 1;
        }

        .left-sidebar {
            width: 400px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .search-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.8s ease-out 0.2s both;
            flex: 1;
        }

        .search-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .search-form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-form input {
            padding: 1.2rem 1.5rem;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1.1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: white;
            min-height: 50px;
        }

        .search-form input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .search-form input::placeholder {
            color: #a0aec0;
            font-size: 1rem;
        }

        .search-form button {
            padding: 1.3rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 55px;
        }

        .search-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .search-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: #6c757d;
            text-align: center;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .btn {
            padding: 1.2rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 3% auto;
            padding: 0;
            border-radius: 20px;
            width: 95%;
            max-width: 1200px;
            max-height: 85vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .close {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .close:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 2rem;
            max-height: 60vh;
            overflow-y: auto;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .result-card {
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Cores diferentes para cada resultado */
        .card-color-1 {
            background: linear-gradient(135deg, #e6f3ff 0%, #cce7ff 100%);
            border-color: #4a90e2;
        }

        .card-color-2 {
            background: linear-gradient(135deg, #e6ffe6 0%, #ccffcc 100%);
            border-color: #50c878;
        }

        .card-color-3 {
            background: linear-gradient(135deg, #fff2e6 0%, #ffe6cc 100%);
            border-color: #ff8c42;
        }

        .card-color-4 {
            background: linear-gradient(135deg, #ffe6e6 0%, #ffcccc 100%);
            border-color: #ff6b6b;
        }

        .card-color-5 {
            background: linear-gradient(135deg, #f2e6ff 0%, #e6ccff 100%);
            border-color: #9b59b6;
        }

        .card-color-6 {
            background: linear-gradient(135deg, #e6ffff 0%, #ccffff 100%);
            border-color: #1abc9c;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(0, 0, 0, 0.1);
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .card-actions a {
            color: #667eea;
            font-size: 1.2rem;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .card-actions a:hover {
            background-color: rgba(102, 126, 234, 0.1);
            transform: scale(1.1);
        }

        .result-info {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
        }

        .label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .value {
            font-weight: 500;
            color: #2d3748;
            text-align: right;
        }

        .no-results {
            text-align: center;
            color: #7f8c8d;
            font-size: 1.2rem;
            margin: 2rem 0;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 12px;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Atualizar as media queries existentes */
        @media (max-width: 1024px) {
            .main-content {
                flex-direction: column;
                align-items: center;
            }
            
            .left-sidebar {
                width: 100%;
                max-width: 400px;
                order: 2;
            }
            
            .search-section {
                width: 100%;
                max-width: 500px;
                order: 1;
                margin-bottom: 2rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header h1 {
                font-size: 2rem;
            }
            
            .search-section {
                padding: 2rem 1.5rem;
            }
            
            .btn {
                padding: 1rem 1.5rem;
                font-size: 0.9rem;
            }

            .modal-content {
                width: 95%;
                margin: 2% auto;
                max-height: 90vh;
            }

            .results-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 0.5rem;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .search-section {
                padding: 1.5rem 1rem;
                border-radius: 15px;
            }
            
            .search-form input {
                padding: 1rem;
                font-size: 16px; /* Evita zoom no iOS */
            }
            
            .search-form button {
                padding: 1rem;
                font-size: 16px;
            }
            
            .btn {
                padding: 1rem;
                font-size: 16px;
            }
            
            .modal-content {
                width: 98%;
                margin: 1% auto;
                border-radius: 15px;
            }
            
            .modal-header {
                padding: 1rem 1.5rem;
            }
            
            .modal-body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <div class="header">
            <h1><img src="images/logomarca.png" alt="Logo" style="height: 5.5rem; vertical-align: middle; margin-right: 10px;"> CEF 411 de Samambaia</h1>
        </div>
        
        <div class="main-content">
            <div class="left-sidebar">
                <div class="button-container">
                    <button class="btn" onclick="novoCadastro()">
                        <i class="fas fa-user-plus"></i> Novo Cadastro
                    </button>
                    <button class="btn" onclick="emitirDeclaracaoComparecimento()">
                        <i class="fas fa-file-alt"></i> Emitir Declaração de Comparecimento
                    </button>
                    <button class="btn" onclick="emitirDeclaracaoCodhab()">
                        <i class="fas fa-file-contract"></i> Emitir Declaração Codhab
                    </button>
                    <button class="btn" onclick="emitirDeclaracaoEscolaridade()">
                        <i class="fas fa-graduation-cap"></i> Emitir Declaração de Escolaridade
                    </button>
                    <button class="btn" onclick="emitirDeclaracaoProvisoria()">
                        <i class="fas fa-file-medical"></i> Emitir Declaração Provisória
                    </button>
                </div>
            </div>
            
            <div class="search-section">
                <div class="search-title">
                    <i class="fas fa-search"></i>
                    Buscar Aluno do Passivo
                </div>
                <form method="POST" class="search-form">
                    <div class="form-group">
                        <label for="nome">
                            <i class="fas fa-user"></i>
                            Nome do Aluno:
                        </label>
                        <input type="text" 
                               id="nome"
                               name="nome" 
                               placeholder="Digite o nome do aluno..."
                               autocomplete="off"
                               value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="data_nascimento">
                            <i class="fas fa-calendar-alt"></i>
                            Data de Nascimento:
                        </label>
                        <input type="text" 
                               id="data_nascimento"
                               name="data_nascimento" 
                               placeholder="dd/mm/aaaa"
                               autocomplete="off"
                               maxlength="10"
                               pattern="\d{2}/\d{2}/\d{4}"
                               value="<?php echo isset($_POST['data_nascimento']) ? htmlspecialchars($_POST['data_nascimento']) : ''; ?>">
                    </div>
                    
                    <button type="submit" name="submit">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </form>
                
                <div class="search-info">
                    <i class="fas fa-info-circle"></i>
                    Você pode buscar por nome, data de nascimento ou ambos.
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para resultados da busca -->
    <div id="resultsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-search"></i> Resultados da Busca</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <?php if (!empty($result)): ?>
                    <div class="results-grid">
                        <?php foreach ($result as $key => $row): ?>
                            <div class="result-card card-color-<?php echo ($key % 6) + 1; ?>">
                                <div class="card-header">
                                    <h3><?php echo htmlspecialchars($row['nome']); ?></h3>
                                    <div class="card-actions">
                                        <a href="./form.php?id=<?php echo $row['id']?>" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>   
                                    </div>
                                </div>
                                <div class="result-info">
                                    <div class="info-item">
                                        <span class="label"><i class="fas fa-calendar"></i> Data de Nascimento:</span>
                                        <span class="value">
                                            <?php 
                                            $data_nascimento = new DateTime($row['data_nascimento']);
                                            echo $data_nascimento->format('d/m/Y'); 
                                            ?>
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label"><i class="fas fa-hashtag"></i> Nº Passivo:</span>
                                        <span class="value">
                                            <?php echo number_format($row['numero_passivo'], 0, '', '.'); ?>
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label"><i class="fas fa-box"></i> Caixa:</span>
                                        <span class="value"><?php echo htmlspecialchars($row['caixa']); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif (isset($_POST['submit'])): ?>
                    <div class="no-results">
                        <i class="fas fa-search"></i> Nenhum resultado encontrado.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function novoCadastro() {
            window.open('form.php', '_blank');
        }

        function emitirDeclaracaoComparecimento() {
            window.open('comparecimento.html', '_blank');
        }

        function emitirDeclaracaoCodhab() {
            window.open('codhab.html', '_blank');
        }
        
        function emitirDeclaracaoEscolaridade() {
            window.open('escolaridade.html', '_blank');
        }
        
        function emitirDeclaracaoProvisoria() {
            window.open('deprov.html', '_blank');
        }

        function closeModal() {
            document.getElementById('resultsModal').style.display = 'none';
        }

        // Fechar modal clicando fora dele
        window.onclick = function(event) {
            var modal = document.getElementById('resultsModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Mostrar modal se houver resultados
        <?php if (!empty($result) || (isset($_POST['submit']) && empty($result))): ?>
            document.getElementById('resultsModal').style.display = 'block';
        <?php endif; ?>

        // Máscara para data de nascimento
        document.getElementById('data_nascimento').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            if (value.length >= 5) {
                value = value.substring(0, 5) + '/' + value.substring(5, 9);
            }
            
            e.target.value = value;
        });
    </script>
</body>
</html>