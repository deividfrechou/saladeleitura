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

<main>
<div class="formulario">    
<h1>Livros do aluno</h1>

<?php

// Consulta para pegar o livro emprestado pelo aluno
$sql = "SELECT * FROM emprestimos WHERE user_id = $v_id AND status_devolucao = 'Emprestado'";

// Executa a consulta
$result = mysqli_query($conexao, $sql);

  if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>
                <th>Codigo</th>
                <th>Livro</th>
                <th>Data emprestimo</th>
                <th>Data Devolução</th>
                <th>Status</th>                
              </tr>";
        while ($aux = mysqli_fetch_array($result)) {
            // Exibe os dados do livro emprestado
    echo "<div><strong>Título do Livro:</strong> " . $aux['livro_id'] . "</div>";
    echo "<div><strong>Título do Livro:</strong> " . $aux['nome_livro'] . "</div>";
    echo "<div><strong>Título do Livro:</strong> " . $aux['data_emprestimo'] . "</div>";
    echo "<div><strong>Título do Livro:</strong> " . $aux['data_devolucao'] . "</div>";
    echo "<div><strong>Título do Livro:</strong> " . $aux['status_devolucao'] . "</div>";
        }
        echo "</table></div>";
    } else {
        echo "<div>Você ainda não tem nenhum livro emprestado no momento.</div>";
    }

    // Fecha a conexão com o banco
    mysqli_close($conexao);
  ?>
</main>

<footer>
   <div class="cookie-consent" id="cookieConsent">
    <img src="./imagens/cookie.png" width="70" height="70" align="left">
    <p>Usamos cookies para melhorar sua experiência e oferecer conteúdo personalizado.
        Ao continuar navegando, você concorda com nossa política de cookies.
        Clique em 'Aceitar' para aproveitar o melhor do nosso site!.</p>
        <button onclick="abrirNovaPagina('politica_de_cookies.htm', 500, 400)">Ler mais</button>
        <button onclick='acceptCookies()'>Aceitar</button>
</div>
  </footer>

<script>
        function acceptCookies() {
            // Hide the cookie consent message
            document.getElementById('cookieConsent').style.display = 'none';

            // Store the consent in localStorage
            localStorage.setItem('cookiesAccepted', 'true');
        }

        // Check if cookies were already accepted
        window.onload = function() {
            if (localStorage.getItem('cookiesAccepted') === 'true') {
                document.getElementById('cookieConsent').style.display = 'none';
            }
        };

    </script>

</body>
</html>