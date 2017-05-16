<?php

class Todo {
	private $db = null;
	
	function __construct ($db) {
		$this->db = $db;
	}
	
	function auth() {
		if(!empty($_POST['reg'])){
			if(empty($_POST['login']) || empty($_POST['password'])){
				echo '<p>Заполните все поля</p>';
			}
			else{
				$login = strip_tags(trim($_POST['login']));
				$password = strip_tags(trim($_POST['password']));
				
				$secret = 'hhJNE63';
				$passMd = md5($password.$secret);
				
				$user = "SELECT `login`, `password` FROM user WHERE login = '" . $login ."' AND password = '". $passMd ."'";
				
				$resUser = $this->db->prepare($user);
				$resUser->execute();
				$resUser2 = $resUser->fetchAll();
				
				if(count($resUser2) === 0){
					echo 'Неверный логин или пароль';
				}
				
				else {
					session_start();
					$_SESSION['user'] = $login;
					header('Location: index.php');
				}
			}
		}	
	}
	
	function registr() {
		if(!empty($_POST['reg'])){
			if(empty($_POST['login']) || empty($_POST['password'])){
				echo '<p>Заполните все поля</p>';
			}
			else{
				$login = strip_tags(trim($_POST['login']));
				$password = strip_tags(trim($_POST['password']));
				
				$secret = 'hhJNE63';
				$passMd = md5($password.$secret);
				
				$user = "SELECT `login` FROM user WHERE login = '" . $login . "'";
				
				$resUser = $this->db->prepare($user);
				$resUser->execute();
				$resUser2 = $resUser->fetchAll();
				
				
				if(!empty($resUser2[0]['login'])){
					if($resUser2[0]['login'] === $login){
						echo '<p>Такой пользователь уже существует</p>';
					}
				}
				else{
					$newUser = "INSERT INTO `user` (`login`, `password`) VALUES ('".$login."', '".$passMd."')";
					$newUserPrepare = $this->db->prepare($newUser);
					$newUserPrepare->execute();
					echo '<p>Регистрация успешно завершена <a href="index.php">Перейти на страницу входа</a></p>';
				}
			}
		}	
	}
	
	function logout() {
		unset($_SESSION['user']);
		session_destroy();
	}
	
	function showTask($sort = null) {
		if(!empty($sort)){
			$sortt = $sort;
		}
		else {
			$sortt = 'date_added';
		}
		
		$showMyTask = "SELECT `login`, task.id, `description`, `date_added`, `is_done`, `assigned_user_id`, `user_id` FROM `task`, `user` WHERE task.user_id = user.id AND user.login = '" . $_SESSION['user'] ."' ORDER BY " . $sortt;
		
		
		
		$resShowMyTask = $this->db->prepare($showMyTask);
		$resShowMyTask->execute();
		
		return $resShowMyTask->fetchAll();
		
	}
	
	function assigned() {
		$sql = $this->db->prepare("SELECT `login`, `description` FROM `user`, `task` WHERE task.assigned_user_id = user.id");
		$sql->execute();	
		return $sql->fetchAll();
	}
	
	function author() {
		$author = $this->db->prepare("SELECT `login`, `description` FROM `user`, `task` WHERE task.user_id = user.id");
		$author->execute();
		return $author->fetchAll();
	}
	
	function getAllUsers(){
		$allUsers = $this->db->prepare('SELECT `id`, `login` FROM user');
		$allUsers->execute();
		return $allUsers->fetchAll();
	}
	
	function myAssigned() {
		$myAssigned = $this->db->prepare("SELECT `login`, task.id, `user_id`, `is_done`, `date_added`, `description` FROM user, task WHERE task.assigned_user_id = user.id AND user.login = '".$_SESSION['user']."'  AND task.assigned_user_id != task.user_id");
			
		$myAssigned->execute();
		return $myAssigned->fetchAll();
	}
	
	function addTask() {
		$date = date("Y-m-d H:i:s");
		$creadTask = trim((string)($_POST['add']));
	
	
		if(!empty($this->showTask()[0]['user_id']) && !empty($this->showTask()[0]['user_id'])){
			$assigned = $this->showTask()[0]['user_id'];
			$user_id = $this->showTask()[0]['user_id'];
		}
		
		//Если у пользователя нету ни одной задачи
		else{
			$sqlAddMyTask = "SELECT `login`, `id` FROM `user` WHERE user.login = '" . $_SESSION['user'] ."'";
			$addMyTask = $this->db->prepare($sqlAddMyTask);
			$addMyTask->execute();
		
			$resAddMyTask = $addMyTask->fetchAll();
			$assigned = $resAddMyTask[0]['id'];
			$user_id = $resAddMyTask[0]['id'];
		}
		
		$sqlNewTask = 'INSERT INTO `task` (`user_id`, `assigned_user_id`, `description`, `is_done`, `date_added`) VALUES 
		("'.$user_id.'", "'.$assigned.'", "'.$creadTask.'", "0", "'.$date.'")';
			
		
			$sqlNewTask = $this->db->prepare($sqlNewTask);
			$sqlNewTask->execute();
			header('Location: index.php');
			
	}
	
	function taskDone($id) {
		$taskDone = 'UPDATE `task` SET `is_done` = 1 WHERE id =:id';
		$taskDone = $this->db->prepare($taskDone);
		$taskDone->execute([':id' => (int)$id]);
		header( 'Location: index.php');
	}
	
	function taskDelete($id) {
		$sqlDelete = 'DELETE FROM `task` WHERE id =:id';
		$sqlDelete = $this->db->prepare($sqlDelete);
		$sqlDelete->execute([':id' => (int)$id]);
		header( 'Location: index.php');
	}
	
	function taskEdit($id) {
		$sqlEdit = 'SELECT `description` FROM `task` WHERE id=:id';
		$sqlEdit = $this->db->prepare($sqlEdit);
		$sqlEdit->execute([':id' => (int)$id]);
		$edit = $sqlEdit->fetchAll();
		$answer = $edit[0]['description'];
		return $answer;
	}
	
	function taskUpdate($description, $id) {
		$taskEdit = 'UPDATE `task` SET `description` = "' . (string)($description) . '" WHERE id =:id';
		$newTask = $this->db->prepare($taskEdit);
		$newTask->execute([':id' => (int)$id]);
		header( 'Location: index.php');
	}
	
	function editAssigned($id) {
		$select = $id;	
		$assigned_user_id = explode('_', $select);
							
		$assigned = $assigned_user_id[1];
		$id2 = $assigned_user_id[3];
				
		$sql2 = $this->db->prepare("UPDATE `task` SET `assigned_user_id` =" . $assigned. " WHERE id =" . $id2);
		$sql2->execute();
		header( 'Location: index.php');	
	}
	
	function sortTask($sort) {
		return $sort;
	}
}