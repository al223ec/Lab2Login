<?php

class config {
	private $DB_CONNECTION = "127.0.0.1"; 
	private $DB_PASSWORD = ""; 
	
	private $DB_USERNAME = "dbUser"; 

	private $DB_NAME = "lab2logindb"; 
	private $TBL_NAME = "users"; 
	private $connection; 

//Stored procedure http://php.net/manual/en/mysqli.quickstart.stored-procedures.php
//Mysql sp http://forums.mysql.com/read.php?98,358569

	public function DBLogin(){

		$mysqli = new mysqli("localhost", $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_NAME);
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
		echo $mysqli->host_info . "\n";

		$mysqli = new mysqli("127.0.0.1", $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_NAME, 3306);
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}

		echo $mysqli->host_info . "\n";       
	}    

	public function handleDBError($error){
		echo "$error";
	}


}