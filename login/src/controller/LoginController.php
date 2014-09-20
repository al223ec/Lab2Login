<?php 
namespace controller; 

require_once(ROOT_DIR . "/src/view/LoginView.php"); 
require_once(ROOT_DIR . "/src/model/Login.php"); 
require_once(ROOT_DIR . "/src/view/LoginCookieHandler.php"); 

class LoginController {

	private $loginView; 
	private $loginModel; 
	private $cookieHandler; 

	public function __construct(){
		$this->loginModel = new \model\Login();
		$this->loginView = new \view\LoginView($this->loginModel);
		$this->cookieHandler = new \view\LoginCookieHandler($this->loginModel); 
	}
	/*
	* Kontrollerar vilken action som ska genomföras
	*/
	public function performAction(){			
		$strAction = $this->loginView->getCurrentAction();

		switch($strAction){
			case \view\LoginView::ActionLoggingIn :
				return $this->userIsloggingIn();
			case \view\LoginView::ActionLoggingOut :
				return $this->logout();
			default : 
				//Kontrollera först om användaren redan är inloggad 
				if($this->loginView->userIsLoggedIn()){
					return $this->userIsLoggedIn(); 
				} else if($this->cookieHandler->cookiesAreSet()){ // annars kontroller cookies 
					return $this->userIsLoggedInWithCookies(); 
				}
				//Slutligen returnera ett vanligt loginform
				return $this->renderLoginForm();
		}
	}

	private function userIsLoggedIn(){
		if($this->cookieHandler->cookiesAreSet() && $this->cookieHandler->updateCookiesExpiry() && $this->cookieHandler->isCookiesValid()){
			$this->cookieHandler->saveCookies(); 
		}
		return $this->loginView->loggedInView(); 
	}

	private function userIsloggingIn(){
		$un = $this->loginView->getUserName();
		$pw = $this->loginView->getPassword();
		$user = null; 

		if($un){
			$user = $this->loginModel->getUserFromDB($un, $pw); 
			if($user != null){ 
				//korrekt username
				if($user->isValid()){
					//korrekt usernamn och pass
					if($this->loginView->getIsAutologinSet()){
						$this->cookieHandler->saveCookies(); 
					}
					$this->loginView->loginUser("Inloggningen lyckades!");
					//$this->loginView->redirect(); 
					return $this->userIsLoggedIn(); //"Logging in!";
				}
			}
			$this->loginView->populateErrorMessages($user);
		}
		return $this->renderLoginForm();  
	} 

	private function logout($prompt = ""){
		$this->cookieHandler->removeCookies(); 
		$this->loginView->logout($prompt);
		return $this->renderLoginForm(); 
	}

	private function renderLoginForm(){
		return $this->loginView->renderLoginForm();
	}

 	/** 
 	*  Det finns kakor hämta dessa och validera mot databasen, görs i cookieHandler
	*/
	private function userIsLoggedInWithCookies(){
		if($this->cookieHandler->isCookiesValid()){
			$this->loginView->loginUser("Inloggning lyckades via cookies!");
			return $this->userIsLoggedIn(); 
		} 
		return $this->logout("Du har manipulerat kakor din tomte!"); 
	}
}