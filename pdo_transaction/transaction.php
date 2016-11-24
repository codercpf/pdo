<?php 
//PDO 事务操作，要么不做、要么全做

try {
	$dsn = "mysql:host=localhost;dbname=imooc";
	$username = "root";
	$passwd = "root";
	$options = array(PDO::ATTR_AUTOCOMMIT=>0);			//关闭自动提交
	$pdo = new PDO($dsn, $username, $passwd, $options);

	var_dump($pdo->inTransaction());				//用inTransaction()检查是否开启事务操作
	//开启事务
	$pdo->beginTransaction();
	var_dump($pdo->inTransaction());

	$sql01 = 'update useraccount set money=money-2000 where username="imooc2"';
	$res01 = $pdo->exec($sql01);
	if($res01 == 0){
		throw new PDOException("imooc 转账失败");
	}

	$sql02 = 'update useraccount set money=money+2000 where username="king"';
	$res02 = $pdo->exec($sql02);
	if($res02 == 0){
		throw new PDOException("king 收款失败");
	}

	$pdo->commit();			//提交事务

} catch (PDOException $e) {
	// 有异常，说明提交失败，将事务回滚
	$pdo->rollBack();

	echo $e->getMessage();
}

