<?php 
/*
*  PDO::ERRMODE_SLIENT: 默认模式，静默模式
*  PDO::ERRMODE_WARNING: 警告模式
*  PDO::ERRMODE_EXCEPTION: 异常模式
*/

	try {
		$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");

		// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);	//设置错误模式为警告模式

		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//设置错误模式为异常模式


		$sql = 'select * from noneTable';	//查询不存在的表，默认没有任何错误信息，调用errorInfo()输出错误信息
		$pdo->query($sql);

	/*
		//只有静默模式，需要明确调用errorCode()和errorInfo()
		echo $pdo->errorCode();
		echo "<br/>";
		print_r($pdo->errorInfo());
	*/

	} catch (PDOException $e) {
		echo $e->getMessage();
	}