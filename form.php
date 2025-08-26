<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEF 411 - Cadastro de Dados</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animação de fundo */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx=".5" cy=".5" r=".5"><stop offset="0%" stop-color="%23ffffff" stop-opacity=".1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="283" cy="283" r="283" fill="url(%23a)"/><circle cx="717" cy="717" r="283" fill="url(%23a)"/></svg>') no-repeat center center;
            background-size: cover;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 2rem 2rem;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.8s ease-out;
            margin: 1rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.25);
        }

        .form-icon i {
            font-size: 1.5rem;
            color: white;
        }

        h1 {
            color: #2d3748;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .subtitle {
            color: #718096;
            font-size: 0.85rem;
            margin-bottom: 0;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            background: #f7fafc;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
                align-items: flex-start;
                padding-top: 2rem;
            }
            
            .form-container {
                margin: 0;
                padding: 1.5rem;
                max-width: 100%;
                min-height: auto;
            }
            
            h1 {
                font-size: 1.3rem;
            }
            
            .form-icon {
                width: 50px;
                height: 50px;
            }
            
            .form-icon i {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                margin: 0.5rem;
                padding: 1.5rem 1rem;
            }
            
            h1 {
                font-size: 1.2rem;
            }
            
            .form-icon {
                width: 45px;
                height: 45px;
            }
            
            .form-icon i {
                font-size: 1.1rem;
            }
        }
        @keyframes slideUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilos faltando para o botão voltar */
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            color: #4a5568;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .back-btn i {
            margin-right: 5px;
        }

        /* Estilos faltando para input-group */
        .input-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            color: #4a5568;
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Estilos faltando para input-wrapper */
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 12px;
            color: #a0aec0;
            font-size: 0.9rem;
            z-index: 1;
        }

        /* Melhorar estilos dos inputs */
        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input[type="text"]:hover,
        input[type="date"]:hover,
        input[type="number"]:hover {
            border-color: #cbd5e0;
        }

        /* Melhorar estilo do botão submit */
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn i {
            margin-right: 8px;
        }
    </style>
</head>
<?php
// Verificar se há mensagem de confirmação
$showModal = false;
$modalContent = '';
$modalType = '';

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    $showModal = true;
    
    if ($msg === 'success' && isset($_GET['passivo'])) {
        $passivo = $_GET['passivo'];
        $modalContent = 'Aluno cadastrado com sucesso!<br><strong>Passivo: ' . number_format($passivo, 0, '', '.') . '</strong>';
        $modalType = 'success';
    } elseif ($msg === 'updated') {
        $modalContent = 'Dados atualizados com sucesso!';
        $modalType = 'success';
    } elseif ($msg === 'error' && isset($_GET['details'])) {
        $details = $_GET['details'];
        $modalContent = 'Erro: ' . htmlspecialchars($details);
        $modalType = 'error';
    }
}

