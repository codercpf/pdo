<?php 

// debugDumpParams()打印出带参数的、预处理SQL语句

	try {
		$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");
/*
		//问号占位符形式
		$sql = "insert user(username, passwd, email) values(?,?,?)";
		$stmt = $pdo->prepare($sql);

		$stmt->bindParam(1,$username,PDO::PARAM_STR);
		$stmt->bindParam(2,$passwd,PDO::PARAM_STR);
		$stmt->bindParam(3,$email,PDO::PARAM_STR);

		$username='testParam';
		$passwd = 'testParam';
		$email = 'testParam@imooc.com';

		$stmt->execute();

		$stmt->debugDumpParams();	//打印出预处理的SQL语句
*/

		//参数占位符形式
		$sql = "select * from user where username=:username and passwd=:passwd";
		$stmt=$pdo->prepare($sql);

		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':passwd',$passwd);

		$username='testParam';
		$passwd = 'testParam';
		$stmt->execute();

		$stmt->debugDumpParams();	//打印出预处理的SQL语句

	} catch (PDOException $e) {
		echo $e->getMessage();
	}