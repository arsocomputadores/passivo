<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ./index.php");
    exit();
}

// Session timeout after 1000 seconds
if (isset($_SESSION['login']) && (time() - $_SESSION['login_time']) > 1000) {
    session_destroy();
    header("Location: ./index.php");
    exit();
}

require_once './db_connection.php';
ini_set('max_execution_time', '300');
ini_set('memory_limit', '256M');
set_time_limit(300);

require 'vendor/autoload.php';

// Function to remove accents
function removeAcentos($string) {
    if (!preg_match('/[\x80-\xff]/', $string)) {
        return $string;
    }
    $chars = array(
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A',
        'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E',
        'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I',
        'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O',
        'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U',
        'Ý'=>'Y', 'Ñ'=>'N', 'Ç'=>'C',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a',
        'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e',
        'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i',
        'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o',
        'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u',
        'ý'=>'y', 'ÿ'=>'y', 'ñ'=>'n', 'ç'=>'c'
    );
    return strtr($string, $chars);
}

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

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consultar dados do banco de dados
    $query = "SELECT nome, dataNascimento, numeroPassivo, caixa FROM registros WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $registro = $result->fetch_assoc();
        
        // Debug: Verifique se os dados estão sendo recuperados
        var_dump($registro); // Adicione esta linha para verificar os dados

        // Converter a data para o formato HTML
        if ($registro['dataNascimento']) {
            $registro['dataNascimento'] = date('Y-m-d', strtotime($registro['dataNascimento']));
        }
    } else {
        $mensagem = "Registro não encontrado.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Consulta Passivo CEF 411 de Samambaia</h1>
        
        <form method="POST" class="search-form">
            <input type="text" 
                   name="nome" 
                   placeholder="Digite o nome ou data de nascimento..."
                   autocomplete="off"
                   required>
            <button type="submit" name="submit">
                <i class="fas fa-search"></i> Buscar
            </button>
        </form>
        
        <div class="button-container">
            <div class="emitir-declaracao-section">
                <button class="btn btn-search" onclick="novoCadastro()">
                    Novo Cadastro
                </button>
            </div>
            <div class="emitir-declaracao-section">
                <button class="btn btn-search" onclick="emitirDeclaracaoComparecimento()">
                    Emitir Declaração de Comparecimento
                </button>
            </div>
            <div class="emitir-declaracao-section">
                <button class="btn btn-search" onclick="emitirDeclaracaoCodhab()">
                    Emitir Declaração Codhab (Em construção)
                </button>
            </div>
        </div>
       
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
        <?php elseif (isset($_POST['submit'])): ?>
            <p class="no-results">Nenhum resultado encontrado.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
    </script>
</body>
</html>