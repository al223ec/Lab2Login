<?php 
namespace controller; 

require_once("./src/view/LoginView.php"); 
require_once("./src/model/DAL/LoginDAL.php"); 


	class LoginController {
		private $loginView; 
		private $model; 
		private $DAL; 
		private $errorMessages; 

		public function __construct(){
			$this->errorMessages = array();
			$this->DAL = new \DAL\LoginDAL(); 
			$this->loginView = new \view\LoginView($this->DAL, $this->errorMessages); 

			//$this->DAL->getUsers();
			$this->DAL->getUser("Admin");
			$this->DAL->getUser("Enanvädnare som inte finns"); 
		}

		public function login($un, $pw){
		}

		public function getLoginForm(){
			if($this->loginView->userIsLoggingIn()){
				if($this->DAL->checkUserName($this->loginView->getUserName()) && $this->DAL->checkPassword($this->loginView->getPassword())){
					//loginsuccessfull
				} 
				$this->DAL->checkPassword($this->loginView->getPassword());
			}
			return $this->loginView->getLoginForm();
		}
	}