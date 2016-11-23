<?php 

//exec更新、删除操作

try {
	$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");
	// $sql = 'update user set username="chang" where id=1';
	// $sql = 'delete from user11 where id=4';

	$sql = 'select * from user';
	$res = $pdo->exec($sql);

	// echo $res."条记录被影响";

	var_dump($res);
	
} catch (PDOException $e) {
	echo $e->getMessage();
}