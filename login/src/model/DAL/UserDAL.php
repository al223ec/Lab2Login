<?php 

	namespace DAL; 
	
	require_once(ROOT_DIR . "/src/model/User.php"); 

	class UserDAL {

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
	    * @return Null eller ett user object 
	    * @throws Exception om något går fel med SQL eller ingen input
	    */
    	public function getUserByUserName($userName){
    		//if(!$userName)
    		//	throw new \Exception("Error no userName provided");
    		$ret = null; 
    		//http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php
			$sql = "SELECT * FROM " . self::TBL_NAME . " WHERE UserName = ?";
			$statement = $this->mysqli->prepare($sql);//Förhindrar sql injections

	        if ($statement === FALSE) {
	            throw new \Exception("prepare of $sql failed " . $this->mysqli->error);
	        }	
			$statement->bind_param("s", $userName); 

	        //http://www.php.net/manual/en/mysqli-stmt.execute.php
	        if ($statement->execute() === FALSE) {
	            throw new \Exception("execute of $sql failed " . $statement->error);
	        }
	 
	        if($result = $statement->get_result()->fetch_object()){	
	        	$ret = new \model\User($result->UserID, $result->UserName, $result->Hash, $result->CookieValue, $result->CookieExpiration);
	    	}
	        return $ret;
    	}	

    	public function saveNewUser($userName, $password){
    		$userName = $this->sanitize($userName); //Ganska onödigt egentligen kommer ej implementera att lägga till användare
    		$password = $this->sanitize($password);

			// A higher "cost" is more secure but consumes more processing power
			$cost = 10;

			// Create a random salt
			$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

			// Prefix information about the hash so PHP knows how to verify it later.
			// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
			$salt = sprintf("$2a$%02d$", $cost) . $salt;

			// Hash the password with the salt
			$hash = crypt($password, $salt);
		
			/* check connection */
			if (mysqli_connect_errno()) {
			    printf("Connect failed: %s\n", mysqli_connect_error());
			    exit();
			}	
			$sql = "INSERT INTO users(UserName, Hash) VALUES ( ?, ?)";
			$statement = $this->mysqli->prepare($sql);

	        if ($statement === FALSE) {
	            throw new \Exception("prepare of $sql failed " . $this->mysqli->error);
	        }	
			$statement->bind_param("ss", $userName, $hash); 

	        //http://www.php.net/manual/en/mysqli-stmt.execute.php
	        if ($statement->execute() === FALSE) {
	            throw new \Exception("execute of $sql failed " . $statement->error);
	        }
	        return true;//Allt har gått väl
    	}

    	/**
	    * @param String input
	    * @return String input - tags - trim
	    */
	    private function sanitize($input) {
	        $temp = trim($input);
	        return filter_var($temp, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	    }

	    public function saveCookieValue ($userID, $cookieValue, $cookieExpiration = 0){
			$sql = "UPDATE " . self::TBL_NAME . " SET CookieValue = ?, CookieExpiration = ? WHERE UserID = ?";
			$statement = $this->mysqli->prepare($sql);

	        if ($statement === FALSE) {
	            throw new \Exception("prepare of $sql failed " . $this->mysqli->error);
	        }	
			$statement->bind_param("sss", $cookieValue, $cookieExpiration, $userID); 

	        //http://www.php.net/manual/en/mysqli-stmt.execute.php
	        if ($statement->execute() === FALSE) {
	            throw new \Exception("execute of $sql failed " . $statement->error);
	        }
	 
	        return true; 
	    }
	    private function performSQL($sql, array $params){
	    }
}