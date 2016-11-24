<?php 

// 使用占位符，删除操作

	header('content-type:text/html;charset=utf-8');

	try {		
		$pdo=new PDO("mysql:host=localhost;dbname=imooc","root","root");
		$sql = "delete from user where id< :id";
		$stmt = $pdo->prepare($sql);

		$stmt->bindParam(":id", $id);
		$id = 10;

		echo $sql."<br/>";

		$stmt->execute();
		echo $stmt->rowCount();

	} catch (PDOException $e) {
		echo $e->getMessage();
	}