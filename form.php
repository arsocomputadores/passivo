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
            padding: 3rem 2.5rem;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.8s ease-out;
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

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .form-icon i {
            font-size: 2rem;
            color: white;
        }

        h1 {
            color: #2d3748;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #718096;
            font-size: 0.95rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .input-group {
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4a5568;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            transition: color 0.3s ease;
            z-index: 2;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 16px 16px 16px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            background: #f7fafc;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus {
            border-color: #667eea;
            outline: none;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        input[type="text"]:focus + i,
        input[type="date"]:focus + i,
        input[type="number"]:focus + i {
            color: #667eea;
        }

        input:disabled {
            background: #e2e8f0;
            color: #718096;
            cursor: not-allowed;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            border: 2px solid #667eea;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            z-index: 1000;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background: #667eea;
            color: white;
            transform: scale(1.05);
        }

        @media (max-width: 480px) {
            .form-container {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .form-icon {
                width: 60px;
                height: 60px;
            }
            
            .form-icon i {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<?php
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
    $numeroPassivo = max(28908, ($result['max_passivo'] ?? 28907) + 1);
}
?>
<body>
    <a href="home.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
    
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
        // Efeito de foco nos inputs
        const inputs = document.querySelectorAll('input[type="text"], input[type="date"], input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Animação de entrada
        window.addEventListener('load', function() {
            document.querySelector('.form-container').style.animation = 'slideUp 0.8s ease-out';
        });
    </script>
</body>
</html>