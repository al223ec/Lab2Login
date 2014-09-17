<?php 
namespace controller; 

require_once(ROOT_DIR . "/src/view/LoginView.php"); 
require_once(ROOT_DIR . "/src/model/Login.php"); 

	class LoginController {
		private $loginView; 
		private $loginModel; 

		public function __construct(){
			$this->loginModel = new \model\Login();
			$this->loginView = new \view\LoginView($this->loginModel);
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
				case \view\LoginView::LoggedIn : 
					return $this->loginView->loggedInView(); 
				default : 
					return $this->renderLoginForm();
			}
		}

		private function userIsloggingIn(){
			$un = $this->loginView->getUserName();
			$pw = $this->loginView->getPassword();

			if($un){
				$user = $this->loginModel->getUserFromDB($un, $pw); 
				if($user != null){ 
					//korrekt username
					if($user->isValid()){
						//korrekt usernamn och pass
						$this->loginView->loginUser($user);
						return "Logging in!";
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