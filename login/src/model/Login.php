<?php 

namespace model; 

require_once(ROOT_DIR . "/src/model/DAL/UserDAL.php");
require_once(ROOT_DIR . "/src/model/LoginSessionHandler.php");

class Login {

	private $userDAL; 
	private $loginSessionHandler;
	private $currentUser; //Om det är en användare inloggad sparas hen här

	public function __construct(){
		$this->userDAL = new \DAL\UserDAL(); 
		$this->loginSessionHandler = new LoginSessionHandler(); 
	}

	public function saveSession($user, $clientIp, $clientBrowserAgent, $rememberUser){
		$this->currentUser = $user; 
		$this->loginSessionHandler->saveSession($this->currentUser, $clientIp, $clientBrowserAgent, $rememberUser); 
	}

	/**
	* @return Null eller ett user object 
	*/
	public function getUserFromDB($userName, $password){
		$user = $this->userDAL->getUserByUserName($userName); 
		if ($user != null){
			$user->validate($password);
    		if($user->isValid()){
    			$this->currentUser = $user; 
    		}
    	}
    	return $user;     		
	}

	public function getUserFromDBWithCookie($userName, $cookieValue, $cookieExpiration){
		$user = $this->userDAL->getUserByUserName($userName); 
		if ($user != null){
			$user->validateByCookieValue($cookieValue, $cookieExpiration);
    		if($user->isValid()){
    			$this->currentUser = $user; 
    		}
    	}
    	return $user; 
	}

	public function getUserName(){
		return isset($this->currentUser) ?  $this->currentUser->getUserName() : ""; 
	}

	public function isUserLoggedIn($clientIp, $clientBrowserAgent){
		$this->currentUser = $this->loginSessionHandler->getUserFromSession($clientIp, $clientBrowserAgent); 
		return $this->currentUser !== null; 
	}

	public function logout(){
		if($this->currentUser !== null){
			$this->userDAL->saveCookieValue($this->currentUser->getUserID(), null);
		}
		$this->currentUser = null; 
		return $this->loginSessionHandler->removeSession(); 
	}
	/**
	* @return True ifall uppdateringen lyckades 	
	*/
	public function saveCookieValueToDB($cookieValue, $cookieExpiration = 0){
		return $this->userDAL->saveCookieValue($this->currentUser->getUserID(), $cookieValue, $cookieExpiration); 
	}

	public function isRememberUserSet(){
		return $this->loginSessionHandler->isRememberUserSet(); 
	}
	public function getExpiryTimeFromUser(){
		if($this->currentUser !== null){
			return $this->currentUser->getCookieExpiration(); 
		}
	}
}