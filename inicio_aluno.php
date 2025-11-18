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

<style>
    /* Estilos específicos para a área de livros do aluno */
    .livros-container {
        width: 100%;
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 15px;
    }

    .livros-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .livros-header h1 {
        color: #2c3e50;
        font-size: 28px;
        margin: 0;
    }

    .filtros-livros {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-filtro {
        padding: 10px 20px;
        border: 2px solid #3498db;
        background-color: white;
        color: #3498db;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 600;
    }

    .btn-filtro:hover {
        background-color: #3498db;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    }

    .btn-filtro.ativo {
        background-color: #3498db;
        color: white;
    }

    .livros-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .livro-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border-left: 4px solid #3498db;
    }

    .livro-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    .livro-card.emprestado {
        border-left-color: #27ae60;
    }

    .livro-card.devolvido {
        border-left-color: #95a5a6;
        opacity: 0.85;
    }

    .livro-card.atrasado {
        border-left-color: #e74c3c;
    }

    .livro-status {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .status-emprestado {
        background-color: #d4edda;
        color: #155724;
    }

    .status-devolvido {
        background-color: #e2e3e5;
        color: #383d41;
    }

    .status-atrasado {
        background-color: #f8d7da;
        color: #721c24;
    }

    .livro-titulo {
        font-size: 18px;
        font-weight: bold;
        color: #2c3e50;
        margin: 10px 0;
    }

    .livro-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 15px;
    }

    .info-item {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #555;
    }

    .info-item i {
        margin-right: 8px;
        width: 20px;
        color: #3498db;
    }

    .livro-codigo {
        font-size: 12px;
        color: #7f8c8d;
        margin-bottom: 5px;
    }

    .mensagem-vazia {
        text-align: center;
        padding: 60px 20px;
        color: #7f8c8d;
        font-size: 18px;
        grid-column: 1 / -1;
    }

    .mensagem-vazia i {
        font-size: 64px;
        color: #bdc3c7;
        margin-bottom: 20px;
        display: block;
    }

    /* Responsividade para tablets */
    @media (max-width: 768px) {
        .livros-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }

        .livros-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .livros-header h1 {
            font-size: 24px;
        }

        .filtros-livros {
            width: 100%;
            justify-content: center;
        }

        .btn-filtro {
            flex: 1;
            min-width: 100px;
        }
    }

    /* Responsividade para celulares */
    @media (max-width: 480px) {
        .livros-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .livros-header h1 {
            font-size: 20px;
        }

        .btn-filtro {
            padding: 8px 15px;
            font-size: 13px;
        }

        .livro-card {
            padding: 15px;
        }

        .livro-titulo {
            font-size: 16px;
        }

        .info-item {
            font-size: 13px;
        }
    }
</style>

<main>
<div class="formulario">    

