<?php
namespace Core\DB;

/**
* 
*/
class ConnectDB{
	private $pdo;
	private $opt;
	//статическое свойство, которое создасться всего один раз при вызове объекта и будет хранить в себе сам объект
	private static $instance;
	
	// конструктор сделаем приватным, что бы извне нельзя было его вызвать
	private function __construct($host, $dbName, $user, $pass){
		$this->opt = array(
		    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
		    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
		);
		$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
		try {
			$this->pdo = new \PDO($dsn, $user, $pass, $this->opt);
		}catch(\PDOException $e) {
	      // обрабатываем ошибку
	    }
	}
	// метод для создания объекта. Здесь проверяется, если объект уже cуществует, 
	//то мы его и вернем, если не создан то создадим и вернем.
	public static function getInstance($host, $dbName, $user, $pass){
		if (!(self::$instance instanceof self)) {
			self::$instance = new self($host, $dbName, $user, $pass);
		}
		return self::$instance;
	}
	public function getPDO(){
		return $this->pdo;
	}
}