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
    <meta http-equiv="Refresh" content="3;URL=inicio.php">
    <link rel="stylesheet" type="text/css" href="./estilos/layout.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Bitter" rel="stylesheet">
    <link rel="icon" href="./imagens/logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Adicionando jQuery -->
    <script src="./scripts/codigo_site.js" defer></script>
</head>

<body>

<header>
    <!-- Div externa -->
    <div class="banner_principal">
        <!-- Div interna com o texto "Sala de Leitura" -->
        <div class="texto_banner">
            Sala de Leitura
        </div>

        <!-- Nova div interna à direita com oo nome e o nível de usuário" -->
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
           $query = mysqli_query($conexao, "SELECT * FROM usuarios WHERE id = $v_id") or die(mysqli_error($conexao));
           $row = mysqli_fetch_array($query);
           echo "<div>" . $row['nome_user'] . "</div>";

           $texto_nivel=$row['nivel_user'];
           if($texto_nivel=="G") {
            echo "<div>Gestor do Sistema</div>";
           }
           else{
            echo "<div>Aluno</div>";
           }
           ?> 
        </div>

        <!-- Div interna com a navbar -->
          <?php
            //include("menu.php");
            //session_start();
            require 'menu.php';
	        echo gerarMenu();
          ?>
    
    </div>
</header>
<?php
// Conexão com o banco de dados
include("conecta.php");

// Verifica se os dados foram recebidos corretamente via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $user_id = $_POST['user_id'];
    $user_nome = $_POST['nome'];
    $livro_id = $_POST['livro_id'];
    $livro_nome = $_POST['livro'];
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'];
    $status = 'Emprestado';

    // Verifica se os campos estão vazios
    if (empty($user_id) || empty($livro_id) || empty($data_emprestimo) || empty($data_devolucao)) {
        die("Todos os campos são obrigatórios.");
    }

    // Prepara o comando SQL para inserir o empréstimo no banco de dados
    $sql = "INSERT INTO emprestimos (user_id, nome_usuario, livro_id, nome_livro, data_emprestimo, data_devolucao, status_devolucao) 
            VALUES ('$user_id', '$user_nome', '$livro_id', '$livro_nome', '$data_emprestimo', '$data_devolucao', '$status')";

    // Executa a consulta SQL
    if (mysqli_query($conexao, $sql)) {       
        echo "<div class='aviso'>";
        echo "<img src='./imagens/alerta_ok.png'>";
        echo "<h2>Emprestimo Realizado com Sucesso</h2>";
        echo "<p>Aguarde o retorno automaticamente</p>";
        echo "</div>";
    } else {
        echo "Erro ao realizar o empréstimo: " . mysqli_error($conexao);
    }

    // Fecha a conexão com o banco de dados
    mysqli_close($conexao);
} else {
    echo "Método de requisição inválido.";
}
?>