<div class="livros-container">
    <div class="livros-header">
        <h1><img src='./imagens/livro_aluno.png'> Meus Livros</h1>
        <div class="filtros-livros">
            <button class="btn-filtro ativo" onclick="filtrarLivros('todos')">
                <i class="fas fa-list"></i> Todos
            </button>
            <button class="btn-filtro" onclick="filtrarLivros('emprestado')">
                <i class="fas fa-book-open"></i> Lendo
            </button>
            <button class="btn-filtro" onclick="filtrarLivros('devolvido')">
                <i class="fas fa-check-circle"></i> Devolvidos
            </button>
        </div>
    </div>

    <div class="livros-grid" id="livrosGrid">
        <?php
        // Consulta para pegar todos os livros do aluno (emprestados e devolvidos)
        $sql = "SELECT * FROM emprestimos WHERE user_id = $v_id ORDER BY data_emprestimo DESC";
        $result = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($aux = mysqli_fetch_array($result)) {
                $status = $aux['status_devolucao'];
                $classe_status = strtolower($status);
                
                // Verificar se está atrasado
                $data_devolucao = strtotime($aux['data_devolucao']);
                $hoje = strtotime(date('Y-m-d'));
                $atrasado = ($status == 'Emprestado' && $data_devolucao < $hoje);
                
                if ($atrasado) {
                    $classe_status = 'atrasado';
                    $status_display = 'Atrasado';
                } elseif ($status == 'Emprestado') {
                    $status_display = 'Lendo';
                } else {
                    $status_display = $status;
                }
                
                echo "<div class='livro-card {$classe_status}' data-status='{$classe_status}'>";
                echo "<div class='livro-codigo'>Código: #{$aux['livro_id']}</div>";
                echo "<span class='livro-status status-{$classe_status}'>";
                
                if ($atrasado) {
                    echo "<i class='fas fa-exclamation-triangle'></i> {$status_display}";
                } elseif ($status == 'Emprestado') {
                    echo "<i class='fas fa-book-open'></i> {$status_display}";
                } else {
                    echo "<i class='fas fa-check-circle'></i> {$status_display}";
                }
                
                echo "</span>";
                echo "<div class='livro-titulo'>{$aux['nome_livro']}</div>";
                echo "<div class='livro-info'>";
                echo "<div class='info-item'><i class='fas fa-calendar-plus'></i> Emprestado: " . date('d/m/Y', strtotime($aux['data_emprestimo'])) . "</div>";
                echo "<div class='info-item'><i class='fas fa-calendar-check'></i> Devolução: " . date('d/m/Y', strtotime($aux['data_devolucao'])) . "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class='mensagem-vazia'>";
            echo "<i class='fas fa-book'></i>";
            echo "<div>Você ainda não tem nenhum livro emprestado.</div>";
            echo "<div style='font-size: 14px; margin-top: 10px;'>Visite a biblioteca e escolha seu próximo livro!</div>";
            echo "</div>";
        }

        // Fecha a conexão com o banco
        mysqli_close($conexao);
        ?>
    </div>
</div>

</div>
</main>

<script>
    function filtrarLivros(filtro) {
        const cards = document.querySelectorAll('.livro-card');
        const botoes = document.querySelectorAll('.btn-filtro');
        const grid = document.getElementById('livrosGrid');
        
        // Atualizar botões ativos
        botoes.forEach(btn => btn.classList.remove('ativo'));
        event.target.closest('.btn-filtro').classList.add('ativo');
        
        // Filtrar cards
        let cardsVisiveis = 0;
        cards.forEach(card => {
            const status = card.getAttribute('data-status');
            if (filtro === 'todos') {
                card.style.display = 'block';
                cardsVisiveis++;
            } else if (filtro === 'emprestado' && (status === 'emprestado' || status === 'atrasado')) {
                card.style.display = 'block';
                cardsVisiveis++;
            } else if (filtro === 'devolvido' && status === 'devolvido') {
                card.style.display = 'block';
                cardsVisiveis++;
            } else {
                card.style.display = 'none';
            }
        });

        // Verificar se há cards visíveis
        if (cardsVisiveis === 0 && filtro !== 'todos') {
            let mensagem = '';
            if (filtro === 'emprestado') {
                mensagem = '<i class="fas fa-book"></i><div>Você não tem livros emprestados no momento.</div>';
            } else if (filtro === 'devolvido') {
                mensagem = '<i class="fas fa-book"></i><div>Você ainda não devolveu nenhum livro.</div>';
            }
            
            const mensagemDiv = document.createElement('div');
            mensagemDiv.className = 'mensagem-vazia';
            mensagemDiv.innerHTML = mensagem;
            grid.appendChild(mensagemDiv);
        } else {
            // Remove mensagens vazias anteriores
            const mensagensVazias = grid.querySelectorAll('.mensagem-vazia');
            mensagensVazias.forEach(msg => {
                if (msg.parentElement === grid && cards.length > 0) {
                    msg.remove();
                }
            });
        }
    }
</script>

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