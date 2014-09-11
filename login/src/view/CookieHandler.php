<?php 

namespace view; 

class CookieHandler{
	//Behöver spara informationen till db
	//Bör inte använda hashen i cookien
	public function __construct(){
		//$this->saveHashedCookie("Password", "2a'$'10'$'DixWNrAgbkC7Z7T6JY7ex.g7hiqrXMF3qh9mBvI9CNQeWbeM.y5Tq"); 
		//var_dump($this->loadAndConfirmHashedCookie("Password", "2a'$'10'$'DixWNrAgbkC7Z7T6JY7ex.g7hiqrXMF3qh9mBvI9CNQeWbeM.y5Tq")); 
	}

	public function saveHashedCookie($cookieName){
		$cookieValue = $this->generateRandomString(); 
//		$hash = crypt($this->generateRandomString(), $this->generateRandomString());
		$this->saveCookie($cookieName, $cookieValue); 
	}

	public function saveCookie($cookieName){
		$cookieValue = $this->generateRandomString(); 
		setcookie($cookieName, $cookieValue, -1); 
	} 

	public function loadCookie($cookieName){
		throw new \Exception("CookieHandler::loadCookie Not implemented exception Processing Request");
	}

	public function loadAndConfirmHashedCookie($cookieName, $value){
		$hashedValue = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : ""; 
		return crypt($value, $hashedValue) === $hashedValue; 
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