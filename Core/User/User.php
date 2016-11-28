<?php
namespace Core\User;
/**
* 
*/
class User{
	private $pdo = null;

	function __construct($pdo){
		$this->pdo = $pdo;
	}
	public function getUser($login, $password){
        $sql = "SELECT id, pass FROM rate_users WHERE login = :login";
        $sth = $this->pdo->prepare($sql);
        if($sth->execute(array(':login' => $login))){
            $row = $sth->fetch(\PDO::FETCH_ASSOC);
            $result = (password_verify($password, $row['pass'])) ? $row['id'] : false;
        }else{
            $result = false;
        }
        return $result;
    }
    /*public function addNewUser($login, $password){
        $dt = date("Y-m-d H:i:s", time());
        $sth = $this->pdo->prepare("INSERT INTO users (login, password, regdate) VALUES (:login, :password, :regdate)");
        $sth->execute(array(':login' => login, ':password' => $password, ':regdate' => $dt));
        $id = $this->pdo->lastInsertId();
        return $id;
    }
    //*****
    
    public function getLoginRows(){
        $sql = "SELECT id, login FROM users";
        $sth = $this->pdo->prepare($sql);
        $sth->execute();

        return $sth->fetchAll();
    }
    public function setUser($id){
        $this->id = $id;
        $sql = "SELECT login FROM users WHERE id = :id";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(array(':id' => $id));

        $this->login = $sth->fetch(\PDO::FETCH_NUM)[0];
    }
    public function getUserID(){
        return $this->id;
    }
    public function getUserLogin(){
        return $this->login;
    }*/
}