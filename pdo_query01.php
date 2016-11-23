<?php 

//$pdo->query($sql),可以执行增、删、改、查操作

try {
	$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");

	//SQL语句必须为单引号
	$sql = 'insert user(username,passwd,email) values("abc01","abc01","abc01@qq.com")';
	$stmt = $pdo->query($sql);

	var_dump($stmt);
	
} catch (PDOException $e) {
	echo $e->getMessage();
}