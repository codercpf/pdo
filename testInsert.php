<?php 

//1、通过PDO插入500条数据
	$pStartTime = microtime(true);
	$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");

	$sql = "insert test2 values(:id)";
	$stmt = $pdo->prepare($sql);

	for ($i=1; $i <= 500; $i++) { 
		$id = $i;
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();		
	}
	$pEndTime = microtime(true);
	$res01 = $pEndTime - $pStartTime;

	unset($pdo); 			//关闭pdo与数据库连接，或销毁对象，或赋值为空：$pdo=null;


//2、通过mysql插入500条数据
	$mStartTime = microtime(true);
	mysql_connect('localhost','root','root');
	mysql_select_db('imooc');	

	for ($i=1; $i <=500; $i++) { 
		$sql = "insert test2 values($i)";
		mysql_query($sql);		
	}
	mysql_close();				//关闭数据库连接
	$mEndTime = microtime(true);
	$res02 = $mEndTime - $mStartTime;

	echo "PDO插入500条数据的时间：".$res01."<br/>";
	echo "MYSQL插入500条数据的时间：".$res02;
	echo "<hr/>";

	if($res01 > $res02){
		echo "PDO插入500条数据的时间是MYSQL的 ".round($res01/$res02)."倍";
	}else{
		echo "MYSQL插入500条数据的时间是PDO的 ".round($res02/$res01)."倍";
	}

