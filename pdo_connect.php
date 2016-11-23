<?php 
	//1、通过参数形式链接数据库
	try {
		$dsn = "mysql:host=localhost;dbname=imooc";
		$username = "root";
		$passwd = "root";
		$options = array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);
		$pdo = new PDO($dsn, $username, $passwd, $options);	

		var_dump($pdo);	

	} catch (PDOException $e) {
		echo $e->getMessage();
	}

	//2、通过uri的形式链接数据库
		/*
		* 将数据源 dsn 专门设置一个文件存放，如dsn.txt；内容为
		* mysql:host=localhost;dbname=imooc
		* 引用时，将数据源写为文件路径即可
		* $dsn = 'uri:file://F:\dev\imooc\pdo\dsn.txt'
		*/

	//3、通过配置文件的形式链接数据库
		/*
		*  将数据源 dsn 写在php.ini配置文件中，并起名字。如： 
		*  pdo.dsn.imooc = "mysql:host=localhost;dbname=imooc"
		*  引用时，将数据源写为配置的名字即可
		*  $dsn = "imooc"
		*/
