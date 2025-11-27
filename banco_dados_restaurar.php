<?php
/**
 * Script de Restore/Upload do Banco de Dados
 * Permite fazer upload de um arquivo SQL e restaurar apenas os REGISTROS
 * Tabelas e banco de dados não são afetados
 */

session_start();

$link = new mysqli('localhost', 'root', '', '');
$link->set_charset('utf8mb4');

if ($link->connect_error) {
    die('Erro de Conexão: ' . $link->connect_error);
}

$nomeBanco = "biblioteca";
$mensagem = '';
$tipo_mensagem = '';

// PASSO 1: Processar upload se enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['arquivo_backup'])) {
    
    // Validar arquivo
    if ($_FILES['arquivo_backup']['error'] !== UPLOAD_ERR_OK) {
        $mensagem = "Erro ao enviar arquivo: " . $_FILES['arquivo_backup']['error'];
        $tipo_mensagem = 'erro';
    } elseif ($_FILES['arquivo_backup']['type'] !== 'application/octet-stream' && 
              $_FILES['arquivo_backup']['type'] !== 'text/plain' &&
              strpos($_FILES['arquivo_backup']['name'], '.sql') === false) {
        $mensagem = "Por favor, envie um arquivo SQL válido (.sql)";
        $tipo_mensagem = 'erro';
    } else {
        // Ler o arquivo
        $arquivo_conteudo = file_get_contents($_FILES['arquivo_backup']['tmp_name']);
        
        if ($arquivo_conteudo === false) {
            $mensagem = "Erro ao ler o arquivo";
            $tipo_mensagem = 'erro';
        } else {
            // Selecionar banco
            if (!$link->select_db($nomeBanco)) {
                $mensagem = "Erro ao selecionar banco: " . $link->error;
                $tipo_mensagem = 'erro';
            } else {
                // PASSO 1: Limpar dados existentes (DELETE FROM sem deletar tabelas)
                $tabelas = array('emprestimos', 'livros', 'usuarios');
                
                $link->query("SET FOREIGN_KEY_CHECKS = 0");
                
                foreach ($tabelas as $tabela) {
                    $link->query("DELETE FROM `$tabela`");
                }
                
                // PASSO 2: Extrair apenas os comandos INSERT
                $linhas = explode("\n", $arquivo_conteudo);
                $erro = false;
                $registros_inseridos = 0;
                $inserts_processados = 0;
                
                foreach ($linhas as $linha) {
                    $linha = trim($linha);
                    
                    // Ignorar linhas vazias e comentários
                    if (empty($linha) || strpos($linha, '--') === 0) {
                        continue;
                    }
                    
                    // Processar apenas comandos INSERT
                    if (stripos($linha, 'INSERT INTO') === 0) {
                        // Garantir que termina com ;
                        if (substr($linha, -1) !== ';') {
                            $linha .= ';';
                        }
                        
                        $inserts_processados++;
                        
                        if (!$link->query($linha)) {
                            $erro = true;
                            $mensagem .= "Erro ao inserir registro #" . $inserts_processados . ": " . $link->error . "<br>";
                        } else {
                            $registros_inseridos++;
                        }
                    }
                }
                
                $link->query("SET FOREIGN_KEY_CHECKS = 1");
                
                if (!$erro) {
                    $mensagem = "✓ Backup restaurado com sucesso! " . $registros_inseridos . " registro(s) inserido(s).";
                    $tipo_mensagem = 'sucesso';
                } else {
                    $mensagem = "⚠ Restauração parcial concluída com " . $registros_inseridos . " registro(s) inserido(s).<br>" . $mensagem;
                    $tipo_mensagem = 'aviso';
                }
            }
        }
    }
}

$link->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restore Banco de Dados</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .mensagem {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }
        
        .mensagem.sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }
        
        .mensagem.erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
        
        .mensagem.aviso {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
            display: block;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        
        input[type="file"] {
            display: block;
            width: 100%;
            padding: 12px;
            border: 2px dashed #667eea;
            border-radius: 5px;
            cursor: pointer;
            background: #f9f9f9;
            transition: all 0.3s;
        }
        
        input[type="file"]:hover {
            background: #f0f0f0;
            border-color: #764ba2;
        }
        
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 3px;
            font-size: 13px;
            color: #0c5aa0;
            line-height: 1.6;
        }
        
        .info-box strong {
            display: block;
            margin-bottom: 8px;
        }
        
        .info-list {
            margin-left: 15px;
        }
        
        .info-list li {
            margin: 5px 0;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Restaurar Registros</h1>
        <p class="subtitle">Envie um arquivo de backup SQL para restaurar dados</p>
        
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <div class="info-box">
            <strong>ℹ️ Como funciona:</strong>
            <ul class="info-list">
                <li>✓ Limpa os dados existentes</li>
                <li>✓ Restaura apenas os registros</li>
                <li>✓ Tabelas e banco de dados mantêm intactos</li>
                <li>✓ Preserva a estrutura do banco</li>
            </ul>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="arquivo_backup">📁 Selecione o arquivo de backup:</label>
                <input 
                    type="file" 
                    id="arquivo_backup" 
                    name="arquivo_backup" 
                    accept=".sql" 
                    required
                >
            </div>
            
            <button type="submit">🔄 Restaurar Registros</button>
        </form>
        
        <div class="links">
            <a href="backup.php">📥 Fazer Backup</a>
            <a href="index.php">🏠 Ir para Início</a>
        </div>
    </div>
</body>
</html>