if (isset($_GET['id'])) {
    if(isset($_GET['id'])) {
        require_once './db_connection.php';
        $id = $_GET['id'];
    
        $query = "SELECT * FROM alunos WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($result) {
            $registro = $result[0];
            
            if ($registro['data_nascimento']) {
                $registro['data_nascimento'] = date('Y-m-d', strtotime($registro['data_nascimento']));
            }

            $nome = $registro['nome'];
            $dataNascimento = $registro['data_nascimento'];
            $numeroPassivo = $registro['numero_passivo'];
            $caixa = $registro['caixa'];
            $matricula = $registro['matricula'];
            $disabled = 'disabled';
        } else {
            $mensagem = "Registro não encontrado.";
        }
    }
} else {
    // Para novos cadastros, gerar próximo número do passivo
    require_once './db_connection.php';
    $queryMax = "SELECT MAX(CAST(numero_passivo AS UNSIGNED)) as max_passivo FROM alunos WHERE numero_passivo REGEXP '^[0-9]+$'";
    $stmtMax = $conn->prepare($queryMax);
    $stmtMax->execute();
    $result = $stmtMax->fetch(PDO::FETCH_ASSOC);
    $numeroPassivo = max(28912, (int)($result['max_passivo'] ?? 28911) + 1);
}
?>
<body>
    <button type="button" class="back-btn" onclick="fecharPagina()">
        <i class="fas fa-arrow-left"></i> Voltar
    </button>
    
    <div class="form-container">
        <div class="form-header">
            <div class="form-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Cadastro Passivo - CEF 411 Samambaia</h1>
            <p class="subtitle">Sistema de Registro de Alunos</p>
        </div>
        
        <form action="./process.php" method="post">
            <div class="input-group">
                <label for="nome">Nome Completo:</label>
                <div class="input-wrapper">
                    <input type="text" id="nome" name="nome" placeholder="Digite o nome completo" required value="<?php echo isset($nome) ? htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') : ''; ?>">
                    <i class="fas fa-user"></i>
                </div>
            </div>

            <div class="input-group">
                <label for="dataNascimento">Data de Nascimento:</label>
                <div class="input-wrapper">
                    <input type="date" id="dataNascimento" name="dataNascimento" required value="<?php echo isset($dataNascimento) ? htmlspecialchars($dataNascimento, ENT_QUOTES, 'UTF-8') : ''; ?>">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>

            <div class="input-group">
                <label for="numeroPassivo">Número Passivo:</label>
                <div class="input-wrapper">
                    <input type="text" id="numeroPassivo" name="numeroPassivo" placeholder="Número gerado automaticamente" readonly value="<?php echo isset($numeroPassivo) ? htmlspecialchars($numeroPassivo, ENT_QUOTES, 'UTF-8') : ''; ?>" style="background-color: #f5f5f5; cursor: not-allowed;">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>

            <div class="input-group">
                <label for="caixa">Caixa:</label>
                <div class="input-wrapper">
                    <input type="number" id="caixa" name="caixa" placeholder="Digite o número da caixa" required value="<?php echo isset($caixa) ? htmlspecialchars($caixa, ENT_QUOTES, 'UTF-8') : ''; ?>">
                    <i class="fas fa-box"></i>
                </div>
            </div>

            <div class="input-group">
                <label for="matricula">Matrícula/Código:</label>
                <div class="input-wrapper">
                    <input type="text" id="matricula" name="matricula" placeholder="Digite a matrícula" required value="<?php echo isset($matricula) ? $matricula : '' ?>" <?php echo isset($disabled) ? $disabled : ''?>>
                    <i class="fas fa-id-card"></i>
                </div>
            </div>

            <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">

            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Salvar Dados
            </button>
        </form>
    </div>

    <script>
        function fecharPagina() {
            // Tenta fechar a janela/aba
            if (window.opener) {
                window.close();
            } else {
                // Se não conseguir fechar, volta para a página anterior
                window.history.back();
            }
        }
    </script>

    <!-- Modal de Confirmação no Form -->
    <?php if ($showModal): ?>
    <div id="confirmationModal" class="modal-overlay-form">
        <div class="modal-content-form <?php echo $modalType === 'success' ? 'modal-success' : 'modal-error'; ?>">
            <div class="modal-icon-form">
                <?php if ($modalType === 'success'): ?>
                    <i class="fas fa-check-circle" style="color: #28a745;"></i>
                <?php else: ?>
                    <i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i>
                <?php endif; ?>
            </div>
            <div class="modal-message-form">
                <?php echo $modalContent; ?>
            </div>
            <button class="btn-close-form" onclick="closeModalForm()">Fechar</button>
        </div>
    </div>
    <?php endif; ?>

    <style>
    .modal-overlay-form {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        animation: fadeIn 0.3s ease-out;
    }
    
    .modal-content-form {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        text-align: center;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        animation: modalBounceIn 0.3s ease-out;
        position: relative;
    }
    
    .modal-success {
        border-top: 4px solid #28a745;
    }
    
    .modal-error {
        border-top: 4px solid #dc3545;
    }
    
    .modal-icon-form {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .modal-message-form {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        color: #333;
        line-height: 1.5;
    }
    
    .btn-close-form {
        background: #007bff;
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 6px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .btn-close-form:hover {
        background: #0056b3;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes modalBounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3) translateY(-50px);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    @media (max-width: 480px) {
        .modal-content-form {
            padding: 1.5rem;
            margin: 1rem;
        }
        
        .modal-icon-form {
            font-size: 2.5rem;
        }
        
        .modal-message-form {
            font-size: 1rem;
        }
    }
    </style>
    
    <script>
    // Função para fechar o modal
    function closeModalForm() {
        const modal = document.getElementById('confirmationModal');
        if (modal) {
            modal.style.opacity = '0';
            setTimeout(function() {
                modal.style.display = 'none';
                // Limpar a URL
                const url = new URL(window.location);
                url.searchParams.delete('msg');
                url.searchParams.delete('passivo');
                url.searchParams.delete('details');
                window.history.replaceState({}, document.title, url.pathname);
            }, 200);
        }
    }
    
    // Fechar modal ao clicar fora dele
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('confirmationModal');
        if (modal && e.target === modal) {
            closeModalForm();
        }
    });
    </script>
</body>
</html>