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

			//$this->DAL->getUsers();
			$this->DAL->getUser("Admin");
			$this->DAL->getUser("Enanvädnare som inte finns"); 
		}

		public function login($un, $pw){
		}

		public function getLoginForm(){

			if($this->loginView->userIsLoggingIn()){
				$this->DAL->checkUserName($this->loginView->getUserName()); 

				if ($this->loginView->loginSuccessFull()) {
				 	# code...
				 }else{

				 }
			}
			return $this->loginView->getLoginForm();
		}
	}