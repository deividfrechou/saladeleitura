<?php
include("conecta.php");

$searchQuery = isset($_GET['query']) ? mysqli_real_escape_string($conexao, $_GET['query']) : '';
$searchType = isset($_GET['type']) ? mysqli_real_escape_string($conexao, $_GET['type']) : 'nome_usuario';

// Validar tipo de busca
$allowedTypes = ['nome_usuario', 'nome_livro'];
if (!in_array($searchType, $allowedTypes)) {
    $searchType = 'nome_usuario';
}

// Construir query - Buscar APENAS empréstimos DEVOLVIDOS
if (!empty($searchQuery)) {
    $query = mysqli_query($conexao, "SELECT * FROM emprestimos WHERE status_devolucao = 'DEVOLVIDO' AND $searchType LIKE '%$searchQuery%' ORDER BY id DESC") or die(mysqli_error($conexao));
} else {
    $query = mysqli_query($conexao, "SELECT * FROM emprestimos WHERE status_devolucao = 'DEVOLVIDO' ORDER BY id DESC") or die(mysqli_error($conexao));
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
        justify-content: center;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #f0f0f0;
    }

    .card-actions a {
        text-decoration: none;
    }

    .card-actions img {
        width: 30px;
        height: 34px;
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
        $data_dev = $aux["data_devolucao"] ? date('d/m/Y', strtotime($aux["data_devolucao"])) : "N/A";
        
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
        
        echo "</div>"; // Fecha card
    }
    
    echo "</div>"; // Fecha container
} else {
    echo "<div class='no-results'>Nenhum livro devolvido encontrado para a busca.</div>";
}
?>