<?php
namespace Core\Rate;
/**
* 
*/
class Response{
	private $dataArray = array();

	function __construct(){}
	public function pushItem($nameItem, $valueItem){
		$this->dataArray[$nameItem] = $valueItem;
	}
	public function getJSON(){
		
		return json_encode($this->dataArray);
	}
}