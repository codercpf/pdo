<?php 

//1、通过PDO链接数据库
	$pStartTime = microtime(true);
	for ($i=1; $i <= 100; $i++) { 
		$pdo = new PDO("mysql:host=localhost;dbname=imooc","root","root");
	}
	$pEndTime = microtime(true);
	$res01 = $pEndTime - $pStartTime;


//2、通过mysql链接数据库
	$mStartTime = microtime(true);
	for ($i=1; $i <=100; $i++) { 
		mysql_connect('localhost','root','root');
		mysql_select_db('imooc');
	}
	$mEndTime = microtime(true);
	$res02 = $mEndTime - $mStartTime;

	echo "PDO链接时间：".$res01."<br/>";
	echo "MYSQL链接时间：".$res02;
	echo "<hr/>";

	if($res01 > $res02){
		echo "PDO链接数据库时间是MYSQL的 ".round($res01/$res02)."倍";
	}else{
		echo "MYSQL连接数据库时间是PDO的 ".round($res02/$res01)."倍";
	}

