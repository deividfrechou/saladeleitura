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
    <link rel="icon" href="logo.png">
</head>

<body>

<?php
function gerarMenu() {

    $menu = "";

    switch ($_SESSION['nivel']) {
        case 'A':
            $menu = "
            
            <nav class='menu'>
               <a href='inicio_aluno.php'><img src='./imagens/menu_inicio.png'><span>Início</span></a>
               <a href='usuario_editar_me1.php'><img src='./imagens/menu_meusdados.png'><span>Meus Dados</span></a>
               <a href='logof.php'><img src='./imagens/menu_sair.png'><span>Sair</span></a>
	        </nav>
            ";
            break;

        case 'U':
            $menu = "
            <nav class='menu'>
	           <a href='inicio.php'><img src='./imagens/menu_inicio.png'><span>Início</span></a>
               <a href='livros.php'><img src='./imagens/menu_livros.png'><span>Livros</span></a>        
               <a href='usuario_editar_me1.php'><img src='./imagens/menu_meusdados.png'><span>Meus Dados</span></a>
               <a href='logof.php'><img src='./imagens/menu_sair.png'><span>Sair</span></a>
	        </nav>
            ";
            break;

        case 'G':
            $menu = "
            <nav class='menu'>
	           <a href='inicio.php'><img src='./imagens/menu_inicio.png'><span>Início</span></a>
               <a href='livros.php'><img src='./imagens/menu_livros.png'><span>Livros</span></a>
               <a href='usuarios.php'><img src='./imagens/menu_usuarios.png'><span>Usuários</span></a>    
               <a href='usuario_editar_me1.php'><img src='./imagens/menu_meusdados.png'><span>Meus Dados</span></a>
               <a href='logof.php'><img src='./imagens/menu_sair.png'><span>Sair</span></a>               
	        </nav>";
            break;

        default:
            $menu = "<p>Acesso inválido. <a href='logout.php'>Sair</a></p>";
            break;
    }

    return $menu;
}
?>

</body>
</html>