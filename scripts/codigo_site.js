function abrirNovaPagina(url, largura, altura) {
    // Calcula o centro da tela
    const esquerda = (screen.width - largura) / 5;
    const topo = (screen.height - altura) / 2;

    // Abre a nova janela com as dimensões especificadas
    window.open(
        url,
        "_blank",
        `width=${largura},height=${altura},top=${topo},left=${esquerda},resizable=yes,scrollbars=yes`
    );
}
