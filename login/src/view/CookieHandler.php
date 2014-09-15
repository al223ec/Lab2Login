<?php 

namespace view; 

class CookieHandler{
	
	private $secondsToExperation = 180; 

	public function saveCookie($cookieName, $cookieValue){
		$this->setMyCookie($cookieName, $cookieValue); 
	} 

	public function saveCookieAndReturnValue($cookieName){
		$cookieValue = $this->generateRandomString(); 
		$this->setMyCookie($cookieName, $cookieValue); 
		return $cookieValue; 
	} 
	private function setMyCookie($cookieName, $cookieValue){
		$expiry = time() + $this->secondsToExperation;
		$cookieData = (object) array( "cookieValue" => $cookieValue, "expiry" => $expiry );
		setcookie($cookieName, json_encode( $cookieData ), $expiry, "/"); 
	}

	public function removeCookie($cookieName){
		setcookie($cookieName, "", time() -100); 
	}

	public function checkIfCookieExpiries($minutes){
		$seconds = $minutes * 60; 
	//	$expiry = isset($_COOKIE[$cookieName]) ? json_decode($_COOKIE[$cookieName])->expiry : 0;
	//	if($expiry === false)
			return; 



	}
	public function loadCookie($cookieName){
		return isset($_COOKIE[$cookieName]) ? json_decode($_COOKIE[$cookieName])->cookieValue : ""; 
	}

	private function generateRandomString($length = 18) {
 	   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}
}