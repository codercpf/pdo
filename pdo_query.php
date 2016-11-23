<?php 

//$pdo->query($sql),执行SQL语句（包含增删改查），返回PDOStatement对象

try {
	$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");

	//SQL语句必须为单引号
	$sql = 'select * from user';
	$stmt = $pdo->query($sql);

	// var_dump($stmt);
	//返回的是一个二维数组，可遍历
	foreach ($stmt as $row) {
		// print_r($row);
		// echo "<br/>";
		echo '编号：'.$row['id']."<br/>";
		echo '用户名：'.$row['username']."<br/>";
		echo '邮箱：'.$row['email']."<br/>";
		echo "<hr>";
	}
	
} catch (PDOException $e) {
	echo $e->getMessage();
}