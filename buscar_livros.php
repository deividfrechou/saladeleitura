<?php
include ("conecta.php");

$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
$searchType = isset($_GET['type']) ? $_GET['type'] : 'titulo_livro'; // Certifique-se que este nome está correto

// Sanitize inputs
$searchQuery = mysqli_real_escape_string($conexao, $searchQuery);
$searchType = mysqli_real_escape_string($conexao, $searchType);

// Base query
$sql = "SELECT * FROM livros WHERE 1=1";

// Add search condition if query is not empty
if (!empty($searchQuery)) {
    // For year, use exact match
    if ($searchType == 'publicacao_livro') {
        $sql .= " AND $searchType = '$searchQuery'";
    } else {
        // For other fields, use LIKE for partial matches
        $sql .= " AND $searchType LIKE '%$searchQuery%'";
    }
}

// Para debug
error_log("SQL Query: " . $sql);

$query = mysqli_query($conexao, $sql) or die(mysqli_error($conexao));

if (mysqli_num_rows($query) > 0) {
    echo "<table class='lista'>";
    echo "<tr>
            <th>ID</th>
            <th>Livro</th>
            <th>Autor</th>
            <th>Gênero</th>
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
<script>
function confirmarExclusao(id) {
    if (confirm('Deseja realmente excluir este livro?')) {
        window.location.href = 'livro_excluir.php?codigo=' + id;
    }
}
</script>
