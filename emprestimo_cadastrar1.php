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
// Conexão com o banco de dados
include("conecta.php");

// Obtém a data atual e calcula a data de devolução
$data_emprestimo = date("Y-m-d");
$data_devolucao = date("Y-m-d", strtotime("+7 days"));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimo de Livros</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .search-container {
            position: relative;
            width: 100%;
        }
        .dropdown-content {
            position: absolute;
            width: 100%;
            background: white;
            border: 1px solid #ccc;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }
        .dropdown-content div {
            padding: 10px;
            cursor: pointer;
        }
        .dropdown-content div:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="formulario">
    <h2>Empréstimo de Livros</h2>
    
    <form action="emprestimo_cadastrar2.php" method="POST">
        <!-- Busca de Usuário -->
        <label>Usuário:</label>
        <div class="search-container">
            <input type="text" id="searchUser" placeholder="Digite o nome do usuário..." autocomplete="off" required />
            <div id="userDropdown" class="dropdown-content"></div>
        </div>
        <input type="hidden" name="user_id" id="user_id">
        <input type="hidden" name="nome" id="nome">
        
        <!-- Busca de Livro -->
        <label>Livro:</label>
        <div class="search-container">
            <input type="text" id="searchBook" placeholder="Digite o título do livro..." autocomplete="off" required />
            <div id="bookDropdown" class="dropdown-content"></div>
        </div>
        <input type="hidden" name="livro_id" id="livro_id">
        <input type="hidden" name="livro" id="livro">
        
        <!-- Datas -->
        <?php 
        echo "<br>Data do emprestimo:".$data_emprestimo;
        echo "<br>Data de Devolução:".$data_devolucao;
        ?>
        <input type="hidden" name="data_emprestimo" value="<?php echo $data_emprestimo; ?>">
        <input type="hidden" name="data_devolucao" value="<?php echo $data_devolucao; ?>">
        <button type="submit">Emprestar</button>
    </form>

    </div>

    <!-- #####   ÁREA DE SCRIPTS ##### -->
    <script>
        $(document).ready(function() {
            // Busca de Usuário
            $('#searchUser').on('input', function() {
                let searchValue = $(this).val();
                if (searchValue.length > 0) {
                    $.ajax({
                        url: 'buscar_usuario_emprestar.php',
                        method: 'GET',
                        data: { query: searchValue },
                        success: function(data) {
                            $('#userDropdown').html(data).show();
                        }
                    });
                } else {
                    $('#userDropdown').hide();
                }
            });

            $(document).on('click', '#userDropdown div', function() {
                var userName = $(this).text();
                $('#searchUser').val(userName); // Define o nome no campo de busca
                $('#user_id').val($(this).data('id')); // Armazena o ID no campo oculto
                $('#nome').val(userName); // Preenche o campo do nome do usuário
                $('#userDropdown').hide(); // Esconde a lista
            });

            // Busca de Livro
            $('#searchBook').on('input', function() {
                let searchValue = $(this).val();
                if (searchValue.length > 0) {
                    $.ajax({
                        url: 'buscar_livro_emprestar.php',
                        method: 'GET',
                        data: { query: searchValue },
                        success: function(data) {
                            $('#bookDropdown').html(data).show();
                        }
                    });
                } else {
                    $('#bookDropdown').hide();
                }
            });

            $(document).on('click', '#bookDropdown div', function() {
                var bookTitle = $(this).text();
                $('#searchBook').val(bookTitle); // Define o título no campo de busca
                $('#livro_id').val($(this).data('id')); // Armazena o ID no campo oculto
                $('#livro').val(bookTitle); // Preenche o campo do título do livro
                $('#bookDropdown').hide(); // Esconde a lista
            });
        });
    </script>
</body>
</html>