<?php 
	
	try {
		$dsn = "mysql:host=localhost;dbname=imooc";
		$username = "root";
		$passwd = "root";
		$pdo = new PDO($dsn, $username, $passwd);

		$attrArr = array(
			'AUTOCOMMIT','ERRMODE','CASE','PERSISTENT','TIMEOUT',
			'SERVER_INFO','SERVER_VERSION','CLIENT_VERSION','CONNECTION_STATUS'
		);

		//遍历获取这些属性
		foreach($attrArr as $att){
			// echo $att."<br/>";
			echo "PDO::ATTR__$att：";
			echo $pdo->getAttribute(constant("PDO::ATTR_$att"))."<br/>";
		}


	} catch (PDOException $e) {
		echo $e->getMessage();		
	}