<?php

class TodoController {
	private $model = null;
	
	function __construct ($db) {
		include 'model/Todo.php';
		$this->model = new Todo($db);
	}
	
	private function render($template = null, $params = null) {
		$fileTemplate = 'template/'.$template;
		if (is_file($fileTemplate)) {
			ob_start();
			if (count($params) > 0) {
				extract($params);
			}
			include $fileTemplate;
			return ob_get_clean();
		}
	}
	
	function getShowAuth() {
		$auth = $this->model->auth();
		echo $this->render('todo/no-session.php', ['auth' => $auth]);
	}
	
	function getRegistr() {
		$this->model->registr();
		echo $this->render('todo/register.php');
	}
	
	function getLogout() {
		$this->model->logout();
		header('Location: index.php');
	}
	
	function getTask() {
		$task = $this->model->showTask();
		$assigned = $this->model->assigned();
		$resAuthor = $this->model->author();
		$allUsers = $this->model->getAllUsers();
		$resMyAssigned = $this->model->myAssigned();
		
		echo $this->render('todo/show-task.php', [
													'task' => $task, 
													'assigned' => $assigned, 
													'resAuthor' => $resAuthor,
													'allUsers' => $allUsers,
													'resMyAssigned' => $resMyAssigned
												]);
		
	}
	
	function addNewTask() {
		$newTask = $this->model->addTask();
	}
	
	function getTaskDone($id) {
		$done = $this->model->taskDone($id);
	}
	
	function getTaskDelete($id) {
		$del = $this->model->taskDelete($id);
	}
	
	function getTaskEdit($id) {
		$editTask = $this->model->taskEdit($id);
		
		$task = $this->model->showTask();
		$assigned = $this->model->assigned();
		$resAuthor = $this->model->author();
		$allUsers = $this->model->getAllUsers();
		$resMyAssigned = $this->model->myAssigned();
		
		echo $this->render('todo/show-task.php', [
													'task' => $task, 
													'assigned' => $assigned, 
													'resAuthor' => $resAuthor,
													'allUsers' => $allUsers,
													'resMyAssigned' => $resMyAssigned,
													'editTask' => $editTask
												]);
		die();										
	}
	
	function getUpdateTask($description, $id) {
		$update = $this->model->taskUpdate($description, $id);
	}
	
	function assign($id) {
		$as = $this->model->editAssigned($id);
	}
	
	function sortTask($sort) {
		$sortic = $this->model->sortTask($sort);
		$task = $this->model->showTask($sortic);
		$assigned = $this->model->assigned();
		$resAuthor = $this->model->author();
		$allUsers = $this->model->getAllUsers();
		$resMyAssigned = $this->model->myAssigned();
		
		echo $this->render('todo/show-task.php', [
													'task' => $task, 
													'assigned' => $assigned, 
													'resAuthor' => $resAuthor,
													'allUsers' => $allUsers,
													'resMyAssigned' => $resMyAssigned,
												]);
	}
}