<?php 

namespace view; 

class LoginCookieHandler {
	/**Hanterar endast kakor för LoginView
	 * Uppdaterar kakorna när intervallet har passerat, detta för att starta tiden till AutoLogout till sista aktivitet från användaren
	 */
	
	private $loginModel; 
	private $secondsToExperation = 1800; //Default 300 min
	//Updateringsinterval sekunder
	private $updateCookieInterval = 180;  
	private $expiry = 0; 

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
		$this->loginModel->saveCookieValueToDB($this->saveCookieAndReturnValue(LoginView::Password), $this->expiry); 
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
		$this->expiry = time() + $this->secondsToExperation;
		$cookieData = (object) array( "cookieValue" => $cookieValue, "expiry" => $this->expiry );
		setcookie($cookieName, json_encode( $cookieData ), $this->expiry, "/"); 
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
	* Kontroller om cookien expires
	 * @param $sekunder = Antal sekunder default updateCookieInterval
	 */ 
	public function cookieExpiries($seconds = 0){
		if($seconds === 0){
			$seconds = $this->updateCookieInterval;
		}

		$expiryTimeToCheck = time() + $this->secondsToExperation - $seconds; 
		$expiry = $this->loadExpiry();

		if(!is_numeric($expiry)){ //Något är fel, det finns ingen cookie eller så har den blivit manipulerad
			$this->removeCookies(); //Ta bort ev kakor
			$this->loginModel->logout(); //kill the session!! 
			return false; 
		}

		if($expiry < $expiryTimeToCheck){
			return true; 	
		}
		return false; 
	}	
	/** 
	* Hämtar vilken sekund som kakorna expires kontrollerar även att de skapdes samma sekund
	 * @return null eller antalet sekunder
	 */
	public function loadExpiry(){
		$passwordObj = isset($_COOKIE[LoginView::Password])  ? json_decode($_COOKIE[LoginView::Password]) : null; 
		$userNameObj = isset($_COOKIE[LoginView::UserName]) ? json_decode($_COOKIE[LoginView::UserName]) : null; 

		if($passwordObj !== null && $userNameObj !== null){
			$passwordExpiry = isset($passwordObj->expiry) ? $passwordObj->expiry : null; 
			$userNameExpiry = isset($userNameObj->expiry) ? $userNameObj->expiry : null; 
			if(is_numeric($passwordExpiry) && is_numeric($userNameExpiry) && $passwordExpiry === $userNameExpiry){
				return $passwordExpiry; 
			}
		}
		return null; 
	}

	public function loadUserNameCookie(){
		return $this->loadCookieValue(LoginView::UserName); 
	}
	public function loadPasswordCookie(){
		return $this->loadCookieValue(LoginView::Password); 
	}
	private function loadCookieValue($cookieName){
		$object = isset($_COOKIE[$cookieName]) ? json_decode($_COOKIE[$cookieName]) : null;
		return isset($object->cookieValue) ? $object->cookieValue : ""; 
	}
}