<?php
// Configurações de tempo e memória
ini_set('max_execution_time', '300'); // 5 minutos
ini_set('memory_limit', '256M');
set_time_limit(300);

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$mensagem = '';
$registro = null;

if(isset($_GET['row'])) {
    $row = $_GET['row'];
    var_dump($row);
    $inputFileName = 'dados.xlsx';
    $reader = IOFactory::createReader('Xlsx');
    $reader->setReadDataOnly(true);  // Otimização: ler apenas dados
    $spreadsheet = $reader->load($inputFileName);
    $worksheet = $spreadsheet->getActiveSheet();
    
    // Carregar dados do registro
    $registro = [
        'nome' => $worksheet->getCell('A' . $row)->getValue(),
        'dataNascimento' => $worksheet->getCell('B' . $row)->getValue(),
        'numeroPassivo' => $worksheet->getCell('C' . $row)->getValue(),
        'caixa' => $worksheet->getCell('D' . $row)->getValue(),
        'row' => $row
    ];
    
    // Converter a data para o formato HTML
    if (is_numeric($registro['dataNascimento'])) {
        $timestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($registro['dataNascimento']);
        $registro['dataNascimento'] = date('Y-m-d', $timestamp);
    }
}

if(isset($_POST['atualizar'])) {
    $row = $_POST['row'];
    $spreadsheet = IOFactory::load($inputFileName);
    $worksheet = $spreadsheet->getActiveSheet();
    
    // Atualizar dados
    $worksheet->setCellValue('A' . $row, $_POST['nome']);
    $worksheet->setCellValue('B' . $row, $_POST['dataNascimento']);
    $worksheet->setCellValue('C' . $row, $_POST['numeroPassivo']);
    $worksheet->setCellValue('D' . $row, $_POST['caixa']);
    
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($inputFileName);
    
    $mensagem = "Registro atualizado com sucesso!";
    
    // Recarregar dados atualizados
    $registro = [
        'nome' => $_POST['nome'],
        'dataNascimento' => $_POST['dataNascimento'],
        'numeroPassivo' => $_POST['numeroPassivo'],
        'caixa' => $_POST['caixa'],
        'row' => $row
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Registro</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h1>Editar Registro</h1>
        
        <div class="nav-buttons">
            <a href="index.php" class="btn-secondary">Voltar para Consulta</a>
        </div>

        <?php if($mensagem): ?>
            <div class="mensagem-sucesso">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <?php if($registro): ?>
            <div class="form-cadastro">
                <!-- Exibir dados do registro para edição -->
                <h2>Dados do Registro</h2>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($registro['nome']); ?></p>
                <p><strong>Data de Nascimento:</strong> <?php echo $registro['dataNascimento']; ?></p>
                <p><strong>Número do Passivo:</strong> <?php echo htmlspecialchars($registro['numeroPassivo']); ?></p>
                <p><strong>Caixa:</strong> <?php echo htmlspecialchars($registro['caixa']); ?></p>
                <hr>
                <form method="POST">
                    <input type="hidden" name="row" value="<?php echo $registro['row']; ?>">
                    
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" 
                               id="nome" 
                               name="nome" 
                               value="<?php echo htmlspecialchars($registro['nome']); ?>"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="dataNascimento">Data de Nascimento:</label>
                        <input type="date" 
                               id="dataNascimento" 
                               name="dataNascimento" 
                               value="<?php echo $registro['dataNascimento']; ?>"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="numeroPassivo">Número do Passivo:</label>
                        <input type="text" 
                               id="numeroPassivo" 
                               name="numeroPassivo" 
                               value="<?php echo htmlspecialchars($registro['numeroPassivo']); ?>"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="caixa">Caixa:</label>
                        <input type="text" 
                               id="caixa" 
                               name="caixa" 
                               value="<?php echo htmlspecialchars($registro['caixa']); ?>"
                               required>
                    </div>
                    
                    <button type="submit" name="atualizar" class="btn-cadastrar">
                        Atualizar
                    </button>
                </form>
            </div>
        <?php else: ?>
            <p class="no-results">Registro não encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html> 