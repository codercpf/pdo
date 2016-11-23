<?php 

//prepare()预处理，配合execute()，执行SQL操作

//fetch()方法得到结果集中的一条记录
//fetchAll()返回所有结果，是一个二维数组

try {
	$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");

	//SQL语句必须为单引号
	$sql = 'select * from user';
	$stmt = $pdo->prepare($sql);		
	$res = $stmt->execute();
/*
	//循环取出所有记录
	if ($res) {
		while ($row = $stmt->fetch()) {
			print_r($row);
			echo "<hr>";
		}
	}
*/

	$rows=$stmt->fetchAll();
	print_r($rows);

} catch (PDOException $e) {
	echo $e->getMessage();
}