<?php 
namespace controller; 

require_once(ROOT_DIR . "/src/view/LoginView.php"); 
require_once(ROOT_DIR . "/src/model/DAL/LoginDAL.php"); 

	class LoginController {
		private $loginView; 
		private $model; 
		private $DAL; 

		public function __construct(){
			$this->DAL = new \DAL\LoginDAL();
			$this->loginView = new \view\LoginView($this->DAL);
		}
		/*
		* Kontrollerar vilken action som ska genomföras
		*/
		public function performAction(){			
			$strAction = isset($_GET['a']) ? $_GET['a'] : "";

			switch($strAction){
				case \view\LoginView::ActionLoggingIn :
					return $this->login();
				case \view\LoginView::ActionLoggingOut :
					return $this->logout();
			}

			if($this->loginView->userIsLoggedIn()){
				return $this->loginView->loggedInView();
			}
			return $this->renderLoginForm();	
		}

		private function login(){
			$un = $this->loginView->getUserName();
			$pw = $this->loginView->getPassword();

			if($un){
				$user = $this->DAL->getUser($un, $pw); 
				if($user != null){ 
					//korrekt username
					if($user->isValid()){
						//korrekt usernamn och pass
						$this->loginView->saveUserLoggedInSession();
						return "Logging in ";
						//return $this->loginView->logingSuccessFull($user); 
					} else {
						$this->loginView->addErrorMessage(\view\LoginView::PasswordError, "Felaktigt lösenord"); 
					}	
				}else{
					$this->loginView->addErrorMessage(\view\LoginView::UserNameError, "Felaktigt användarnamn"); 		
				}
			}
			return $this->renderLoginForm();  
		} 

		private function logout(){
			return $this->loginView->logout();
		}

		private function renderLoginForm(){
			return $this->loginView->renderLoginForm();
		}
	}