<?php 
namespace controller; 

require_once("./src/view/LoginView.php"); 
require_once("./src/model/DAL/LoginDAL.php"); 

	class LoginController {
		private $loginView; 
		private $model; 
		private $DAL; 

		public function __construct(){
			$this->DAL = new \DAL\LoginDAL(); 
			$this->loginView = new \view\LoginView($this->DAL);
		}
		/*
		*
		*/
		public function getLoginForm(){		
			
			$strAction = isset($_GET['a']) ? $_GET['a'] : "";
			switch($strAction){
				case 'login':
					return $this->login();
					break;
				case 'logout':
					return $this->logout();
					break;
			}

			if($this->loginView->userIsLoggedIn()){
				return $this->loginView->loggedIn();
			}else{
				return $this->renderForm();	
			}
		}

		private function login(){
			$un = $this->loginView->getUserName();
			$pw = $this->loginView->getPassword();

			if($un){
				$user = $this->DAL->getUserByUserName($un);
				var_dump($user);
				if($user != null){ 
					//korrekt username
					if($user->authenticate($pw)){
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
			return $this->renderForm();  
		} 

		private function logout(){
			$this->loginView->logout(); 
			return "logged out"; 
		}
		private function renderForm(){
			return $this->loginView->getLoginForm();
		}
	}