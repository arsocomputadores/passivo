<?php
// Aumentar limite de memória
ini_set('memory_limit', '256M');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    try {
        $inputFileName = 'dados.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow() + 1;

        // Validação dos campos
        if (empty($_POST['nome']) || empty($_POST['dataNascimento']) || empty($_POST['numeroPassivo']) || empty($_POST['caixa'])) {
            throw new Exception("Todos os campos são obrigatórios!");
        }

        // Formatar a data
        $data = DateTime::createFromFormat('Y-m-d', $_POST['dataNascimento']);
        if (!$data) {
            throw new Exception("Data inválida!");
        }
        
        // Converter a data para o formato Excel
        $excelDate = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($data);

        // Inserir dados
        $worksheet->setCellValue('A' . $highestRow, $_POST['nome']);
        $worksheet->setCellValue('B' . $highestRow, $excelDate);
        $worksheet->setCellValue('C' . $highestRow, $_POST['numeroPassivo']);
        $worksheet->setCellValue('D' . $highestRow, $_POST['caixa']);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($inputFileName);

        $message = "Registro cadastrado com sucesso!";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Erro: " . $e->getMessage();
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro - Passivo</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Passivo</h1>
        
        <div class="nav-buttons">
            <a href="index.php" class="btn-secondary">Voltar para Consulta</a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="card-form">
            <form method="POST" class="cadastro-form">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           required 
                           placeholder="Digite o nome completo"
                           autocomplete="off">
                </div>
                
                <div class="form-group">
                    <label for="dataNascimento">Data de Nascimento</label>
                    <input type="date" 
                           id="dataNascimento" 
                           name="dataNascimento" 
                           required
                           min="1900-01-01"
                           max="<?php echo date('Y-m-d'); ?>"
                           value="1970-01-01">
                </div>
                
                <div class="form-group">
                    <label for="numeroPassivo">Número do Passivo</label>
                    <input type="text" 
                           id="numeroPassivo" 
                           name="numeroPassivo" 
                           required 
                           placeholder="Digite o número do passivo"
                           pattern="[0-9]+"
                           title="Apenas números são permitidos">
                </div>
                
                <div class="form-group">
                    <label for="caixa">Número da Caixa</label>
                    <input type="text" 
                           id="caixa" 
                           name="caixa" 
                           required 
                           placeholder="Digite o número da caixa">
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="cadastrar" class="btn-submit">
                        Cadastrar
                    </button>
                    <a href="index.php" class="btn-voltar">Voltar para Consulta</a>
                </div>
            </form>
        </div>
        <div class="signature">Desenvolvido por André Ricardo</div>
    </div>
</body>
</html> 