<?php
include("conecta.php");

if (isset($_GET['query'])) {
    $search = mysqli_real_escape_string($conexao, $_GET['query']);

    // Consulta com base no filtro de busca
    $query = mysqli_query($conexao, "SELECT * FROM usuarios WHERE nome_user LIKE '%$search%'") or die(mysqli_error($conexao));

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
                        <a href='usuario_excluir.php?codigo=" . $aux['id'] . "'><img src='./imagens/usuario_excluir.png'></a>
                        <a href='usuario_reset.php?codigo=" . $aux['id'] . "'><img src='./imagens/usuario_reset.png'></a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhum resultado encontrado.</p>";
    }
}
?>

