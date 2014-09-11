<?php 

namespace view; 

class CookieHandler{

	public function __construct(){
		$this->saveHashedCookie("Password", "2a'$'10'$'DixWNrAgbkC7Z7T6JY7ex.g7hiqrXMF3qh9mBvI9CNQeWbeM.y5Tq"); 
		var_dump($this->loadHashedCookie("Password", "2a'$'10'$'DixWNrAgbkC7Z7T6JY7ex.g7hiqrXMF3qh9mBvI9CNQeWbeM.y5Tq")); 
	}
	public function saveHashedCookie($cookieName, $cookieValue){
		$hash = crypt($cookieValue, $this->generateRandomString());
		$this->saveCookie($cookieName, $hash); 
	}

	public function saveCookie($cookieName, $cookieValue){
		setcookie($cookieName, $cookieValue, -1); 
	} 

	public function loadCookie($cookieName){
	}

	public function loadHashedCookie($cookieName, $password){
		$hashedPassword = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : ""; 
		return crypt($password, $hashedPassword) === $hashedPassword; 
		
	}

	private function generateRandomString($length = 16) {
 	   $characters = '0123456789abcdefghijklmnopqrstuvwxyzåäöABCDEFGHIJKLMNOPQRSTUVWXYZÅÄÖ';
    	$randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}
}