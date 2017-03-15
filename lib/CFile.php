<?php
require_once 'stdlib.php';
class CFile{
	protected $id = null;
	protected $type = "file";
	protected $url = "";
	
	function __construct($id=null){

	}
	
	function GetID(){return $this->id;}
	function GetType(){return $this->type;}
	function GetURL(){return $this->url;}
	
	function SetType($type){$this->type = $type;}
	function SetURL($url){$this->url = $url;}
	function GetName(){return $this->url;}
	
}
?>