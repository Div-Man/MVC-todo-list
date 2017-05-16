<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		 <link rel="stylesheet" href="style.css">
		<style>
			.clearfix:after {
			  visibility: hidden;
			  display: block;
			  font-size: 0;
			  content: " ";
			  clear: both;
			  height: 0;
			}

			form{margin-bottom: 0}
			select{margin-right: 5px;}

			.main-reg {
				border: 1px solid;
				width: 400px;
			}

			.main-reg label{
				float: left;
			}

			.main-reg div{
				margin-bottom: 20px;
			}

			.main-reg input{
				float: right;
			}

			.add-task {
				margin-bottom: 20px; 
				margin-right: 20px;
				float: left;
			}
			
			table {
				border-spacing: 0;
				border-collapse: collapse;
			}

			.gray{background: #eee;}
			
			table td, table th{
				border: 1px solid #ccc;
				padding: 5px;
			}
			.orange {
				color: orange;
			}

			.green {
				color: green;
			}
		</style>
	</head>
	
	<body>
	<div class="clearfix">
		<div class="add-task float">
			<?php
				if(empty($_GET['action'])) {
					require_once 'add-task.php';
				}
				
				else {
					require_once 'edit-task.php';
				}
			?>
		</div>
			
		<div class="add-task float">
			<form method="POST">
				<label>Сортировать по:</label>
				<select name="sort_by">
					<option value="date_added">Дате добавления</option>
					<option value="is_done">Статусу</option>
					<option value="description">Описанию</option>
				</select>
				<input type="submit" name="sort" value="Отсортировать">
			</form>
		</div>
	</div>
		<table>
			<tr class="gray">
				<th>Описание задачи</th>
				<th>Дата добавления</th>
				<th>Статус</th>
				<th></th>
				<th>Ответственный</th>
				<th>Автор</th>
				<th>Закрепить задачу за пользователем</th>
			</tr>

			
			<?php
			echo '<tr>';
				foreach($task as $key){
					echo'<td>' . $key['description'] . '</td>';
					echo'<td>' . $key['date_added'] . '</td>';
									
					if($key['is_done'] == 0){
						echo '<td class="orange">В процессе</td>';
					}
					else if($key['is_done'] == 1){
						echo '<td class="green">Выполнено</td>';
					}
					echo '<td><a href="?id=' . $key['id'] . '&action=edit">Изменить </a>';
									
					if($key['assigned_user_id'] == $key['user_id']){
						echo '<a href="?id=' . $key['id'] . '&action=done">Выполнить</a> ';
					}
					echo '<a href="?id=' . $key['id'] . '&action=delete">Удалить</td>';
					
					if($key['assigned_user_id'] == $key['user_id']){
						echo '<td>Вы</td>';
					}
					
					
					
					else {
						foreach($assigned as $userrr){
							if($userrr['description'] == $key['description']){
								echo '<td>'.$userrr['login'].'</td>'; 
							}
						}
					}	
				
				foreach($resAuthor as $auth){
					if($key['description'] == $auth['description']){
						echo '<td>'.$auth['login'].'</td>';
					}
				}
				
				echo '<td>
					<form method="POST">
						<select name="assigned_user_id">';
							foreach($allUsers as $user1){
								echo '<option value="user_' . $user1['id'] .'_task_'.$key['id'].'">' . $user1['login'] .'</option>';
							};
						'</select>';	
						echo ' <input type="submit" name="assign" value="Предложить ответсвенность">
					</form>
				</td>';
			echo '</tr>';
			}
		?>
			
			
		</table>	
	
		<p><strong>Также, посмотрите, что от Вас требуют другие люди:</strong></p>
	
		<table>
			<tr class="gray">
				<th>Описание задачи</th>
				<th>Дата добавления</th>
				<th>Статус</th>
				<th></th>
				<th>Ответственный</th>
				<th>Автор</th>
			</tr>
				
			<?php
				foreach($resMyAssigned as $key){
					echo '<tr>';
						echo'<td>' . $key['description'] . '</td>';
						echo'<td>' . $key['date_added'] . '</td>';
											
						if($key['is_done'] == 0){
							echo '<td class="orange">В процессе</td>';
						}
						else if($key['is_done'] == 1){
							echo '<td class="green">Выполнено</td>';
						}
						echo '<td><a href="?id=' . $key['id'] . '&action=edit">Изменить </a>';
						echo '<a href="?id=' . $key['id'] . '&action=done">Выполнить</a> ';
						echo '<a href="?id=' . $key['id'] . '&action=delete">Удалить</td>';
											
						echo '<td>'.$_SESSION['user'].'</td>';
											
						foreach($resAuthor as $auth){
							if($key['description'] == $auth['description']){
								echo '<td>'.$auth['login'].'</td>';
							}
						}
					echo '</tr>';	
				}
			?>
		</table>	
	</body>
<html>

