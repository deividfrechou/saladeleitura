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
  

<?PHP 
  
//seleciona a tabela e executa a consulta
//$query = mysqli_query($conexao, "SELECT * FROM usuarios") or die(mysqli_error($cx));

echo "<div class=lista>";
echo "<a href='JavaScript:location.reload(true)' class='link'><img src='./imagens/atualizar.png'><small>Atualizar</small></a>
      <a href='usuario_cadastro1.php' class='link'><img src='./imagens/usuario_cadastrar.png'><small>Cadastrar</small></a>";
echo "Buscar: <input type='text' id='searchBox' placeholder='Digite para buscar usuários...' />";

echo "<hr size=5 />";
?>

<main>
<div id="userList">
        <?php
        include ("conecta.php");
        // Exibe a lista geral de usuários ao carregar a página
        $query = mysqli_query($conexao, "SELECT * FROM usuarios") or die(mysqli_error($conexao));
        if (mysqli_num_rows($query) > 0) {
            echo "<table class='lista'>";
            echo "<tr>
                    <th>ID</th>
                    <th>Nível</th>
                    <th>Nome</th>
                    <th>Ações</th>
                  </tr>";
            while ($aux = mysqli_fetch_array($query)) {
                echo "<tr>
                        <td>" . $aux["id"] . "</td>
                        <td>" . $aux["nivel_user"] . "</td>
                        <td>" . $aux["nome_user"] . "</td>
                        <td>
                            <a href='usuario_consultar.php?codigo=" . $aux['id'] . "'><img src='./imagens/usuario_consultar.png'></a>
                            <a href='usuario_editar1.php?codigo=" . $aux['id'] . "'><img src='./imagens/usuario_editar.png'></a>
                            <a href='javascript:void(0)' onclick='confirmarExclusao(" . $aux['id'] . ")'><img src='./imagens/usuario_excluir.png'></a>
                            <a href='javascript:void(0)' onclick='confirmarReset(" . $aux['id'] . ")'><img src='./imagens/usuario_reset.png'></a>                            
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhum usuário encontrado.</p>";
        }
        ?>
    </div>
</main>

<script>
$(document).ready(function() {
    $('#searchBox').on('input', function() {
        let searchValue = $(this).val();
        $.ajax({
            url: 'buscar_usuarios.php',
            method: 'GET',
            data: { query: searchValue },
            success: function(data) {
                $('#userList').html(data); // Atualiza o conteúdo da lista
            }
        });
    });
});

function confirmarExclusao(id) {
    if (confirm('Deseja realmente excluir este usuário?')) {
        window.location.href = 'usuario_excluir.php?codigo=' + id;
    }
}

function confirmarReset(id) {
    if (confirm('Deseja realmente resetar a senha deste usuário?\nA senha padrão será -> dombosco')) {
        window.location.href = 'usuario_reset.php?codigo=' + id;
    }
}

</script>

</body>
</html>