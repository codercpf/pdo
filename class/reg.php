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
			<form action="doAction.php?act=reg" method="POST" role="form">
				<legend>注册</legend>
				<div class="form-group">
					<label for="username">用户名：</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="用户名">
				</div>
				<div class="form-group">
					<label for="email">邮箱：</label>
					<input type="text" class="form-control" id="email" name="email" placeholder="邮箱">
				</div>
				<div class="form-group">
					<label for="passwd">密码：</label>
					<input type="password" class="form-control" id="passwd" name="passwd" placeholder="密码">
				</div>

				<div class="form-group">
					<span style="float:left;">
						<input type="submit" class="btn btn-primary" value="提交">
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