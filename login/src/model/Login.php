<?php 

namespace model; 

require_once(ROOT_DIR . "/src/model/DAL/UserDAL.php");
require_once(ROOT_DIR . "/src/model/LoginSessionHandler.php");

	class Login {

		private $userDAL; 
		private $loginSessionHandler;
		private $currentUser; 

		public function __construct(){
			$this->userDAL = new \DAL\UserDAL(); 
			$this->loginSessionHandler = new LoginSessionHandler(); 
		}

		public function saveSession($user, $clientIp, $clientBrowserAgent){
			$this->currentUser = $user; 
			$this->loginSessionHandler->saveSession($this->currentUser, $clientIp, $clientBrowserAgent); 
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
		public function getUserName(){
			return isset($this->currentUser) ?  $this->currentUser->getUserName() : ""; 
		}

		public function isUserLoggedIn($clientIp, $clientBrowserAgent){
			$this->currentUser = $this->loginSessionHandler->getUserFromSession($clientIp, $clientBrowserAgent); 
			return $this->currentUser !== null; 
		}
		public function logout(){
			$this->currentUser = null; 
			return $this->loginSessionHandler->removeSession(); 
		}
    	/**
		* @return True ifall uppdateringen lyckades 	
    	*/
		public function saveCookieValueToDB($cookieValue){
			return $this->userDAL->saveCookieValue($this->currentUser->getUserID(), $cookieValue); 
		}

		public function getUserByUserName($userName){
			return $this->userDAL->getUserByUserName($userName); 
		}
	
	}