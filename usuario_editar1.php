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
$query = mysqli_query($conexao, "SELECT * FROM usuarios WHERE id =  ".$v_id."") or die(mysqli_error($cx));

$row = mysqli_fetch_array($query);

$id = $row['id'];
$ativo = $row['ativo_user'];
$nivel = $row['nivel_user'];
$nome = $row['nome_user'];
$email = $row['email_user'];
$telefone = $row['telefone_user']; 
 
?>

<div class="formulario">    
   <h2>Alterar dados do usuário</h2>
   <form method="GET" action="usuario_editar2.php">
     <div class="form-group">
       <label for="id">ID:</label>
       <input name="id" type="text" id="id" value="<?php echo $id ; ?>" readonly/>
     </div>
     <div class="form-group">
       <label for="ativo">Ativo?</label>
       <select name="ativo" id="ativo" required="required">
         <option value="<?php echo $ativo ; ?>" selected><?php echo $ativo ; ?></option>
         <option value="S">Sim</option>
         <option value="N">Não</option>
       </select>
     </div>
     <div class="form-group">
       <label for="nivel">Nível:</label>
       <select name="nivel" id="nivel" required="required">
         <option value="<?php echo $nivel ; ?>" selected><?php echo $nivel ; ?></option>
         <option value="A">Aluno</option>
         <option value="G">Gestor</option>
       </select>
     </div>
     <div class="form-group">
       <label for="nome">Nome:</label>
       <input name="nome" type="text" id="nome" value="<?php echo $nome; ?>" autocomplete="off" />
     </div>
     <div class="form-group">
       <label for="email">E-mail:</label>
       <input name="email" type="text" id="email" value="<?php echo $email; ?>" autocomplete="off"/>
     </div>
     <div class="form-group">
       <label for="telefone">Telefone:</label>
       <input name="telefone" type="text" id="telefone" value="<?php echo $telefone; ?>" autocomplete="off"/>
     </div>
     <div class="form-actions">
       <a href="usuarios.php"><button type="button" ><i class="fas fa-arrow-alt-circle-left fa-1x"></i> Voltar</button></a> 
       <button type="submit"><i class="fas fa-save"></i> Atualizar</button>
     </div>
   </form>
</div>

</body>
</html>