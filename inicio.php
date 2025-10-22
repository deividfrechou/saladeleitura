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
    <style>
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
            padding: 15px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .emprestimo-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 4px solid #ddd;
        }

        .emprestimo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .emprestimo-card.status-devolvido {
            border-left-color: #28a745;
        }

        .emprestimo-card.status-pendente {
            border-left-color: #ffc107;
        }

        .emprestimo-card.status-atrasado {
            border-left-color: #dc3545;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-id {
            background: #007bff;
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 12px;
        }

        .card-status {
            padding: 3px 10px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 11px;
        }

        .status-devolvido {
            background: #d4edda;
            color: #155724;
        }

        .status-pendente {
            background: #fff3cd;
            color: #856404;
        }

        .status-atrasado {
            background: #f8d7da;
            color: #721c24;
        }

        .card-info {
            margin: 10px 0;
        }

        .card-info-row {
            display: flex;
            margin-bottom: 6px;
            align-items: flex-start;
        }

        .card-info-label {
            font-weight: bold;
            color: #555;
            min-width: 75px;
            font-size: 11px;
        }

        .card-info-value {
            color: #333;
            font-size: 11px;
            flex: 1;
        }

        .card-usuario {
            font-size: 13px;
            color: #007bff;
            font-weight: bold;
        }

        .card-livro {
            font-size: 12px;
            color: #333;
            font-weight: 500;
        }

        .card-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #f0f0f0;
        }

        .card-actions a {
            text-decoration: none;
        }

        .card-actions img {
            width: 24px;
            height: 24px;
            transition: transform 0.2s;
        }

        .card-actions img:hover {
            transform: scale(1.1);
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .cards-container {
                grid-template-columns: 1fr;
                padding: 10px;
            }
        }
    </style>
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
      <a href='emprestimo_cadastrar1.php' class='link'><img src='./imagens/livro_emprestar.png'><small>Emprestar</small></a>";

// Search interface
echo "<select id='searchType' style='width: 180px; margin-right: 10px'>
        <option value='nome_usuario'>Usuário</option>
        <option value='nome_livro'>Livro</option>
      </select>";
echo "<input type='text' id='searchBox' placeholder='Digite para buscar...' />";

echo "<hr size=5 />";
echo "</div>";
?>

<main>
<div id="emprestimoList">
    <?php
    include ("conecta.php");

    // Initial query to show all emprestimos
    $query = mysqli_query($conexao, "SELECT * FROM emprestimos ORDER BY id DESC") or die(mysqli_error($conexao));
    if (mysqli_num_rows($query) > 0) {
        echo "<div class='cards-container'>";
        
        while ($aux = mysqli_fetch_array($query)) {
            // Formatar datas
            $data_emp = date('d/m/Y', strtotime($aux["data_emprestimo"]));
            $data_dev = $aux["data_devolucao"] ? date('d/m/Y', strtotime($aux["data_devolucao"])) : "Não devolvido";
            
            // Definir classe de status
            $status_lower = strtolower($aux["status_devolucao"]);
            $status_class = "status-" . str_replace("ç", "c", $status_lower);
            $card_class = "emprestimo-card " . $status_class;
            
            echo "<div class='$card_class'>";
            
            // Header do card
            echo "<div class='card-header'>";
            echo "<span class='card-id'>#" . $aux["id"] . "</span>";
            echo "<span class='card-status $status_class'>" . $aux["status_devolucao"] . "</span>";
            echo "</div>";
            
            // Informações principais
            echo "<div class='card-info'>";
            
            echo "<div class='card-info-row'>";
            echo "<span class='card-info-label'><i class='fas fa-user'></i> Usuário:</span>";
            echo "<span class='card-info-value card-usuario'>" . $aux["nome_usuario"] . "</span>";
            echo "</div>";
            
            echo "<div class='card-info-row'>";
            echo "<span class='card-info-label'><i class='fas fa-book'></i> Livro:</span>";
            echo "<span class='card-info-value card-livro'>" . $aux["nome_livro"] . "</span>";
            echo "</div>";
            
            echo "<div class='card-info-row'>";
            echo "<span class='card-info-label'><i class='fas fa-calendar-plus'></i> Empréstimo:</span>";
            echo "<span class='card-info-value'>" . $data_emp . "</span>";
            echo "</div>";
            
            echo "<div class='card-info-row'>";
            echo "<span class='card-info-label'><i class='fas fa-calendar-check'></i> Devolução:</span>";
            echo "<span class='card-info-value'>" . $data_dev . "</span>";
            echo "</div>";
            
            echo "</div>";
            
            // Ações
            echo "<div class='card-actions'>";
            echo "<a href='emprestimo_consultar.php?codigo=" . $aux['id'] . "' title='Consultar'><img src='./imagens/livro_consultar.png'></a>";
            echo "<a href='emprestimo_editar1.php?codigo=" . $aux['id'] . "' title='Editar'><img src='./imagens/livro_editar.png'></a>";
            echo "<a href='javascript:void(0)' onclick='confirmarExclusao(" . $aux['id'] . ")' title='Excluir'><img src='./imagens/livro_excluir.png'></a>";
            echo "</div>";
            
            echo "</div>"; // Fecha card
        }
        
        echo "</div>"; // Fecha container
    } else {
        echo "<div class='no-results'>Nenhum empréstimo encontrado.</div>";
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
            url: 'buscar_emprestimo.php',
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