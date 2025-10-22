<?php
//session_start();
// Verifica se o papel do usuário está na lista de papéis permitidos
  //  if ($_SESSION['nivel']=="A") {
    //    header("Location: erro_sistema.php");
      //  exit;
    //}
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
include ("conecta.php");

$id = $_GET['id']; 
$nome = $_GET['nome']; 
$telefone = $_GET['telefone']; 
$email = $_GET['email']; 
$senha = $_GET['senha']; 

$query = "UPDATE usuarios SET nome_user='".$nome."',telefone_user='".$telefone."',email_user='".$email."', senha_user='".$senha."' WHERE id = '".$id."' ";
mysqli_query($conexao,$query);

echo "<div class='aviso'>";
echo "<img src='./imagens/alerta_ok.png'>";
echo "<h2>Usuário Cadastrado com sucesso.</h2>";
echo "<p>Aguarde o retorno automaticamente</p>";
echo "</div>";

if ($_SESSION['nivel']=="A") {
    header("Refresh: 5; url=inicio_aluno.php");
  }else{
    header("Refresh: 5; url=inicio.php");
  }

?>
</body>
</html>