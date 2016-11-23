<?php 

//prepare()预处理，配合execute()，执行SQL操作

//fetch()方法得到结果集中的一条记录
//fetchAll()返回所有结果，是一个二维数组

//setFetchMode()设置默认获取数据的方式，关联或数组、对象，默认为FETCH_BOTH
//FETCH_ASSOC、FETCH_OBJ

try {
	$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");

	//SQL语句必须为单引号
	$sql = 'select * from user';
	$stmt = $pdo->prepare($sql);		
	$res = $stmt->execute();
/*
	//循环取出所有记录
	if ($res) {
		while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
			print_r($row);
			echo "<hr/>";
		}
	}
*/

/*
	//直接传参数
	$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
	print_r($rows);
	echo "<hr/>";
*/
	//通过setFetchMode()方法设置默认的获取数据的方式
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$rows=$stmt->fetchAll();
	print_r($rows);	

} catch (PDOException $e) {
	echo $e->getMessage();
}