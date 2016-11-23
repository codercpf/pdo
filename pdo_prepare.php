<?php 

//prepare()预处理，配合execute()，执行SQL操作
//返回PDOStatement对象

try {
	$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");

	//SQL语句必须为单引号
	$sql = 'select * from user where username="abc"';

	//prepare($sql)准备SQL语句
	$stmt = $pdo->prepare($sql);
		// var_dump($stmt);

	//execute()执行预处理语句
	$res = $stmt->execute();

	//通过fetch()方法得到结果集中的一条记录
	$row = $stmt->fetch();
	var_dump($row);
	
} catch (PDOException $e) {
	echo $e->getMessage();
}