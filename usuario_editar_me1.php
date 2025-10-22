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
if (session_status() !== PHP_SESSION_ACTIVE) {
   session_start();
   }
$v_id = $_SESSION['logado'];
$query = mysqli_query($conexao, "SELECT * FROM usuarios WHERE id =  ".$v_id."") or die(mysqli_error($cx));

$row = mysqli_fetch_array($query);

$id = $row['id'];
$nome = $row['nome_user'];
$email = $row['email_user'];
$telefone = $row['telefone_user'];
$senha = $row['senha_user'];

?>

<div class="formulario">    
   <h2>Alterar meus dados</h2>
   <form method="GET" action="usuario_editar_me2.php">
     <div class="form-group">
       <label for="iduser">ID:</label>
       <input type="text" name="id" id="id" value="<?php echo $id; ?>" readonly>
     </div>
     <div class="form-group">
       <label for="nomeuser">Nome:</label>
       <input type="text" name="nome" id="nome" required="required" placeholder="Seu nome completo" value="<?php echo $nome; ?>">
     </div>
     <div class="form-group">
       <label for="telefoneuser">Telefone:</label>
       <input type="text" name="telefone" id="telefone" required="required" pattern="[0-9]+$" placeholder="51999999999" value="<?php echo $telefone; ?>">
     </div>
     <div class="form-group">
       <label for="emailuser">E-mail:</label>
       <input type="text" name="email" id="email" required="required" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="<?php echo $email; ?>">
     </div>
     <div class="form-group">
       <label for="senhauser">Senha:</label>
       <input type="text" name="senha" id="senha" required="required" value="<?php echo $senha; ?>">
     </div>
     <div class="form-actions">
       <a href="usuarios.php">
         <button type="button"><i class="fas fa-arrow-alt-circle-left fa-1x"></i> Voltar</button>
       </a> 
       <button type="submit"><i class="fas fa-save"></i>Atualizar</button>
     </div>
   </form>    
 </div>
 

</body>
</html>