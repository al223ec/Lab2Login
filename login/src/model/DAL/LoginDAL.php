<?php 

	namespace DAL; 
	require_once("../User.php"); 

	class LoginDAL {

		const DB_CONNECTION = "127.0.0.1"; 
		const DB_PASSWORD = ""; 
		const DB_USERNAME = "dbUser"; 
		const DB_NAME = "lab2logindb"; 
		const TBL_NAME = "users"; 

		private $mysqli;
		public function __construct(){
			$this->mysqli = new \mysqli(self::DB_CONNECTION, self::DB_USERNAME, self::DB_PASSWORD, self::DB_NAME);
		}
    	/**
	    * @param String userName
	    * @return Null or an object 
	    * @throws Exception if something goes wrong with SQL or no input
	    */
    	public function getUser($userName){
    		if(!$userName)
    			throw new \Exception("Error no userName provided");
    			
    		$ret = null; 
    		//http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
			$sql = "SELECT * FROM " . self::TBL_NAME . " WHERE UserName = ?"; //Förhindrar sql injections
			$statement = $this->mysqli->prepare($sql);
			$statement->bind_param("s", $userName); 

	        if ($statement === FALSE) {
	            throw new \Exception("prepare of $sql failed " . $this->mysqli->error);
	        }	
	 
	        //http://www.php.net/manual/en/mysqli-stmt.execute.php
	        if ($statement->execute() === FALSE) {
	            throw new \Exception("execute of $sql failed " . $statement->error);
	        }
	 
	        $ret = $statement->get_result()->fetch_object();	 
	        //var_dump($ret); 
	        return $ret;
    	}	
    	public function checkUserName($userName){
    		if($userName == ""){

    		}
    	}
    	public function checkPassword($password){

    	}

    	public function checkCredentials($userName, $password){
    	}
    	public function passwordIsNotCorrect(){
    		return false; 
    	}
    	public function ceckIfUserNameExists(){
    		return true; 
    	}

		//private static $staticMysqli; 
		//public static function __construct(){
		//	self::$staticMysqli = new \mysqli("localhost", $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_NAME);
		//}
		/*
		private function getUsers(){ 
			$ret = array();
	        $sql = "SELECT 
	         * FROM " . $this->TBL_NAME . ";";
	 
	        //http://www.php.net/manual/en/mysqli-stmt.prepare.php
	        $statement = $this->mysqli->prepare($sql);
	        if ($statement === FALSE) {
	            throw new Exception("prepare of $sql failed " . $this->mysqli->error);
	        }
	 
	        //http://www.php.net/manual/en/mysqli-stmt.execute.php
	        if ($statement->execute() === FALSE) {
	            throw new Exception("execute of $sql failed " . $statement->error);
	        }
	 
	        //http://www.php.net/manual/en/mysqli-stmt.get-result.php
	        $result = $statement->get_result();
	             
	        //http://www.php.net/manual/en/mysqli-result.fetch-array.php
	        while ($object = $result->fetch_array(MYSQLI_ASSOC))
	        {
	       		var_dump($object);   
	        }
	 
	        return $ret;
    	}
		*/
	}