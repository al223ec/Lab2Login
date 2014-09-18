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
				if($this->userIsLoggedInWithCookies() || $this->loginView->userIsLoggedIn()){
					return $this->userIsLoggedIn(); 
				}
				return $this->renderLoginForm();
		}
	}

	private function userIsLoggedIn(){
		if($this->cookieHandler->cookiesAreSet() && $this->cookieHandler->cookieExpiries()){
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
					$this->loginView->loginUser();
					$this->loginView->redirect(); 
					return "Logging in!";
				}
			}
			$this->loginView->populateErrorMessages($user);
		}
		return $this->renderLoginForm();  
	} 

	private function logout(){
		$this->cookieHandler->removeCookies(); 
		//LoginModel logout returnerar true om det finns en användare att logga ut
		//Datta för att kunna visa meddelande endast när det är relevant
		return $this->loginView->logout($this->loginModel->logout());
	}

	private function renderLoginForm(){
		return $this->loginView->renderLoginForm();
	}

 	/** 
 	*  Om det finns kakor hämtas dessa och valideras mot databasen
 	*	@return bool 
 	*	true om det finns kakor som är giltiga annars false
	*/
	private function userIsLoggedInWithCookies(){ 
		if($this->cookieHandler->cookiesAreSet()){

			$userName = $this->cookieHandler->loadUserNameCookie();
			$expiry = $this->cookieHandler->loadExpiry();
			
			$user = $this->loginModel->getUserFromDBWithCookie($userName, $this->cookieHandler->loadPasswordCookie(), $expiry); 
			if($user !== null && $expiry !== null && $user->isValid()){
				$this->loginView->loginUser($user);
				return true; 
			}else{
				//Något är fel på cookien dvs cookien måste ha blivit manipulerad
				$this->cookieHandler->removeCookies(); //Ta bort ev kakor
				$this->loginModel->logout();
				echo "Plz don\"t manipulate any cookie!"; 
				die(); 
			}
		} 
		return false;
	}
}