<?php

include 'controller/TodoController.php';

$todo = new TodoController($db);

if(empty($_SESSION['user']) && empty($_GET['register'])){
	$todo->getShowAuth();
}

if(!empty($_GET['register'])) {
	 $todo->getRegistr();
}

if(!empty($_GET['exit'])){
	$todo->getLogout();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SESSION['user']) {
	if(!empty($_GET['action'])){
		if($_GET['action'] === 'done'){
			$todo->getTaskDone($_GET['id']);
		}
		if($_GET['action'] === 'delete'){
			$todo->getTaskDelete($_GET['id']);
		}
		
		if($_GET['action'] === 'edit'){
			echo '<p>Добро пожаловать ' . $_SESSION['user'] . ' <br><a href="?exit=ok">Выход</a></p>';
			$todo->getTaskEdit($_GET['id']);
			
		}
	}
	
	if(!empty($_SESSION['user'])){
		echo '<p>Добро пожаловать ' . $_SESSION['user'] . ' <br><a href="?exit=ok">Выход</a></p>';
		$todo->getTask();
	}
	
	
	
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($_POST['addTask']) {
		$todo->addNewTask();
	}
	
	if($_POST['editTask']) {
		$todo->getUpdateTask($_POST['update-description'], $_GET['id']);
	}
	
	if($_POST['assign']) {
		$todo->assign($_POST['assigned_user_id']);
	}
	
	if($_POST['sort']) {
		echo '<p>Добро пожаловать ' . $_SESSION['user'] . ' <br><a href="?exit=ok">Выход</a></p>';
		$todo->sortTask($_POST['sort_by']);
	}
	
	
}


