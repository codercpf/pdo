<?php 

// 通过命名参数使用bindValue()

// 当有很多个参数，其中一个参数是固定值（可理解为常量）时，将这个值绑定到该参数

//使用bindValue()时，要先赋值，再使用

	header('content-type:text/html;charset=utf-8');

	try {		
		$pdo=new PDO("mysql:host=localhost;dbname=imooc","root","root");
		$sql = "insert user(username,passwd,email) values(:username,:passwd,:email)";
		$stmt=$pdo->prepare($sql);

		//给变量赋值					//	先赋值，再使用
		$username="chang";
		$passwd="chang";

		$stmt->bindValue(':username',$username);
		$stmt->bindValue(':passwd',$passwd);
		//邮箱email值固定为877040117@qq.com，可将他绑定到第三个参数
		$stmt->bindValue(':email','877040117@qq.com');		

		$stmt->execute();
		echo $stmt->rowCount()."<br/>";

		//插入多个记录

		//给变量赋值					//	先赋值，再使用
		$username="chang02";
		$passwd="chang02";

		$stmt->bindValue(':username',$username);
		$stmt->bindValue(':passwd',$passwd);

		$stmt->execute();
		echo $stmt->rowCount()."<br/>";


	} catch (PDOException $e) {
		echo $e->getMessage();
	}