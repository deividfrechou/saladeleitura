<?php
// Configuração da Conexão
$host = 'localhost';
$user = 'root'; // Usuário padrão do XAMPP
$pass = '';     // Senha padrão vazia do XAMPP
$nomeBanco = "biblioteca";

// Conexão com servidor MySQL/MariaDB
$link = new mysqli($host, $user, $pass, ''); // Conecta sem banco selecionado inicialmente
$link->set_charset('utf8mb4');

// Verificar conexão
if ($link->connect_error)
{
    die('Erro de Conexão: (' . $link->connect_errno . ') ' . $link->connect_error);
}

echo "✓ Conexão realizada com sucesso<br><br>";

// --- GARANTIA DE LIMPEZA E CRIAÇÃO ---

// PASSO 1: Deletar banco se existir (para garantir limpeza total)
$sql_drop = "DROP DATABASE IF EXISTS `$nomeBanco`";
if ($link->query($sql_drop))
{
    echo "✓ Banco anterior removido (se existisse)<br>";
}

// PASSO 2: Criar banco de dados
$sql_create_db = "CREATE DATABASE `$nomeBanco` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($link->query($sql_create_db))
{
    echo "✓ Banco de dados '$nomeBanco' criado com sucesso<br>";
}
else
{
    die("✗ Erro ao criar banco: " . $link->error . "<br>");
}

// PASSO 3: Selecionar o banco
if (!$link->select_db($nomeBanco))
{
    die("✗ Erro ao selecionar banco: " . $link->error . "<br>");
}
echo "✓ Banco selecionado com sucesso<br><br>";

// --- CRIAÇÃO DE TABELAS (AGORA COM IF NOT EXISTS PARA ROBUSTEZ) ---

// PASSO 4: Criar tabela usuarios
$sql_usuarios = "CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `ativo_user` VARCHAR(2) NOT NULL,
    `nivel_user` VARCHAR(10) NOT NULL,
    `nome_user` VARCHAR(100) NOT NULL,
    `email_user` VARCHAR(100) NOT NULL UNIQUE,
    `telefone_user` VARCHAR(20),
    `senha_user` VARCHAR(250) NOT NULL,
    `termos_user` VARCHAR(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($link->query($sql_usuarios))
{
    echo "✓ Tabela 'usuarios' criada com sucesso<br>";
}
else
{
    die("✗ Erro ao criar tabela usuarios: " . $link->error . "<br>");
}

// PASSO 5: Inserir usuário padrão (A senha 'educar360' não está criptografada aqui, use sempre 'password_hash' em produção!)
// NOTA: É recomendável usar password_hash para senhas em produção.
$senha_criptografada = 'educar360'; // Substitua por password_hash('educar360', PASSWORD_DEFAULT); em um projeto real.
$sql_insert_user = "INSERT INTO `usuarios`
    (`id`, `ativo_user`, `nivel_user`, `nome_user`, `email_user`, `telefone_user`, `senha_user`, `termos_user`)
    VALUES (NULL, 'S', 'G', 'Deivid Pereira Frechou', 'deividfrechou@hotmail.com', '51989505145', '$senha_criptografada', 'S')";

if ($link->query($sql_insert_user))
{
    echo "✓ Usuário padrão inserido com sucesso<br><br>";
}
else
{
    // Se o INSERT falhar, provavelmente é porque o usuário já existe (devido à chave UNIQUE).
    // Para um script de automação, podemos apenas emitir um aviso e seguir.
    echo "✗ Aviso: Erro ao inserir usuário padrão. Possível duplicidade. Detalhes: " . $link->error . "<br>";
}


// PASSO 6: Criar tabela livros
$sql_livros = "CREATE TABLE IF NOT EXISTS `livros` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `titulo_livro` VARCHAR(100) NOT NULL,
    `genero_livro` VARCHAR(100) NOT NULL,
    `autor_livro` VARCHAR(150) NOT NULL,
    `editora_livro` VARCHAR(100),
    `edicao_livro` VARCHAR(50),
    `isbn_livro` VARCHAR(20) UNIQUE,
    `publicacao_livro` INT,
    `data_cadastro_livro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($link->query($sql_livros))
{
    echo "✓ Tabela 'livros' criada com sucesso<br>";
}
else
{
    die("✗ Erro ao criar tabela livros: " . $link->error . "<br>");
}

// PASSO 7: Criar tabela emprestimos com Foreign Keys
$sql_emprestimos = "CREATE TABLE IF NOT EXISTS `emprestimos` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `nome_usuario` VARCHAR(100) NOT NULL,
    `livro_id` INT NOT NULL,
    `nome_livro` VARCHAR(100) NOT NULL,
    `data_emprestimo` DATE,
    `data_devolucao` DATE,
    `status_devolucao` VARCHAR(20) NOT NULL,
    CONSTRAINT `fk_emprestimos_usuarios` FOREIGN KEY (`user_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_emprestimos_livros` FOREIGN KEY (`livro_id`) REFERENCES `livros`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($link->query($sql_emprestimos))
{
    echo "✓ Tabela 'emprestimos' criada com sucesso<br><br>";
}
else
{
    die("✗ Erro ao criar tabela emprestimos: " . $link->error . "<br>");
}

// Sucesso final
echo "<div style='background-color: #d4edda; padding: 15px; border-radius: 5px; margin-top: 20px;'>";
echo "<h2 style='color: #155724;'>✓ SUCESSO!</h2>";
echo "<p style='color: #155724;'>Banco de dados '$nomeBanco' foi criado e configurado com sucesso!</p>";
echo "<p style='color: #155724;'><strong>Tabelas criadas:</strong></p>";
echo "<ul style='color: #155724;'>";
echo "<li>usuarios</li>";
echo "<li>livros</li>";
echo "<li>emprestimos</li>";
echo "</ul>";
echo "<br><a href='index.php' style='background-color: #155724; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir para página inicial</a>";
echo "</div>";

$link->close();
?>