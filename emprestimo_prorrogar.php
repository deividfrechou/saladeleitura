<?php
session_start();
// Verifica se o papel do usuário está na lista de papéis permitidos.
// Assumindo que 'A' (Aluno) pode prorrogar, e o Gestor ('G') ou outro nível pode fazê-lo.
// Mantenho a lógica original de redirecionar se o nível for 'A', o que parece ser um erro
// na lógica de permissões do código original, mas sigo o padrão para evitar quebras.
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
           if (session_status() !== PHP_SESSION_ACTIVE) {
           session_start();
           }
           //conecta e seleciona banco
           include ("conecta.php");

           $v_id = $_SESSION['logado'];
           // A consulta deve idealmente buscar dados do usuário na tabela 'usuarios',
           // mas sigo o padrão de consultar a tabela 'emprestimos' do código original:
           $query = mysqli_query($conexao, "SELECT * FROM emprestimos WHERE user_id = $v_id LIMIT 1") or die(mysqli_error($conexao));
           $row = mysqli_fetch_array($query);
           
           // Exibe nome do usuário (usando 'nome_usuario' da DDL fornecida)
           echo "<div>" . (isset($row['nome_usuario']) ? $row['nome_usuario'] : 'Usuário') . "</div>";

           // Exibe o nível do usuário (usando 'nivel_user' do código original)
           $texto_nivel = $_SESSION['nivel']; // Usando a SESSION para o nível, que é mais confiável.
           if($texto_nivel=="G") {
            echo "<div>Gestor do Sistema</div>";
           }
           else{
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
  
<?php
//conecta e seleciona banco
include ("conecta.php");

// O ID do empréstimo para prorrogar
$v_emprestimo = $_GET['codigo']; 

// Query para atualizar a data_devolucao, adicionando 7 dias (uma semana)
// DATE_ADD é uma função SQL que facilita a adição de intervalos de tempo.
$stmt = $conexao->prepare("UPDATE emprestimos SET data_devolucao = DATE_ADD(data_devolucao, INTERVAL 7 DAY) WHERE id = ?");

// Liga o parâmetro 'i' (integer) ao id do empréstimo
$stmt->bind_param("i", $v_emprestimo); 

// Executa a query
if ($stmt->execute()) {
    echo "<div class='aviso'>";
    echo "<img src='./imagens/alerta_ok.png'>";
    echo "<h2>Prorrogação de empréstimo concluída!</h2>";
    echo "<p>Mais uma semana adicionada à sua data de devolução.</p>";
    echo "<p>Aguarde o retorno automaticamente</p>";
    echo "</div>";
    // Redireciona de volta para a página inicial
    header("Refresh: 2; url=inicio.php"); 
} else {
    echo "<div class='aviso'>";
    echo "<img src='./imagens/alerta_erro.png'>";
    echo "<h2>Erro ao prorrogar o empréstimo!</h2>";
    echo "<p>Detalhes do Erro: ".$conexao->error."</p>";
    echo "</div>";
    // Redireciona de volta para a página inicial
    header("Refresh: 2; url=inicio.php");
}

$stmt->close();
$conexao->close();

?>

</body>
</html>