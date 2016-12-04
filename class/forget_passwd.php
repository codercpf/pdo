<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<div id="register" class="container" style="margin-top: 50px;">
			<form action="doAction.php?act=getpassword" method="POST" role="form">
				<legend>请输入注册邮箱：</legend>
				
				<div class="form-group">
					<label for="email">邮箱：</label>
					<input type="text" class="form-control" id="email" name="email" placeholder="邮箱账号">
				</div>
				<div class="form-group text-center">
					<input type="submit" class="btn btn-primary" value="找回密码">
				</div>

				<div class="form-group">
					<span style="float:left;">
						<a href="reg.php">注册</a>
					</span>					
					<span style="float: right;">
						<a href="index.php">登录</a>
					</span>
				</div>
								
			</form>
		</div>

	</div>
	
</body>
</html>