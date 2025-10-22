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
    <script src="./scripts/codigo_site.js" defer></script>
    <style>
        .error {
          color:red;
          font-size: 18px;
          padding: 20px
        }
    </style>
</head>


<body bgcolor="#CCCCCC">

<center>
<div class="formulario">    
   <h2>Cadastro de Usuários</h2>
   <span id="errorMessage" class="error"></span> 
   <form action="usuario_cadastro_me2.php" id="registrationForm" method="post">
     <div class="form-group">
       <label for="nome">Nome:</label>
       <input type="text" name="nome" id="nome" required="required" placeholder="Seu nome completo">
     </div>
     <div class="form-group">
       <label for="telefone">Telefone:</label>
       <input type="text" name="telefone" id="telefone" pattern="[0-9]+$" placeholder="51999999999 - Telefone">
     </div>
     <div class="form-group">
       <label for="login">E-mail:</label>
       <input type="text" name="login" id="login" required="required" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" placeholder="seu e-mail">
     </div>
     <div class="form-group">
       <label for="senha">Senha:</label>
       <input type="password" name="senha" id="senha" required="required" placeholder="Sua senha">
     </div>
     <div class="form-group">
       <label for="repete">Confirmar Senha:</label>
       <input type="password" name="repete" id="repete" required="required" placeholder="Repita a senha">
     </div>
     <div>
       <input type="checkbox" id="termos" name="termos" required>
       <span>Li e aceito a <a href="javascript:abrirNovaPagina('politica_de_privacidade.htm', 600, 400)">Politica de Privacidade</a>, os <a href="javascript:abrirNovaPagina('termos_de_uso.htm', 600, 400)">Termos de Uso</a> e Concordo com o <a href="javascript:abrirNovaPagina('contrato_operacao_dados.htm', 600, 400)">Contrato de Operação de Dados</a></span>
     </div>
     <div class="form-actions">
       <a href="index.php"><button type="button"><i class="fas fa-arrow-alt-circle-left fa-1x"></i> Voltar</button></a> 
       <button type="submit"><i class="fas fa-save"></i> Cadastrar</button>
     </div>
   </form>
</div>
</center>

<script type="text/javascript" language="javascript">

        const form = document.getElementById('registrationForm');
        const senha = document.getElementById('senha');
        const repete = document.getElementById('repete');
        const errorMessage = document.getElementById('errorMessage');

        form.addEventListener('submit', function(event) {
            // Reseta a mensagem de erro
            errorMessage.textContent = '';

            // Verifica se as senhas são iguais
            if (senha.value !== repete.value) {
                event.preventDefault(); // Impede o envio do formulário
                errorMessage.textContent = 'As senhas não coincidem.';
                senha.focus(); // Coloca o foco no campo de confirmação
            }
        });

function valida_form (){
if(document.getElementById("nome").value.length < 3){
alert('Por favor, preencha o campo nome');
document.getElementById("nome").focus();
return false
}
return true
}

</script>  
 </body>
</html>