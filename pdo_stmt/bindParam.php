<?php 

// 绑定命名参数的形式，使用占位符

	header('content-type:text/html;charset=utf-8');

	try {		
		$pdo=new PDO("mysql:host=localhost;dbname=imooc","root","root");
		$sql = "insert user(username,passwd,email) values(:username,:passwd,:email)";
		$stmt = $pdo->prepare($sql);

		//绑定命名参数，第三个参数指定该参数的数据类型，默认为PDO::PARAM_STR，即字符串类型
		$stmt->bindParam(":username",$username,PDO::PARAM_STR);
		$stmt->bindParam(":passwd",$passwd,PDO::PARAM_STR);
		$stmt->bindParam(":email",$email);
		//给参数赋值
		$username = 'imooc3';
		$passwd = 'imooc3';
		$email = 'imooc3@imooc3.com';
		$stmt->execute();

		//当执行多次、插入多条记录时，把变量赋值修改即可
		$username = 'imooc4';
		$passwd = 'imooc4';
		$email = 'imooc4@imooc4.com';
		$stmt->execute();

		echo $stmt->rowCount();

	} catch (PDOException $e) {
		echo $e->getMessage();
	}