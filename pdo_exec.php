<?php 

//exec()执行一条sql语句并返回其受影响的行数；如果没有受影响的记录，返回0

//exec对查询语句select没有作用，SQL语句必须为单引号

try {
	$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");

	$sql = <<<EOF
		CREATE TABLE IF NOT EXISTS user(
			id INT UNSIGNED AUTO_INCREMENT KEY,
			username VARCHAR(20) NOT NULL UNIQUE,
			passwd CHAR(32) NOT NULL,
			email VARCHAR(30) NOT NULL
		);
EOF;
	$res = $pdo->exec($sql);
	var_dump($res);
	//SQL语句必须为单引号
	$sql = 'insert user(username,passwd,email) values("chang","'.md5('chang').'","87700@qq.com")';
	$res = $pdo->exec($sql);
	var_dump($res);
} catch (PDOException $e) {
	echo $e->getMessage();
}