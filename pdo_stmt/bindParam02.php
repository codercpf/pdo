<?php 

// 使用问号 ？ 参数的形式，使用占位符

	header('content-type:text/html;charset=utf-8');

	try {		
		$pdo=new PDO("mysql:host=localhost;dbname=imooc","root","root");
		$sql = "insert user(username,passwd,email) values(?,?,?)";
		$stmt = $pdo->prepare($sql);

		//问号绑定参数，赋值时，按照索引顺序赋值，索引从1开始
		$stmt->bindParam(1,$username);
		$stmt->bindParam(2,$passwd);
		$stmt->bindParam(3,$email);

		//给参数赋值
		$username = 'test';
		$passwd = 'test';
		$email = 'test@test.com';
		$stmt->execute();		

		echo $stmt->rowCount();

	} catch (PDOException $e) {
		echo $e->getMessage();
	}