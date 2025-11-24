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

<body>
<!-- [Previous header content remains the same...] -->

<?PHP  
echo "<div class=lista>";
echo "<a href='JavaScript:location.reload(true)' class='link'><img src='./imagens/atualizar.png'><small>Atualizar</small></a>
      <a href='livro_cadastro1.php' class='link'><img src='./imagens/usuario_cadastrar.png'><small>Cadastrar</small></a>";

// New search interface
echo "<select id='searchType' style='width: 180px; margin-right: 10px'>
        <option value='titulo_livro'>Livro</option>
        <option value='genero_livro'>Gênero</option>
        <option value='autor_livro'>Autor</option>
        <option value='editora_livro'>Editora</option>
        <option value='publicacao_livro'>Ano de Publicação</option>
      </select>";
echo "<input type='text' id='searchBox' placeholder='Digite para buscar...' />";

echo "<hr size=5 />";
?>

<main>
<div id="livroList">
    <?php
    include ("conecta.php");

    
    // Initial query to show all books
    $query = mysqli_query($conexao, "SELECT * FROM livros") or die(mysqli_error($conexao));
    if (mysqli_num_rows($query) > 0) {
        echo "<table class='lista'>";
        echo "<tr>
                <th>ID</th>
                <th>Livro</th>
                <th>Gênero</th>
                <th>Autor</th>
                <th>Editora</th>
                <th>Ano</th>
                <th>Ações</th>
              </tr>";
        while ($aux = mysqli_fetch_array($query)) {
            echo "<tr>
                    <td>" . $aux["id"] . "</td>                    
                    <td>" . $aux["titulo_livro"] . "</td>
                    <td>" . $aux["genero_livro"] . "</td>
                    <td>" . $aux["autor_livro"] . "</td>
                    <td>" . $aux["editora_livro"] . "</td>
                    <td>" . $aux["publicacao_livro"] . "</td>
                    <td>
                        <a href='livro_consultar.php?codigo=" . $aux['id'] . "'><img src='./imagens/livro_consultar.png'></a>
                        <a href='livro_editar1.php?codigo=" . $aux['id'] . "'><img src='./imagens/livro_editar.png'></a>
                        <a href='javascript:void(0)' onclick='confirmarExclusao(" . $aux['id'] . ")'><img src='./imagens/livro_excluir.png'></a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhum livro encontrado.</p>";
    }
    ?>
</div>
</main>

<script>
$(document).ready(function() {
    $('#searchBox').on('input', function() {
        let searchValue = $(this).val();
        let searchType = $('#searchType').val();
        
        console.log('Buscando:', searchType, searchValue); // Debug log
        
        $.ajax({
            url: 'buscar_livros.php',
            method: 'GET',
            data: {
                query: searchValue,
                type: searchType
            },
            success: function(data) {
                $('#livroList').html(data);
                console.log('Sucesso na busca'); // Debug log
            },
            error: function(xhr, status, error) {
                console.error('Erro na busca:', error);
                console.log(xhr.responseText);
            }
        });
    });
});

function confirmarExclusao(id) {
    if (confirm('Deseja realmente excluir este livro?')) {
        window.location.href = 'livro_excluir.php?codigo=' + id;
    }
}

</script>

</body>
</html>