<?php 

// errorCode、errorInfo操作

try {
	$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");
	
	$sql = 'delete from user11 where id=4';
	$res = $pdo->exec($sql);

	// echo $res."条记录被影响";
	// var_dump($res);
	if ($res === false) {
		echo $pdo->errorCode();	//输出SQLSTATE
		echo "<br/>";
		var_dump ($pdo->errorInfo());  
		//输出错误信息，包含3个单元
		//输出SQLSTATE、错误码、错误信息
	}
	
} catch (PDOException $e) {
	echo $e->getMessage();
}