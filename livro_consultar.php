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
//conecta e seleciona banco
include ("conecta.php");

$v_id = $_GET['codigo'];
$query = mysqli_query($conexao, "SELECT * FROM livros WHERE id =  ".$v_id."") or die(mysqli_error($cx));

$row = mysqli_fetch_array($query);

echo "<div class=lista>";
echo "<br><a href='livros.php' class='link'><img src='./imagens/voltar.png'><small>Voltar</small></a>";

echo "<hr size=5 />";


echo "<table class='lista'>";
echo "<tr><td>Id</td><td>".$row['id']."</td><tr>";
echo "<tr><td>Livro</td><td>".$row['titulo_livro']."</td><tr>";
echo "<tr><td>Genero</td><td>".$row['genero_livro']."</td><tr>";
echo "<tr><td>Autores</td><td>".$row['autor_livro']."</td><tr>";
echo "<tr><td>Editora</td><td>".$row['editora_livro']."</td><tr>";
echo "<tr><td>ISBN</td><td>".$row['isbn_livro']."</td><tr>";
echo "<tr><td>Ano publicação</td><td>".$row['publicacao_livro']."</td><tr>";
echo "<tr><td>Quantidade</td><td>".$row['quantidade_livro']."</td><tr>";
echo "<tr><td>Data Inclusão</td><td>".$row['data_cadastro_livro']."</td><tr>";
echo "</table>";

?> 

</body>
</html>