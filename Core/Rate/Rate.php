<?php
namespace Core\Rate;
/**
* 
*/
class Rate{
	private $pdo = null;

	function __construct($pdo){
		$this->pdo = $pdo;
	}
	public function addNewRate($postArray){
        $sth = $this->pdo->prepare("INSERT INTO rates (comment, rate, rating_date, hash) VALUES (:comment, :rate, :rate_date, :hash)");
        $sth->execute(array(':comment' => $postArray['comment'], ':rate' => $postArray['rating'], ':rate_date' => $postArray['ls'], ':hash' => $postArray['hash']));
        $id = $this->pdo->lastInsertId();
        return $id;
    }
    public function updateRate($postArray){
        $sql = "UPDATE rates SET comment=:comment, 
            rate=:rate, 
            rating_date=:rating_date,
            hash=:hash
            WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);                                  
        $stmt->bindParam(':comment', $postArray['comment'], \PDO::PARAM_STR);       
        $stmt->bindParam(':rate', $postArray['rating'], \PDO::PARAM_INT);    
        $stmt->bindParam(':rating_date', $postArray['ls'], \PDO::PARAM_INT);
        $stmt->bindParam(':hash', $postArray['hash'], \PDO::PARAM_STR);
        $stmt->bindParam(':id', $postArray['id'], \PDO::PARAM_INT);   
        $stmt->execute();
    }
    public function getRateInfo($index){
        $sql = "SELECT id FROM rates WHERE rating_date = :index";
        $sth = $this->pdo->prepare($sql);
        if($sth->execute(array(':index' => $index))){
            $result = $sth->fetch(\PDO::FETCH_NUM)[0];
        }else{
            print_r($result);
            $result = false;
        }
        return $result;
    }
    public function checkInfo($hash){
        $sql = "SELECT * FROM rates WHERE hash = :hash";
        $sth = $this->pdo->prepare($sql);
        if($sth->execute(array(':hash' => $hash))){
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
        }
        return ($result) ? $result : false;
    }
    public function getCommentRows(){
        $sth = $this->pdo->prepare("SELECT id, comment, rate, rating_date, readed FROM rates");
        $sth->execute();

        return $sth->fetchAll();
    }
    public function changeReadStatus($id, $param){
        $tmp = ($param === 1) ? time() : 0;
        $sql = "UPDATE rates SET readed=:readed
            WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':readed', $tmp, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);   
        $stmt->execute();
    }
    public function deleteComment($id){
        $sth = $this->pdo->prepare("DELETE FROM rates WHERE id = :id");
        $sth->execute(array(':id' => $id));
    }
    public function sortAcs(){
        $sth = $this->pdo->prepare("SELECT id, comment, rate, rating_date, readed FROM rates ORDER BY rate ASC");
        $sth->execute();

        return $sth->fetchAll();
    }
    public function sortDecs(){
        $sth = $this->pdo->prepare("SELECT id, comment, rate, rating_date, readed FROM rates ORDER BY rate DESC");
        $sth->execute();

        return $sth->fetchAll();
    }
    public function filterComment($filter){
        if (empty($filter['readFilter'])) {
            return $this->rateFilter($filter['rateFilter']);
        }elseif (empty($filter['rateFilter'])) {
            return $this->readFilter($filter['readFilter']);
        }elseif (empty($filter['rateFilter']) && empty($filter['readFilter'])) {
            return $this->getCommentRows();
        }else{
            return $this->readRateFilter($filter);
        }
    }
    public function rateFilter($filter){
        $tmp = explode("-", $filter);
        if (count($tmp) > 1) {
            $start = intval(trim($tmp[0]));
            $end = intval(trim($tmp[1]));
            $sth = $this->pdo->prepare("SELECT id, comment, rate, rating_date, readed 
            FROM rates 
            WHERE rate >= :s AND rate <= :e");
            $sth->execute(array("s" => $start, "e" => $end));
        }
        if (count($tmp) === 1) {
            $rate = intval(trim($tmp[0]));
            $sth = $this->pdo->prepare("SELECT id, comment, rate, rating_date, readed 
            FROM rates 
            WHERE rate = :rate");
            $sth->execute(array("rate" => $rate));
        }
        return $sth->fetchAll();
    }
    public function readFilter($filter){
        if($filter === 'setReadFilter'){
            $sth = $this->pdo->prepare("SELECT id, comment, rate, rating_date, readed 
            FROM rates 
            WHERE readed > :readed");
        }else{
            $sth = $this->pdo->prepare("SELECT id, comment, rate, rating_date, readed 
            FROM rates 
            WHERE readed = :readed");
        }
        $sth->execute(array("readed" => 0));
        return $sth->fetchAll();
    }
    public function readRateFilter($filter){
        $tmp = explode("-", $filter['rateFilter']);
        if (count($tmp) > 1) {
            $start = intval(trim($tmp[0]));
            $end = intval(trim($tmp[1]));
            if($filter['readFilter'] === 'setReadFilter'){
                $sth = $this->pdo->prepare("SELECT id, comment, rate, rating_date, readed 
                FROM rates 
                WHERE rate >= :s AND rate <= :e AND readed > :readed");
            }else{
                $sth = $this->pdo->prepare("SELECT id, comment, rate, rating_date, readed 
                FROM rates 
                WHERE rate >= :s AND rate <= :e AND readed = :readed");
            }
            $sth->execute(array("s" => $start, "e" => $end, "readed" => 0));
        }
        if (count($tmp) === 1) {
            $rate = intval(trim($tmp[0]));
            if($filter['readFilter'] === 'setReadFilter'){
                $sth = $this->pdo->prepare("SELECT id, comment, rate, rating_date, readed 
                FROM rates 
                WHERE rate = :rate AND readed > :readed");
            }else{
                $sth = $this->pdo->prepare("SELECT id, comment, rate, rating_date, readed 
                FROM rates 
                WHERE rate = :rate AND readed = :readed");
            }
            $sth->execute(array("rate" => $rate, "readed" => 0));
        }
        return $sth->fetchAll();
    }
}