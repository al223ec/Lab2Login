<?php

class config {
	private $DB_CONNECTION = "127.0.0.1"; 
	private $DB_PASSWORD = ""; 
	
	private $DB_USERNAME = "dbUser"; 

	private $DB_NAME = "lab2logindb"; 
	private $TBL_NAME = "users"; 
	private $connection; 

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
	public function firstSetup(){
		$sql = "
		USE " . $this->$DB_NAME .";
		
		CREATE TABLE IF NOT EXISTS `". $this->TBL_NAME ."` (
  		`UserName` varchar(45) NOT NULL,
  		`PK` int(11) NOT NULL AUTO_INCREMENT,
  		`Password` varchar(45) NOT NULL,
  		PRIMARY KEY (`PK`),
  		UNIQUE KEY `PK` (`PK`),
  		KEY `UserName` (`UserName`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3;"; 
	}

	//http://alias.io/2010/01/store-passwords-safely-with-php-and-mysql/

	public function handleDBError($error){
		echo "$error";
	}


}