
<?php
// este modelo usa o mysqli - mais rapido e pratico na  conexão
  
$database="localhost:3306"; // SERVIDOR E PORTA UTILIZADA   
$dbbanco="biblioteca"; // BASE DE DADOS 
$dbusuario="root"; // USUÁRIO DO MYSQL
$dbsenha=""; // SENHA DO MYSQL
 
// conexão com o banco de dados
$conexao=mysqli_connect($database,$dbusuario,$dbsenha,$dbbanco) or die("Error". mysqli_error($conexao));

?>