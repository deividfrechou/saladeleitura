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

<div class="formulario">    
    <h2>Cadastro de Livros</h2>
    <form action="livro_cadastro2.php" method="post">
      <div class="form-group">
        <label for="livro">Livro:</label>
        <input type="text" name="livro" id="livro" required="required" placeholder="Título do livro">
      </div>
      <div class="form-group">
        <label for="genero">Genero:</label>
         <select name="genero" id="genero" required="required">
           <option value="outros">Outros</option>
           <option value="Aventura">Aventura</option>
<option value="Autobiografia">Autobiografia</option>
<option value="Biografia">Biografia</option>
<option value="Conto">Conto</option>
<option value="Cronica">Crônica</option>
<option value="Drama">Drama</option>
<option value="Fantasia">Fantasia</option>
<option value="Ficcao_Cientifica">Ficção Científica</option>
<option value="Ficcao_Historica">Ficção Histórica</option>
<option value="Historia_em_Quadrinhos">História em Quadrinhos</option>
<option value="Horror">Horror</option>
<option value="Infantil">Infantil</option>
<option value="Infantojuvenil">Infantojuvenil</option>
<option value="Literatura_Brasileira">Literatura Brasileira</option>
<option value="Literatura_Estrangeira">Literatura Estrangeira</option>
<option value="Literatura_Nacional">Literatura Nacional</option>
<option value="Manual">Manual</option>
<option value="Misterio">Mistério</option>
<option value="Poesia">Poesia</option>
<option value="Policial">Policial</option>
<option value="Romance">Romance</option>
<option value="Romance_de_Aventura">Romance de Aventura</option>
<option value="Romance_Historico">Romance Histórico</option>
<option value="Suspense">Suspense</option>
<option value="Terror">Terror</option>
<option value="Teatro">Teatro</option>
<option value="Tecnico">Técnico</option>
<option value="Teologia">Teologia</option>
<option value="Artes">Artes</option>
<option value="Ciencias">Ciências</option>
<option value="Ciencias_Humanas">Ciências Humanas</option>
<option value="Ciencias_Sociais">Ciências Sociais</option>
<option value="Dicionario">Dicionário</option>
<option value="Didatico">Didático</option>
<option value="Educacao">Educação</option>
<option value="Enciclopedia">Enciclopédia</option>
<option value="Geografia">Geografia</option>
<option value="Historia">História</option>
<option value="Linguas">Línguas</option>
<option value="Literatura">Literatura</option>
<option value="Matematica">Matemática</option>
<option value="Portugues">Português</option>
          </select>
      </div>
      <div class="form-group">
        <label for="autor">Autores:</label>
        <input type="text" name="autor" id="autor" placeholder="Autores do livro separados por vírgula">
      </div>
      <div class="form-group">
        <label for="editora">Editora:</label>
        <input type="text" name="editora" id="editora" placeholder="Editora do livro">
      </div>
      <div class="form-group">
        <label for="edicao">Edição:</label>
        <input type="text" name="edicao" id="edicao" placeholder="Edição do livro - 1º edição ...">
      </div>
      <div class="form-group">
        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" id="isbn" placeholder="Padrão Internacional de Numeração de Livro">
      </div>
      <div class="form-group">
        <label for="publicado">Publicação:</label>
        <input type="text" name="publicado" id="publicado" placeholder="Ano de publicação">
      </div>
      <div class="form-actions">
        <a href="livros.php">
          <button type="button"><i class="fas fa-arrow-alt-circle-left fa-1x"></i> Voltar</button>
        </a> 
        <button type="submit"><i class="fas fa-save"></i> Cadastrar</button>
      </div>
    </form>    
  </div> 

<script type="text/javascript" language="javascript">
function valida_form (){
if(document.getElementById("nome").value.length < 3){
alert('Por favor, preencha o campo nome');
document.getElementById("nome").focus();
return false
}
return true
}

</script>  
 </body>
</html>