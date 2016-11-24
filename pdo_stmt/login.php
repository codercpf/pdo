<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="container" style="margin-top: 50px;">
		<form action="doAction01.php" method="POST" role="form">
			<legend>登录</legend>
			<div class="form-group">
				<label for="username">用户名：</label>
				<input type="text" class="form-control" id="username" name="username" placeholder="用户名">
			</div>
			<div class="form-group">
				<label for="passwd">密码：</label>
				<input type="text" class="form-control" id="passwd" name="passwd" placeholder="密码">
			</div>				
			<button type="submit" class="btn btn-primary">提交</button>
		</form>
	</div>
</body>
</html>