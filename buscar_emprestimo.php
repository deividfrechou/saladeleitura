<?php
include("conecta.php");

$searchQuery = isset($_GET['query']) ? mysqli_real_escape_string($conexao, $_GET['query']) : '';
$searchType = isset($_GET['type']) ? mysqli_real_escape_string($conexao, $_GET['type']) : 'nome_usuario';

// Validar tipo de busca
$allowedTypes = ['nome_usuario', 'nome_livro'];
if (!in_array($searchType, $allowedTypes)) {
    $searchType = 'nome_usuario';
}

// Construir query
if (!empty($searchQuery)) {
    $query = mysqli_query($conexao, "SELECT * FROM emprestimos WHERE $searchType LIKE '%$searchQuery%' ORDER BY id DESC") or die(mysqli_error($conexao));
} else {
    $query = mysqli_query($conexao, "SELECT * FROM emprestimos ORDER BY id DESC") or die(mysqli_error($conexao));
}

// Incluir o CSS dos cards inline para funcionar no AJAX
echo "<style>
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
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .card-id {
        background: #007bff;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 14px;
    }

    .card-status {
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 13px;
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
        margin: 15px 0;
    }

    .card-info-row {
        display: flex;
        margin-bottom: 10px;
        align-items: flex-start;
    }

    .card-info-label {
        font-weight: bold;
        color: #555;
        min-width: 90px;
        font-size: 14px;
    }

    .card-info-value {
        color: #333;
        font-size: 14px;
        flex: 1;
    }

    .card-usuario {
        font-size: 16px;
        color: #007bff;
        font-weight: bold;
    }

    .card-livro {
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }

    .card-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
    }

    .card-actions a {
        text-decoration: none;
    }

    .card-actions img {
        width: 30px;
        height: 30px;
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
</style>";

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
    echo "<div class='no-results'>Nenhum empréstimo encontrado para a busca.</div>";
}
?>