<?php 

//exec()执行一条sql语句并返回其受影响的行数；如果没有受影响的记录，返回0

//exec对查询语句select没有作用

//exec插入多条记录，lastInsertId()返回最后插入的记录id

try {
	$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");
/*	
	//exec插入多条记录
	$sql = <<<EOF
		insert user(username,passwd,email) 
		values ("chang01","chang02","chang02@qq.com"),
			   ("abc","abc","abc@qq.com"),
			   ("mmaa","mmaa","mm@qq.com")
EOF;
*/
	//SQL语句必须为单引号
	$sql = 'insert user(username,passwd,email)values("chang05","chang05","chang05@qq.com")';
	$res = $pdo->exec($sql);
	echo '受影响的记录条数为：'."$res.<br/>";

	echo '最后插入的ID号为：'.$pdo->lastInsertId();
	
} catch (PDOException $e) {
	echo $e->getMessage();
}