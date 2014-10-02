<?php 

namespace model; 

require_once(ROOT_DIR . "/src/model/DAL/UserDAL.php");
require_once(ROOT_DIR . "/src/model/session/LoginSessionHandler.php");

class Login {

	private $userDAL; 
	private $loginSessionHandler;
	private $currentUser = null; //Om det är en användare inloggad sparas hen här, endast validerade users kommer att sparas

	public function __construct(){
		$this->userDAL = new \DAL\UserDAL(); 
		$this->loginSessionHandler = new LoginSessionHandler(); 
	}

	public function saveSession($clientIp, $clientBrowserAgent, $rememberUser){
		$this->loginSessionHandler->saveSession($this->currentUser, $clientIp, $clientBrowserAgent, $rememberUser); 
	}
	public function setReadOnceMessage($message){
		$this->loginSessionHandler->setReadOnceMessage($message); 
	}
	public function getReadOnceMessage(){
		return $this->loginSessionHandler->getReadOnceMessage(); 
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

	public function ceckSessionAndLoadUserFromSession($clientIp, $clientBrowserAgent){
		$this->currentUser = $this->loginSessionHandler->getUserFromSession($clientIp, $clientBrowserAgent); 
		return $this->currentUser !== null; 
	}

	public function logout($clientIp, $clientBrowserAgent){
		$currentUser = $this->loginSessionHandler->getUserFromSession($clientIp, $clientBrowserAgent); 
		if($currentUser !== null){
			$this->userDAL->saveCookieValue($currentUser->getUserID(), null, 0);
		}
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