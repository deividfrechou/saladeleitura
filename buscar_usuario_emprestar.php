<?php
include("conecta.php");

if (isset($_GET['query'])) {
    $search = mysqli_real_escape_string($conexao, $_GET['query']);

    $query = mysqli_query($conexao, "SELECT * FROM usuarios WHERE nome_user LIKE '%$search%'") or die(mysqli_error($conexao));

    if (mysqli_num_rows($query) > 0) {
        while ($aux = mysqli_fetch_array($query)) {
            echo "<div data-id='" . $aux["id"] . "'>" . $aux["nome_user"] . "</div>";
        }
    } else {
        echo "<div>Nenhum usuário encontrado.</div>";
    }
}
?>
