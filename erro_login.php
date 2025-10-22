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
    <script src="./scripts/codigo_site.js" defer></script>
</head>

<?php
echo "<center>";
echo "<div class='aviso'>";
echo "<img src='./imagens/alerta_erro.png'>";
echo "<h2>Algo deu errado.</h2>";
echo "<p>Sua senha não confere com o banco de dados<br>cadastre-se ou verifique com administrador</p>";
echo "<a href='index.php' class='link'>Tente Novamente</a>";
echo "</div>";
echo "</center>";

?>

</body>
</html>