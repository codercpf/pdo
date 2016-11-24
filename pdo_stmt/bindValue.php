<?php 

// bindValue()将一个值绑定到一个参数
// 当有很多个参数，其中一个参数是固定值（可理解为常量）时，将这个值绑定到该参数

//使用bindValue()时，要先赋值，再使用

	header('content-type:text/html;charset=utf-8');

	try {		
		$pdo=new PDO("mysql:host=localhost;dbname=imooc","root","root");
		$sql = "insert user(username,passwd,email) values(?,?,?)";
		$stmt=$pdo->prepare($sql);

		//给变量赋值					//	先赋值，再使用
		$username="abc2";
		$passwd="abc2";

		$stmt->bindValue(1,$username);
		$stmt->bindValue(2,$passwd);
		//邮箱email值固定为abc@abc.com，可将他绑定到第三个参数
		$stmt->bindValue(3,'abc@abc.com');		

		$stmt->execute();
		echo $stmt->rowCount()."<br/>";

		//插入多个记录

		//给变量赋值					//	先赋值，再使用
		$username="abc3";
		$passwd="abc3";

		$stmt->bindValue(1,$username);
		$stmt->bindValue(2,$passwd);

		$stmt->execute();
		echo $stmt->rowCount()."<br/>";


	} catch (PDOException $e) {
		echo $e->getMessage();
	}