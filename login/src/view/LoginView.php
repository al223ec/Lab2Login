<?php

namespace view; 

class LoginView{
 
 	//Kanske bör förändra dessa till ej statiska
 	private static $UserName =  "LoginView::UserName";  
	private static $Password =  "LoginView::Password"; 
	private static $AutoLogin = "LoginView::AutoLogin"; 

	private static $cookieName = "LoginView::IsLoggedIn"; 
	private static $sessionName = "LoginView::IsLoggedIn"; 

	private $errorMessages;
	//erromessage nycklar
	const PasswordError = "PasswordError"; 
	const UserNameError = "UserNameError"; 

	//Actions
	const ActionLoggingIn = "login"; 
	const ActionLoggingOut = "logout";  


	private $loginDAL; 

	public function __construct($loginDAL) {
		$this->loginDAL = $loginDAL; 
		$this->errorMessages = array(); 
	}
	public function userIsLoggedIn(){
//		isset($_SESSION[self::$sessionName]) ? var_dump($_SESSION[self::$sessionName]) : ''; 
		return isset($_SESSION[self::$sessionName]); 
	}

	public function userIsLoggingIn(){
		return isset($_GET[self::ActionLoggingIn]);
	}
	
	public function userIsLoggingOut(){
		return isset($_GET[self::ActionLoggingOut]);
	}

	public function renderLoginForm($prompt = ""){
 		return 
 			$this->getHeader("Ej Inloggad") . "		  	
			<form action='?a=". self::ActionLoggingIn ."' method='post' enctype='multipart/form-data'>
				<fieldset>
					<legend>Login - Skriv in användarnamn och lösenord</legend>
					$prompt
					<label for='UserNameID' >Användarnamn :</label>
					<input type='text' size='20' name='" . self::$UserName ."' id='UserNameID' value='' />" 
					. $this->getErrorMessages(self::UserNameError) .

					"<label for='PasswordID' >Lösenord  :</label>
					<input type='password' size='20' name='" . self::$Password ."' id='PasswordID' value='' />" 
					. $this->getErrorMessages(self::PasswordError) . 

					"<label for='AutologinID' >Håll mig inloggad  :</label>
					<input type='checkbox' name='" . self::$AutoLogin ."' id='AutologinID' />
					<input type='submit' name=''  value='Logga in' />
				</fieldset>
			</form>"
			 . $this->getFooter(); 
	}

	public function loggedIn(){
		return $this->getHeader("Du Är inloggad") . "<a href='?a=". self::ActionLoggingOut ."'>Logga ut</a>" . $this->getFooter();
	}

	public function saveUserLoggedInSession(){
		$_SESSION[self::$sessionName] = $this->loginDAL->getCurrentUser();
		header("Location: " . $_SERVER["PHP_SELF"]); 
	}
	public function logout(){
		if(isset($_SESSION[self::$sessionName])){
  			unset($_SESSION[self::$sessionName]);
  			session_destroy(); //Bara för att, antar jag. 
  			return $this->renderLoginForm("<p>Du har nu loggat ut!</p>"); 
		}
  		return $this->renderLoginForm(); 
	}	

	private function getHeader($prompt){
 		return "<h1>Laborationskod xx222aa</h1><h2>$prompt</h2>"; 		
	}
	private function getFooter(){
		return "<p> ". (new \DateTime())->format('Y-m-d H:i:sP') . "</p>"; 
	}


	//Use $this to refer to the current object. Use self to refer to the current class. 
	//In other words, use $this->member for non-static members, use self::$member for static members.
	public function getUserName(){
		$ret = $this->getCleanInput(self::$UserName);
		if($ret === ""){
			$this->errorMessages[self::UserNameError] = "Användarnamnet saknas";
		} 
		return $ret; 
	}

	public function getPassword(){
		$ret = $this->getCleanInput(self::$Password);
		if($ret === ""){
			$this->errorMessages[self::PasswordError] = "Lösenordet saknas";
		}
		return $ret; 
	}

	public function addErrorMessage($key, $errorMessage){
		if($key === self::PasswordError || $key === self::UserNameError){
			$this->errorMessages[$key] = $errorMessage; 
		} else { 
			throw new \Exception("LoginView::addErrorMessage fel nyckel skickad till funktionen!!");
		}
	}
	/**
    * @param String input
    * @return String input - tags - trim
    * @throws Exception if something is wrong or input does not exist
    */
	private function getCleanInput($inputName) {
		if (isset($_POST[$inputName]) == false) {
	    	return "";
		}
	    return $this->sanitize($_POST[$inputName]);
	}
 
    /**
    * @param String input
    * @return String input - tags - trim
    */
    private function sanitize($input) {
        $temp = trim($input);
        //filter_var — Filters a variable with a specified filter
        //FILTER_FLAG_STRIP_LOW - Strip characters with ASCII value below 32
        //The FILTER_SANITIZE_STRING filter strips or encodes unwanted characters.
		//This filter removes data that is potentially harmful for your application. 
		//It is used to strip tags and remove or encode unwanted characters.
        return filter_var($temp, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
    }
	
	private function getErrorMessages($key){
		if (isset($this->errorMessages[$key])) {
			return "<span> " . $this->errorMessages[$key] . " </span>"; 
		}
	}
}	 	