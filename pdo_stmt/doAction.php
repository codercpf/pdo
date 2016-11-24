<?php 
// SQL注入：username输入 ' or 1=1 #
// 以单引号 ' 开头，# 结尾

// 使用pdo的quote()方法过滤用户输入，防止SQL注入

	header('content-type:text/html;charset=utf-8');
	$username = $_POST['username'];
	$passwd = $_POST['passwd'];

	try {		
		$pdo=new PDO("mysql:host=localhost;dbname=imooc","root","root");

		// echo $pdo->quote($username)."<br/>";

		// $sql = "select * from user where username='{$username}' and passwd='{$passwd}'";
		// echo $sql."<br/>";

		// 通过quote()方法，返回带引号的字符串，过滤字符串中的特殊字符；此时不需要在sql语句中对变量加单引号
		$username=$pdo->quote($username);
		$sql = "select * from user where username={$username} and passwd='{$passwd}'";
		echo $sql."<br/>";

		$stmt = $pdo->query($sql);

		//PDOStatement对象的方法：rowCount()
		//对于select操作，返回结果集中记录的条数；
		//对于insert、update、delete操作，返回受影响的记录的条数
		echo $stmt->rowCount();

	} catch (PDOException $e) {
		echo $e->getMessage();
	}