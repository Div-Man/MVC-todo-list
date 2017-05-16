<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>Для того, что бы увидеть содержимое сайта, надо авторизироваться</p>
		<a href="?register=ok">Или зарегистрируйтесь</a>
		
		<div class="main-reg">
			<h4>Авторизация</h4>
			<form method="POST" >
				<div class="clearfix">
					<label for="login">Введите логин</label>
					<input type="text" id="login" name="login">
				</div>
				<div class="clearfix">
					<label for="password">Введите пароль</label>
					<input type="password" id="password" name="password">
				</div>
							
				<input type="submit" name="reg" value="Войти">
			</form>
		</div>
	</body>
</html>