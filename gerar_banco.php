<?php 
	// cria e verifica uma conexao com servidor, para criar o
	//banco de dados
	$link = new mysqli('localhost', 'root', '', '');
	$link->set_charset('utf8');

	if ($link)
	{
		echo "A conexao foi realizada com sucesso";
	}
	else
	{
		die('Connect Error (' . mysqli_connecterrno() . ')' .
			mysqli_connect_error());
	}

	//Criando o banco de dados caso nao exista
	$nomeBanco = "biblioteca";

	$query_create_schema = "CREATE SCHEMA IF NOT EXISTS $nomeBanco"
	or die ("Erro na criação do banco.. " . $link->connection_error);
	$result_create_schema = $link->query($query_create_schema);

	if ( $link->query($query_create_schema) == TRUE )
	{
		echo "<p>O banco de dados chamado biblioteca foi criado corretamente </p>";
	}
	else
	{
		echo "<p>nao criou banco de dados</p>";
	}

	//Criando a tabela de usuários
	mysqli_select_db($link , $nomeBanco);

	$query_create_table = "CREATE TABLE usuarios (
		id INT PRIMARY KEY AUTO_INCREMENT,
		ativo_user varchar(2) NOT NULL,
		nivel_user varchar(10) NOT NULL,
		nome_user varchar(100) NOT NULL,
		email_user varchar(100) NOT NULL,
		telefone_user varchar(20),
		senha_user varchar(250) NOT NULL,
		termos_user varchar(2) NOT NULL
	   )"
	   or die("Erro na criação da tabela de banco de dados ... ". $link->connect_error);
	   $result_create_table = $link->query($query_create_table);

	   $sql = "INSERT INTO `usuarios`(`id`, `ativo_user`, `nivel_user`, `nome_user`, `email_user`, `telefone_user`, `senha_user`) VALUES (null,'S','G','Deivid Pereira Frechou','deividfrechou@hotmail.com','51989505145','educar360')";
				  
	   if (mysqli_query($link, $sql)) {
			 echo "Tabela de Usuarios Criada corretamente...<br>";
	   } else {
			 echo "Error: " . $sql . "<br>" . mysqli_error($link);
	   }
	   
	/*criando a tabela de Livros*/
	mysqli_select_db($link , $nomeBanco);
		
       $query_create_table = "CREATE TABLE livros (
       id INT PRIMARY KEY AUTO_INCREMENT,
       titulo_livro VARCHAR(100) NOT NULL,
	   genero_livro VARCHAR(100) NOT NULL,
       autor_livro VARCHAR(150) NOT NULL,
       editora_livro VARCHAR(100),
       edicao_livro VARCHAR(50),
	   isbn_livro VARCHAR(20) UNIQUE,
       publicacao_livro INT,
       data_cadastro_livro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )" 
	or die("Error in the create table ... " . $link->connect_error);
	$result_create_table = $link->query($query_create_table);
	
	   echo "Tabela de Livros Criada corretamente...<br>";
	

/*criando a tabela de emprestimos*/
	mysqli_select_db($link , $nomeBanco);
		
       $query_create_table = "CREATE TABLE emprestimos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        nome_usuario VARCHAR(100) NOT NULL,
        livro_id INT NOT NULL,
		nome_livro VARCHAR(100) NOT NULL,
        data_emprestimo date,
        data_devolucao date,
        status_devolucao varchar(20) NOT NULL
        /*FOREIGN KEY (user_id) REFERENCES usuarios(id),
        FOREIGN KEY (livro_id) REFERENCES livros(id)*/
        )" 
	    or die("Error in the create table ... " . $link->connect_error);
	    $result_create_table = $link->query($query_create_table);
	   	   
		   echo "Tabela de emprestimos Criada corretamente...<br>";
        

	//Se a tabela for criada corretamente ou ja existir
	//entao sera encaminhado para a pagina index.php
	// caso contrario a um erro na criacao da tabela
	
	if($result_create_table == TRUE)
	{
        echo "<br>Parabéns, Deu tudo certo - Prosiga para página inicial";
        echo "<br><br><a href='index.php'>Clique Aqui !!!</a>";
	}
	else
	{
		echo "<p>nao criou a tabela</p>";
	}

?>