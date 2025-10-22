<meta  http-equiv="Refresh" content="3;URL=index.php">
<?PHP 
include ("conecta.php");
// as variáveis login e senha recebem os dados digitados na página anterior 
$nome = $_POST['nome'];
$ativo = 'N'; 
$nivel = 'A'; 
$telefone = $_POST['telefone']; 
$email = $_POST['login'];
$senha = $_POST['senha'];
$termos = 'S';
  
//seleciona a tabela e executa a consulta
$query = "INSERT INTO usuarios(ativo_user,nivel_user,nome_user,telefone_user,email_user,senha_user,termos_user)
VALUES ('$ativo','$nivel','$nome','$telefone','$email','$senha','$termos')";

mysqli_query($conexao,$query);

?>