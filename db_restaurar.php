<<?php
/**
 * Script de Restore/Upload do Banco de Dados
 * Permite fazer upload de um arquivo SQL e restaurar apenas os REGISTROS
 * Tabelas e banco de dados não são afetados
 */

session_start();

// Verifica se o papel do usuário está na lista de papéis permitidos
if ($_SESSION['nivel']=="A") {
    header("Location: erro_sistema.php");
    exit;
}

include ("conecta.php");

$link = $conexao;
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

$v_id = $_SESSION['logado'];
$query = mysqli_query($link, "SELECT * FROM usuarios WHERE id = $v_id") or die(mysqli_error($link));
$row = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sala de Leitura - Laura Vicuña | Restaurar Banco de Dados</title>
    <meta name="Description" content="Aplicação para gestão de livros da sala de leitura">
    <meta name="keywords" content="deivid, frechou, Sala de Leitura, Laura Vicuña">
    <meta name="robots" content="index, falow">
    <meta name="author" content="Deivid frechou">
    <link rel="stylesheet" type="text/css" href="./estilos/layout.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Bitter" rel="stylesheet">
    <link rel="icon" href="./imagens/logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./scripts/codigo_site.js" defer></script>
    
    <style>
        .container-restore {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .container-restore h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
            font-family: 'Bitter', serif;
        }

        .container-restore p {
            color: #666;
            margin-bottom: 25px;
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

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input[type="file"] {
            display: block;
            width: 100%;
            padding: 12px;
            border: 2px dashed #666;
            border-radius: 5px;
            cursor: pointer;
            background: #f9f9f9;
            transition: all 0.3s;
        }

        .form-group input[type="file"]:hover {
            background: #f0f0f0;
            border-color: #333;
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

        .info-box ul {
            margin-left: 15px;
        }

        .info-box li {
            margin: 5px 0;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background: #333;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .form-group button:hover {
            background: #555;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .form-group button:active {
            transform: translateY(0);
        }

        .links-restore {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .links-restore a {
            color: #333;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .links-restore a:hover {
            color: #666;
            text-decoration: underline;
        }
    </style>
</head>

<body>

<header>
    <div class="banner_principal">
        <div class="texto_banner">
            Sala de Leitura
        </div>

        <div class="texto2_banner">
            <div><?php echo $row['nome_user']; ?></div>
            <?php
                $texto_nivel = $row['nivel_user'];
                if ($texto_nivel == "G") {
                    echo "<div>Gestor do Sistema</div>";
                } else {
                    echo "<div>Aluno</div>";
                }
            ?>
        </div>

        <?php
            require 'menu.php';
            echo gerarMenu();
        ?>
    </div>
</header>

<main>
    <div class="container-restore">
        <h1><i class="fas fa-database"></i> Restaurar Banco de Dados</h1>
        <p>Envie um arquivo de backup SQL para restaurar dados</p>
        
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="arquivo_backup"><i class="fas fa-file-upload"></i> Selecione o arquivo de backup:</label>
                <input 
                    type="file" 
                    id="arquivo_backup" 
                    name="arquivo_backup" 
                    accept=".sql" 
                    required
                >
            </div>
            
            <div class="form-group">
                <button type="submit"><i class="fas fa-sync"></i> Restaurar Registros</button>
            </div>
        </form>
        
        <div class="links-restore">
            <a href="db_backup.php"><i class="fas fa-download"></i> Fazer Backup</a>
            <a href="inicio.php"><i class="fas fa-home"></i> Ir para Início</a>
        </div>
    </div>
</main>

</body>
</html>

<?php
$link->close();
?>