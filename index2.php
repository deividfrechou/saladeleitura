<?PHP 
include ("conecta.php");
// as variáveis login e senha recebem os dados digitados na página anterior  
$login = $_POST['login']; 
$senha = $_POST['senha']; 
  
//seleciona a tabela e executa a consulta
$query = "select * from usuarios where email_user = '$login' and senha_user = '$senha'";
$res=mysqli_query($conexao,$query);

//conta o numero de linhas ou registros
$total=mysqli_num_rows($res);
 
if ($total==0) {
   session_start();
   header("Location: erro_login.php");
 }else{	 
     $vquery = mysqli_query($conexao, "select * from usuarios where email_user = '$login'") or die(mysqli_error($cx));
	 $linha=mysqli_fetch_array($vquery);
	 //setcookie("logado",$linha['id'], (time() + (5 * 24 * 3600))); 
	 if (session_status() !== PHP_SESSION_ACTIVE) {
       session_start();
      }
      //Definindo valores na sessão:
      $_SESSION['logado'] = $linha['id'];
      $_SESSION['nivel'] = $linha['nivel_user'];
      $_SESSION['ativo'] = $linha['ativo_user'];
      
     if ($_SESSION['nivel']=="A" && $_SESSION['ativo']=="S") {
        header("Location: inicio_aluno.php");
     } elseif($_SESSION['nivel']=="U" && $_SESSION['ativo']=="S"){
        header("Location: inicio.php");
     } elseif($_SESSION['nivel']=="G" && $_SESSION['ativo']=="S"){
        header("Location: inicio.php");
     } else{
      header("Location: erro_sistema.php");
     }
  }
mysql_close($conexao);
?>