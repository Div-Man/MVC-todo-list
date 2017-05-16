<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		 <link rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<div class="main-reg">
		<h4>Регистрация нового пользователя</h4>
		
		<form method="POST" >
			<div class="clearfix">
				<label for="login">Введите логин</label>
				<input type="text" id="login" name="login" value="<?php if(!empty($_POST['login'])){echo $_POST['login'];}?>">
			</div>
			<div class="clearfix">
				<label for="password">Введите пароль</label>
				<input type="password" id="password" name="password">
			</div>
			
			<input type="submit" name="reg" value="Зарегистрироваться">
		</form>
		</div>
		
	</body>
</html>