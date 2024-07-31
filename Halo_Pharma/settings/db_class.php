<?php
//database

//database credentials
require('db_cred.php');


class db_connection
{
	
	//properties
	public $db = null;
	public $results = null;

	function db_connect(){
		
		//connection
		$this->db = mysqli_connect(SERVER,USERNAME,PASSWD,DATABASE);
		
		//test the connection
		if (mysqli_connect_errno()) {
			return false;
		}else{
			return true;
		}
	}

	function db_conn(){
		
		//connection
		$this->db = mysqli_connect(SERVER,USERNAME,PASSWD,DATABASE);
		
		//test the connection
		if (mysqli_connect_errno()) {
			return false;
		}else{
			return $this->db;
		}
	}



	function db_query($sqlQuery){
		
		if (!$this->db_connect()) {
			return false;
		} 
		elseif ($this->db==null) {
			return false;
		}

		//run query 
		$this->results = mysqli_query($this->db,$sqlQuery);
		
		if ($this->results == false) {
			return false;
		}else{
			return true;
		}
	}

	//execute a query with mysqli real escape string
	//to saveguard from sql injection
	/**
	*Query the Database
	*@param takes a connection and sql query
	*@return bolean
	**/
	function db_query_escape_string($sqlQuery){
		
		//run query 
		$this->results = mysqli_query($this->db,$sqlQuery);
		
		if ($this->results == false) {
			return false;
		}else{
			return true;
		}
	}

	//fetch a data
	/**
	*get select data
	*@return a record
	**/
	function db_fetch_one($sql){
		
		// if executing query returns false
		if(!$this->db_query($sql)){
			return false;
		} 
		//return a record
		return mysqli_fetch_assoc($this->results);
	}

	//fetch all data
	/**
	*get select data
	*@return all record
	**/
	function db_fetch_all($sql){
		
		// if executing query returns false
		if(!$this->db_query($sql)){
			return false;
		} 
		//return all record
		return mysqli_fetch_all($this->results, MYSQLI_ASSOC);
	}


	//count data
	/**
	*get select data
	*@return a count
	**/
	function db_count(){
		
		//check if result was set
		if ($this->results == null) {
			return false;
		}
		elseif ($this->results == false) {
			return false;
		}
		
		//return a record
		return mysqli_num_rows($this->results);

	}
	
}
?>