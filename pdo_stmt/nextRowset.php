<?php 

//nextRowset()函数，将指针指向下一个结果集；当返回结果有多个结果集时使用
//通过循环也可以实现
	
	try {
		$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");
		$sql = 'call test1()';
		$stmt = $pdo->query($sql);

		$rowset = $stmt->fetchAll(PDO::FETCH_ASSOC);
		print_r($rowset);
		echo "<hr/>";

		$stmt->nextRowset();	//调用nextRowset()，将指针指向下一个结果集，即存储过程的结果集
		
		$rowset = $stmt->fetchAll(PDO::FETCH_ASSOC);
		print_r($rowset);		

	} catch (PDOException $e) {
		echo $e->getMessage();
	}