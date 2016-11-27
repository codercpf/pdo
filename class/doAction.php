<?php 
//1、包含所需文件
require_once 'swiftmailer/lib/swift_required.php';
require_once 'pdoMySQL.class.php';
require_once 'config.php';
require_once 'pwd.php';

//2、接收信息
$act = $_GET['act'];
$username = addslashes($_POST['username']);
$password = md5($_POST['passwd']);
$email = $_POST['email'];

//3、得到链接对象
$pdoMySQL = new pdoMySQL();
$table = 'mail';

if ($act === 'reg') {
	//完成注册功能
	$regtime = time();							 //注册时间
	$token = md5($username.$password.$regtime);  //令牌token
	$token_exptime = $regtime + 24 * 60 * 60;	 //过期时间，一天后过期
	//compact()函数，创建由参数所带变量组成的数组——————与extract()作用相反
	//键名为函数的参数、键值为参数中变量的值
	$data = compact('username','password','email','token','token_exptime','regtime');
	
	$res = $pdoMySQL->add($data,$table);		 //注册信息插入数据表
	$lastInsertId = $pdoMySQL->getLastInsertId();	//得到最后插入的ID
	
	//写入数据库成功，则发送邮件，以163邮箱为例；QQ邮箱要开启STMP服务
	if ($res) {
		//实例化Swift_SmtpTransport类，得到传输对象
		$transport = Swift_SmtpTransport::newInstance('smtp.163.com',25);
		//设置登录账号和密码
		$transport->setUsername('makingdifference@163.com');
		// $transport->setPassword('chang+2016');
		$transport->setPassword($emailPassword);
		//得到发送邮件对象Swift_Mailer对象，将传输对象传入
		$mailer = Swift_Mailer::newInstance($transport);
		//得到邮件信息对象
		$message = Swift_Message::newInstance();
		//设置管理员的信息
		$message->setFrom(array('makingdifference@163.com'=>'admin'));
		//接受者信息
		$message->setTo(array($email=>'imooc'));
		//邮件主题
		$message->setSubject('激活邮件');
		//邮件内容
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?act=active&token={$token}";
		$urlencode = urlencode($url);			//转码url
		$str=<<<EOF
			亲爱的{$username}，您好！感谢您注册我们的网站<br/>
			请点击此链接激活账号即可登录！<br/>
			<a href="{$url}">{$urlencode}</a><br/>
			如果点此链接无效，请将其复制到浏览器中来执行，链接的有效期为24小时。
EOF;
		$message->setBody("{$str}",'text/html','utf-8');
			//使用swift_attachment添加附件
		
		//使用mailer对象发送邮件
		try {			
			if ($mailer->send($message)) {
				//发送成功，告诉用户去激活
				echo "恭喜您{$username}，注册成功，请到邮箱激活之后登录<br/>";
				echo "5秒钟后跳转到登录页面";
				echo '<meta http-equiv="refresh" content="5;url=index.php" />';
			}else{
				//发送失败
				$pdoMySQL->delete($table, 'id='.$lastInsertId);
				echo '注册失败，请重新注册';
				echo "5秒钟后跳转到注册页面";
				echo '<meta http-equiv="refresh" content="5;url=reg.php" />';
			}
			
		} catch (Swift_ConnectionException $e) {
			echo "邮件发送错误：".$e->getMessage();
		}


	}else{
		echo '用户注册失败，5秒钟后跳转到注册页面';
		echo '<meta http-equiv="refresh" content="5;url=reg.php" />';
	}


}elseif ($act === 'login') {
	//完成登录功能
	$row = $pdoMySQL->find($table, "username='{$username}' and password='{$password}'", 'status');
/*
	echo "<pre>";
	print_r($row);
	echo "<pre>";
	exit;
*/
	if($row['status'] == 0) {
		echo '该账户未激活，请先激活再登录';
		echo '<meta http-equiv="refresh" content="5;url=index.php" />';
	}else{
		echo '登录成功，5秒钟后跳转到首页';
		echo '<meta http-equiv="refresh" content="5;url=http://www.baidu.com" />';
	}


}elseif ($act === 'active') {
	//完成激活功能	
	$token = addslashes($_GET['token']);
	//验证token、注册状态（未激活）;获取id、过期时间
	$row = $pdoMySQL->find($table, "token='{$token}' and status=0", array('id','token_exptime'));

	$now = time();	
	if ($now > $row['token_exptime']) {					//当前时间大于过期时间，则链接失效
		echo "该链接已失效，激活时间过期，请重新注册";
	}else{
		//更新账户状态
		$res = $pdoMySQL->update(array('status'=>1), $table, 'id='.$row['id']);
		if ($res) {
			echo '激活成功，5秒钟后跳转到登录页面';
			echo '<meta http-equiv="refresh" content="5;url=index.php" />';
		}else{
			echo '激活失败，请重新激活';
			echo '<meta http-equiv="refresh" content="5;url=index.php" />';
		}
	}
	

}

