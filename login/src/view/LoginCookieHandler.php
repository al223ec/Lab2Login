<?php 

namespace view; 

class LoginCookieHandler {
	
	private $loginModel; 

	private $secondsToExperation = 1800; //Default 30 min
	//Updateringsinterval sekunder
	private $updateCookieInterval = 10;  

	public function __construct(\model\Login $loginModel) {
		$this->loginModel = $loginModel; 
	}

	public function cookiesAreSet(){
		return isset($_COOKIE[LoginView::Password]) || isset($_COOKIE[LoginView::UserName]);   
	}

	public function removeCookies(){
		$this->removeCookie(LoginView::UserName); 
		$this->removeCookie(LoginView::Password); 	
	}
	private function removeCookie($cookieName){
		setcookie($cookieName, "", 1, "/"); 
	}

	/**
	*Sparar cookie funktioner
	*/
	public function saveCookies(){
		$this->setMyCookie(LoginView::UserName, $this->loginModel->getUserName()); 
		$this->loginModel->saveCookieValueToDB($this->saveCookieAndReturnValue(LoginView::Password)); 
	}

	/**
	* @return En random sträng som måste sparas i databasesn
	*/
	private function saveCookieAndReturnValue($cookieName){
		$cookieValue = $this->generateRandomString(); 
		$this->setMyCookie($cookieName, $cookieValue); 
		return $cookieValue; 
	} 

	private function setMyCookie($cookieName, $cookieValue){
		$expiry = time() + $this->secondsToExperation;
		$cookieData = (object) array( "cookieValue" => $cookieValue, "expiry" => $expiry );
		setcookie($cookieName, json_encode( $cookieData ), $expiry, "/"); 
	}
	/**
	* @param Längden på strängen 
	* @return En random sträng
	*/
	private function generateRandomString($length = 18) {
 	   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}

	//Läs cookie funktioner
	/**
	*Kontroller om cookien expires
	* @param $sekunder = Antal sekunder default updateCookieInterval
	*/ 
	public function checkIfCookieExpiries($seconds = 0){
		if($seconds === 0){
			$seconds = $this->updateCookieInterval;
		}

		$expiryTimeToCheck = time() + $this->secondsToExperation - $seconds; 
		$object = isset($_COOKIE[LoginView::Password]) && isset($_COOKIE[LoginView::UserName]) ? json_decode($_COOKIE[LoginView::Password]) : null; 
		$expiry = isset($object->expiry) ? $object->expiry : 0; 

		if(!is_numeric($expiry) || $expiry === 0){ //Något är fel, det finns ingen cookie eller så har den blivit manipulerad
			$this->removeCookies(); //Ta bort ev kakor
			$this->loginModel->logout(); //kill the session!! 
			return false; 
		}

		if($expiry < $expiryTimeToCheck){
			return true; 	
		}
		return false; 
	}	

	public function loadUserNameCookie(){
		return $this->loadCookie(LoginView::UserName); 
	}
	public function loadPasswordCookie(){
		return $this->loadCookie(LoginView::Password); 
	}
	private function loadCookie($cookieName){
		$object = isset($_COOKIE[$cookieName]) ? json_decode($_COOKIE[$cookieName]) : null;
		return isset($object->cookieValue) ? $object->cookieValue : ""; 
	}
}