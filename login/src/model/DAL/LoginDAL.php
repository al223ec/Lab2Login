<?php 

	namespace DAL; 
	
	require_once(ROOT_DIR . "/src/model/User.php"); 

	class LoginDAL {

		const DB_CONNECTION = "127.0.0.1"; 
		const DB_PASSWORD = ""; 
		const DB_USERNAME = "dbUser"; 
		const DB_NAME = "lab2logindb"; 
		const TBL_NAME = "users"; 

		private $mysqli;
		private $currentUser; 

		public function __construct(){
			$this->mysqli = new \mysqli(self::DB_CONNECTION, self::DB_USERNAME, self::DB_PASSWORD, self::DB_NAME);
		}
    	/**
	    * @param String userName
	    * @return Null eller ett user object 
	    * @throws Exception om något går fel med SQL eller ingen input
	    */
    	private function getUserByUserName($userName){
    		//if(!$userName)
    		//	throw new \Exception("Error no userName provided");
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
	 
	        if($result = $statement->get_result()->fetch_object()){	
	        	$ret = new \model\User($result->PK, $result->UserName, $result->Password);
	    	}
	        return $ret;
    	}	
    	/**
    	*
    	*/
    	public function getUser($userName, $password){
      		$user = $this->getUserByUserName($userName); 
    		if($user != null){
    			$user->validate($password); 
    			if($user->isValid())
    				$this->currentUser = $user; 
    		}
    		return $user; 
    	}

    	public function getCurrentUser(){
    		return $this->currentUser; //Privacy??
    	}
	}