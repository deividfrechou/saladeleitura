<?php
session_start();
// Verifica se o papel do usuário está na lista de papéis permitidos
    if ($_SESSION['nivel']=="A") {
        header("Location: erro_sistema.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sala de Leitura - Laura Vicuña</title>
    <meta name="Description" content="Aplicação para gestão de livros da sala de leitura">
    <meta name="keywords" content="deivid, frechou, Sala de Leitura, Laura Vicuña">
    <meta name="robots" content="index, falow">
    <meta name="author" content="Deivid frechou">
    <link rel="stylesheet" type="text/css" href="./estilos/layout.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Bitter" rel="stylesheet">
    <link rel="icon" href="./imagens/logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <script src="./scripts/codigo_site.js" defer></script>
</head>

<body>

<header>
    <div class="banner_principal">
        <div class="texto_banner">
            Sala de Leitura
        </div>

        <div class="texto2_banner">
            <?php
           //$valor = $_COOKIE["logado"]; 
           //echo $valor;
           if (session_status() !== PHP_SESSION_ACTIVE) {
           session_start();
           }
           //conecta e seleciona banco
           include ("conecta.php");

           $v_id = $_SESSION['logado'];
           // ATENÇÃO: A query original pode estar incorreta, pois 'logado' geralmente é o user_id.
           // Mantenho a estrutura original (consultando 'emprestimos') para compatibilidade.
           // O correto seria: SELECT * FROM usuarios WHERE id = $v_id
           $query = mysqli_query($conexao, "SELECT * FROM emprestimos WHERE id = $v_id") or die(mysqli_error($conexao));
           $row = mysqli_fetch_array($query);
           echo "<div>" . (isset($row['nome_user']) ? $row['nome_user'] : 'Usuário') . "</div>";

           $texto_nivel=(isset($row['nivel_user']) ? $row['nivel_user'] : $_SESSION['nivel']);
           if($texto_nivel=="G") {
            echo "<div>Gestor do Sistema</div>";
           }
           else{
            echo "<div>Aluno</div>";
           }
           ?> 
        </div>

        <?php
            //include("menu.php");
            //session_start();
            require 'menu.php';
	        echo gerarMenu();
          ?>
    
    </div>
</header>
  
<?php
//conecta e seleciona banco
include ("conecta.php");

$v_emprestimo = $_GET['codigo'];

// === MODIFICAÇÃO: SUBSTITUI 'DELETE' POR 'UPDATE' NO 'status_devolucao' ===
$stmt = $conexao->prepare("UPDATE emprestimos SET status_devolucao = 'DEVOLVIDO' WHERE id = ?");
// =========================================================================

$stmt->bind_param("i", $v_emprestimo); 
// Note: O comando $stmt->execute() foi chamado duas vezes no código original. 
// Para ser seguro, mantive apenas o segundo IF para a exibição de mensagem.

if ($stmt->execute()) { // Executa a atualização
    echo "<div class='aviso'>";
    echo "<img src='./imagens/alerta_ok.png'>";
    echo "<h2>Livro devolvido.<br>pegue outro livro e comece a ler de novo!</h2>";
    echo "<p>Aguarde o retorno automaticamente</p>";
    echo "</div>";
    header("Refresh: 2; url=inicio.php");
} else {
    echo "<div class='aviso'>";
    echo "<img src='./imagens/alerta_erro.png'>";
    echo "<h2>Erro ao marcar este livro como devolvido</h2>";
    echo "<p>".$conexao->error."</p>";
    echo "</div>";
    header("Refresh: 2; url=inicio.php");
}

$stmt->close();
$conexao->close();

?>

</body>
</html>