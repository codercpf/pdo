<?php 

// 通过pdo的prepare()预处理语句，防止SQL注入

	header('content-type:text/html;charset=utf-8');
	$username = $_POST['username'];
	$passwd = $_POST['passwd'];

	try {		
		$pdo=new PDO("mysql:host=localhost;dbname=imooc","root","root");

		//预处理语句用占位符代替变量，有两种形式：一个命名参数、一个是通用占位符 ?

	/*
		// 1、命名参数形式的占位符
		$sql = "select * from user where username=:username and passwd=:passwd";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(":username"=>$username, ":passwd"=>$passwd));
	*/

		// 2、通用占位符 ？形式
		$sql = "select * from user where username=? and passwd=?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array($username, $passwd));

		echo $stmt->rowCount();


	} catch (PDOException $e) {
		echo $e->getMessage();
	}