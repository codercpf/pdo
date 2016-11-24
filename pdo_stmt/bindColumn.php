<?php 

//bindColumn()绑定一列到一个php变量
//columnCount()返回结果集中的列数
//getColumnMeta() 返回结果集中某一列的元数据，是一个数据组；索引下标从0开始。该方法为实验性的，以后可能会删除

	try {
		$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");
		$sql = "select username, passwd, email from user";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		//返回总列数
		echo '结果集中的列数一共有：'.$stmt->columnCount();
		echo '<hr/>';

		//使用getColumnMeta()返回某一列，索引从0开始
		print_r($stmt->getColumnMeta(0));	//返回username这一列的定义信息，不是数据
		echo '<hr/>';

		//将列绑订到指定变量
		$stmt->bindColumn(1,$username);		//第一列绑定到 $username
		$stmt->bindColumn(2,$passwd);		//第二列绑定到 $passwd
		$stmt->bindColumn(3,$email);		//第三列绑定到 $email

		//使用bindColumn()时，fetch()函数取数据，参数要设置为FETCH_BOUND()，即获取绑定列的数据
		while ($stmt->fetch(PDO::FETCH_BOUND)) {
			echo '用户名：'.$username.'   密码：'.$passwd.'   邮箱：'.$email."<hr/>";
		}
		
	} catch (PDOException $e) {
		echo $e->getMessage();		
	}