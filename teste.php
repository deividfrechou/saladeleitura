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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./scripts/codigo_site.js" defer></script>
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

        <?php
            require 'menu.php';
	        echo gerarMenu();
          ?>
    
    </div>
</header>

<body>

<?PHP  
echo "<div class=lista>";
echo "<a href='JavaScript:location.reload(true)' class='link'><img src='./imagens/atualizar.png'><small>Atualizar</small></a>
      <a href='emprestimo_cadastro1.php' class='link'><img src='./imagens/usuario_cadastrar.png'><small>Cadastrar</small></a>";

// Search interface
echo "<select id='searchType' style='width: 180px; margin-right: 10px'>
        <option value='nome_usuario'>Usuário</option>
        <option value='nome_livro'>Livro</option>
      </select>";
echo "<input type='text' id='searchBox' placeholder='Digite para buscar...' />";

echo "<hr size=5 />";
?>

<main>
<div id="emprestimoList">
    <?php
    include ("conecta.php");

    // Initial query to show all emprestimos
    $query = mysqli_query($conexao, "SELECT * FROM emprestimos ORDER BY id DESC") or die(mysqli_error($conexao));
    if (mysqli_num_rows($query) > 0) {
        echo "<table class='lista'>";
        echo "<tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>Livro</th>
                <th>Data Empréstimo</th>
                <th>Data Devolução</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>";
        while ($aux = mysqli_fetch_array($query)) {
            // Formatar datas
            $data_emp = date('d/m/Y', strtotime($aux["data_emprestimo"]));
            $data_dev = $aux["data_devolucao"] ? date('d/m/Y', strtotime($aux["data_devolucao"])) : "Não devolvido";
            
            // Definir cor de status
            $status_class = "";
            if ($aux["status_devolucao"] == "Devolvido") {
                $status_class = "style='color: green; font-weight: bold;'";
            } elseif ($aux["status_devolucao"] == "Pendente") {
                $status_class = "style='color: orange; font-weight: bold;'";
            } elseif ($aux["status_devolucao"] == "Atrasado") {
                $status_class = "style='color: red; font-weight: bold;'";
            }
            
            echo "<tr>
                    <td>" . $aux["id"] . "</td>                    
                    <td>" . $aux["nome_usuario"] . "</td>
                    <td>" . $aux["nome_livro"] . "</td>
                    <td>" . $data_emp . "</td>
                    <td>" . $data_dev . "</td>
                    <td " . $status_class . ">" . $aux["status_devolucao"] . "</td>
                    <td>
                        <a href='emprestimo_consultar.php?codigo=" . $aux['id'] . "'><img src='./imagens/livro_consultar.png'></a>
                        <a href='emprestimo_editar1.php?codigo=" . $aux['id'] . "'><img src='./imagens/livro_editar.png'></a>
                        <a href='javascript:void(0)' onclick='confirmarExclusao(" . $aux['id'] . ")'><img src='./imagens/livro_excluir.png'></a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhum empréstimo encontrado.</p>";
    }
    ?>
</div>
</main>

<script>
$(document).ready(function() {
    $('#searchBox').on('input', function() {
        let searchValue = $(this).val();
        let searchType = $('#searchType').val();
        
        console.log('Buscando:', searchType, searchValue);
        
        $.ajax({
            url: 'buscar_teste.php',
            method: 'GET',
            data: {
                query: searchValue,
                type: searchType
            },
            success: function(data) {
                $('#emprestimoList').html(data);
                console.log('Sucesso na busca');
            },
            error: function(xhr, status, error) {
                console.error('Erro na busca:', error);
                console.log(xhr.responseText);
            }
        });
    });
});

function confirmarExclusao(id) {
    if (confirm('Deseja realmente excluir este empréstimo?')) {
        window.location.href = 'emprestimo_excluir.php?codigo=' + id;
    }
}

</script>

</body>
</html>