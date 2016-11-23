<?php 
	
	try {
		$dsn = "mysql:host=localhost;dbname=imooc";
		$username = "root";
		$passwd = "root";
		$pdo = new PDO($dsn, $username, $passwd);

		echo '自动提交：'. $pdo->getAttribute(PDO::ATTR_AUTOCOMMIT);
		echo "<br/>";
		echo '默认的错误处理模式：'.$pdo->getAttribute(PDO::ATTR_ERRMODE);
		echo "<br/>";	

		// 通过setAttribute()设置PDO属性
		$pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
		echo '自动提交：'. $pdo->getAttribute(PDO::ATTR_AUTOCOMMIT);
		echo "<br/>";

	} catch (PDOException $e) {
		echo $e->getMessage();		
	}