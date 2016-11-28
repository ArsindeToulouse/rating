<?php
namespace Core\Task;
use Core\User\User;
/**
* 
*/
class Tasks{

	private $pdo = null;
	private $user = null;
	
	function __construct($pdo, User $user){
		$this->pdo = $pdo;
		$this->user = $user;
	}
	public function getAssinedTasksRows(){
        $sql = "SELECT tasks.id, tasks.description, tasks.is_done, tasks.date_added, users.login FROM tasks LEFT JOIN users ON tasks.user_id = users.id WHERE assigned_user_id = :id";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':id' => $this->user->getUserID()));

        return $sth->fetchAll();
    }
    public function changeAssignedUser($another_user_id, $task_id){
        $sql = "UPDATE tasks SET assigned_user_id = :assigned_user_id WHERE id = :id";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':assigned_user_id' => $another_user_id, ':id' => $task_id));
    }
    public function addNewTask($task){
    	$dt = date("Y-m-d H:i:s", time());
        $sth = $this->pdo->prepare("INSERT INTO tasks (user_id, description, is_done, date_added) VALUES (:user_id, :description, :is_done, :date_added)");
        $sth->execute(array(':user_id' => $this->user->getUserID(), ':description' => $task, ':is_done' => 0, ':date_added' => $dt));

        return true;
    }
    public function setDoneTask($id){
    	$sql = "SELECT is_done FROM tasks WHERE id = :id";
    	$sth = $this->pdo->prepare($sql);
        $sth->execute(array(':id' => $id));
        $result = $sth->fetch(\PDO::FETCH_NUM);
        if ($result[0] === '0') {
        	$dt = date("Y-m-d H:i:s", time());
        	$sql_update = "UPDATE tasks SET is_done ='1', date_added = :dt WHERE id = :id";
        	$sth2 = $this->pdo->prepare($sql_update);
        	$sth2->execute(array(':id' => $id, ':dt' => $dt));
        }
    }
    public function getUserTasksOrderRows($str){
        $sql = "SELECT tasks.id, tasks.description, tasks.is_done, tasks.date_added, users.login FROM tasks LEFT JOIN users ON tasks.assigned_user_id = users.id WHERE user_id = :id ORDER BY {$str}";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':id' => $this->user->getUserID()));

        return $sth->fetchAll();
    }
    public function getUserTasksRows(){
        $sql = "SELECT tasks.id, tasks.description, tasks.is_done, tasks.date_added, users.login FROM tasks LEFT JOIN users ON tasks.assigned_user_id = users.id WHERE user_id = :id";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':id' => $this->user->getUserID()));

        return $sth->fetchAll();
    }
}