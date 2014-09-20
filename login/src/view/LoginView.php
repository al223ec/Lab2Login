<?php

namespace view; 

require_once(ROOT_DIR . "/src/view/LoginCookieHandler.php"); 
require_once(ROOT_DIR . "/myExtensions/MyDate.php");

class LoginView {

 	const UserName =  "LoginView::UserName";  
	const Password =  "LoginView::Password"; 
	const AutoLogin = "LoginView::AutoLogin"; 

 	private $errorMessages;
	//erromessage nycklar
	const PasswordErrorKey = "PasswordError"; 
	const UserNameErrorKey = "UserNameError"; 

	//Actions
	const Action = "a"; 
	const ActionLoggingIn = "login"; 
	const ActionLoggingOut = "logout";

	private $loginModel; 
	private $prompt = ""; 

	public function __construct(\model\Login $loginModel) {
		$this->loginModel = $loginModel; 
		$this->errorMessages = array(); 
	}

	public function getCurrentAction(){
		return isset($_GET[self::Action]) ? $_GET[self::Action] : "";
	}

	public function userIsLoggedIn(){
		return $this->loginModel->ceckSessionAndLoadUserFromSession($_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);  
	}

	public function renderLoginForm(){
		$prompt = $this->prompt; 
 		return 
 			$this->getFormHeader("Ej Inloggad") . "		  	
			<form action='?". self::Action ."=". self::ActionLoggingIn ."' method='post' enctype='multipart/form-data'>
				<fieldset>
					<legend>Login - Skriv in användarnamn och lösenord</legend>
					<p>". $prompt."</p>
					<label for='UserNameID' >Användarnamn :</label>
					<input type='text' size='20' name='" . self::UserName ."' id='UserNameID' value='". $this->getCleanInput(self::UserName) . "' />
					<span class='errormessage'>" . $this->getErrorMessages(self::UserNameErrorKey) ."</span>

					<label for='PasswordID' >Lösenord  :</label>
					<input type='password' size='20' name='" . self::Password ."' id='PasswordID' value='' />
					<span class='errormessage'>" . $this->getErrorMessages(self::PasswordErrorKey) . "</span>
					
					<label for='AutologinID' >Håll mig inloggad  :</label>
					<input type='checkbox' name='" . self::AutoLogin ."' id='AutologinID' />
					<input type='submit' name=''  value='Logga in' />
				</fieldset>
			</form>"
			. $this->getFormFooter(); 
	}

	public function loggedInView(){
		$userName = $this->loginModel->getUserName();
		$rememberMeIsSetMessage = $this->loginModel->isRememberUserSet() ? " Vi kommer ihåg dig till nästa gång" : "";
		$message = "<p>" . $this->prompt   . $rememberMeIsSetMessage . "</p>"; 

		return $this->getFormHeader("$userName är inloggad") 
			. $message . 
			"<a href='?" . self::Action ."=". self::ActionLoggingOut ."'>Logga ut</a>" 
			. $this->getFormFooter();
	}

	public function loginUser($prompt = ""){
		$this->prompt = $prompt; 
		$this->loginModel->saveSession($_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"], $this->getIsAutologinSet()); 
	}

	public function populateErrorMessages($user){
		if($user === null){ 
			$this->errorMessages[self::UserNameErrorKey] = "Felaktigt användarnamn"; 	
		} else if(!$user->isValid()){
			$this->errorMessages[self::PasswordErrorKey] = "Felaktigt lösenord"; 
		}

	}
	public function redirect(){
		header("Location: " . $_SERVER["PHP_SELF"]); 
	}

	public function logout($prompt = ""){
		$this->prompt = $prompt; 
		//LoginModel logout returnerar true om det finns en användare att logga ut
		//Detta för att kunna visa meddelande endast när det är relevant
		$displayMessage = $this->loginModel->logout($_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]); 
		if($displayMessage){
			$this->prompt =  "Du har nu loggat ut!"; 
  		}
  	}	

	private function getFormHeader($header){
 		return "<h1>Laboration 2 al223ec</h1><h2>$header</h2>"; 		
	}

	private function getFormFooter(){
		return "<p> ". \myExtensions\MyDate::getDayName() . " den " .  strftime("%d") . " "
		. \myExtensions\MyDate::getMonthName() . " år " . strftime("%Y") .  ". Klockan är [" . strftime("%H:%M:%S") ."]</p>"; 
	}

	public function getUserName(){
		$ret = $this->getCleanInput(self::UserName);
		if($ret === ""){
			$this->errorMessages[self::UserNameErrorKey] = "Användarnamnet saknas";
		} 
		return $ret; 
	}

	public function getPassword(){
		$ret = $this->getCleanInput(self::Password);
		if($ret === ""){
			$this->errorMessages[self::PasswordErrorKey] = "Lösenordet saknas";
		}
		return $ret; 
	}

	public function getIsAutologinSet(){
 		return isset($_POST[self::AutoLogin]); 
 	}

	/**
    * @param String input
    * @return String input - tags - trim
    * @throws Exception if something is wrong or input does not exist
    */
	private function getCleanInput($inputName) {
		return isset($_POST[$inputName]) ? $this->sanitize($_POST[$inputName]) : "";
	}
	/* Fuktion för att lägga till errormessages utanför klassen
	*
	private function addErrorMessage($key, $errorMessage){
		if($key === self::PasswordError || $key === self::UserNameError){
			$this->errorMessages[$key] = $errorMessage; 
		} else { 
			throw new \Exception("LoginView::addErrorMessage fel nyckel skickad till funktionen!!");
		}
	}
 	*/
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