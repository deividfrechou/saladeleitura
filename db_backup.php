<?php
/**
 * Script de Backup do Banco de Dados
 * Cria um arquivo SQL com toda a estrutura e dados do banco
 */

$link = new mysqli('localhost', 'root', '', '');
$link->set_charset('utf8mb4');

if ($link->connect_error) {
    die('Erro de Conexão: ' . $link->connect_error);
}

$nomeBanco = "biblioteca";

// Selecionar o banco
if (!$link->select_db($nomeBanco)) {
    die('Erro ao selecionar banco: ' . $link->error);
}

$backup_sql = "";
$data_hora = date('d-m-Y_H-i-s');
$nome_arquivo = "backup_biblioteca_" . $data_hora . ".sql";

// PASSO 1: Obter todas as tabelas do banco
$result_tables = $link->query("SHOW TABLES");

if (!$result_tables) {
    die('Erro ao obter tabelas: ' . $link->error);
}

// PASSO 2: Para cada tabela, gerar o backup
while ($row = $result_tables->fetch_row()) {
    $table = $row[0];
    
    // Obter a estrutura da tabela (CREATE TABLE)
    $result_create = $link->query("SHOW CREATE TABLE `$table`");
    $create_row = $result_create->fetch_row();
    $backup_sql .= "\n\n-- =====================================================\n";
    $backup_sql .= "-- Tabela: `$table`\n";
    $backup_sql .= "-- =====================================================\n";
    $backup_sql .= "DROP TABLE IF EXISTS `$table`;\n";
    $backup_sql .= $create_row[1] . ";\n\n";
    
    // Obter todos os dados (INSERT)
    $result_data = $link->query("SELECT * FROM `$table`");
    
    if ($result_data->num_rows > 0) {
        $backup_sql .= "-- Inserir dados na tabela `$table`\n";
        
        while ($data_row = $result_data->fetch_assoc()) {
            $columns = implode('`, `', array_keys($data_row));
            $values = array_values($data_row);
            $values = array_map(function($val) use ($link) {
                if ($val === null) {
                    return 'NULL';
                }
                return "'" . $link->real_escape_string($val) . "'";
            }, $values);
            $values_str = implode(', ', $values);
            $backup_sql .= "INSERT INTO `$table` (`$columns`) VALUES ($values_str);\n";
        }
        $backup_sql .= "\n";
    }
}

// PASSO 3: Enviar o arquivo para download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $nome_arquivo . '"');
header('Content-Length: ' . strlen($backup_sql));
header('Pragma: no-cache');
header('Expires: 0');

echo $backup_sql;

$link->close();
?>
