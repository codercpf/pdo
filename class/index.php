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
		<div id="login" class="container" style="margin-top: 50px;">
			<form action="doAction.php?act=login" method="POST" role="form">
				<legend>登录</legend>
				<div class="form-group">
					<label for="username">用户名：</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="用户名">
				</div>
				<div class="form-group">
					<label for="passwd">密码：</label>
					<input type="password" class="form-control" id="passwd" name="passwd" placeholder="密码">
				</div>

				<div class="form-group">
					<span style="float:left;">
						<input type="submit" class="btn btn-primary" value="登录">
					</span>					
					<span style="float: right;">
						<a href="reg.php">加入我们</a>
					</span>
				</div>
			</form>
		</div>

	</div>	
</body>
</html>