<?php 

//fetchColumn() 从结果集中的下一行 返回单独的一列；索引从0开始
//该函数无法返回同一行的另一列；因每执行一次，指针下移一位


	try {
		$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");
		$sql = "select username, passwd, email from user";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		echo $stmt->fetchColumn(0);  //返回第一行的第一列，单个数据元素
		echo "<br/>";
		echo $stmt->fetchColumn(1);
		//这里返回的是 第二行的第二列；而不是第一行的第二列；因上面执行过后，指针下移

		echo "<br/>";
		echo $stmt->fetchColumn(2);	//返回第三行的第三列
		
	} catch (PDOException $e) {
		echo $e->getMessage();		
	}