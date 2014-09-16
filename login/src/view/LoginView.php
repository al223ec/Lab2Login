<?php

namespace view; 

require_once(ROOT_DIR . "/src/view/LoginCookieHandler.php"); 

class LoginView {
 	//TODO: http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
 	const UserName =  "LoginView::UserName";  
	const Password =  "LoginView::Password"; 
	const AutoLogin = "LoginView::AutoLogin"; 

 	private $errorMessages;
	//erromessage nycklar
	const PasswordError = "PasswordError"; 
	const UserNameError = "UserNameError"; 

	//Actions
	const Action = "a"; 
	const ActionLoggingIn = "login"; 
	const ActionLoggingOut = "logout";
	const LoggedIn = "loggedIn"; 

	private $cookieHandler; 
	private $loginModel; 

	public function __construct(\model\Login $loginModel) {
		$this->loginModel = $loginModel; 
		$this->errorMessages = array(); 
		$this->cookieHandler = new LoginCookieHandler($loginModel); 
	}

	//Denna bör flyttas till model, UserSession model
	private function userIsLoggedIn(){
		return $this->loginModel->isUserLoggedIn($_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]) || $this->getAndVerifyUserByCookies(); 
	}

	public function getCurrentAction(){
		$action = isset($_GET[self::Action]) ? $_GET[self::Action] : "";
		if($this->userIsLoggedIn() && $action === ""){
			 $action = self::LoggedIn; 
		}
		return  $action; 
	}

	public function renderLoginForm($prompt = ""){
 		return 
 			$this->getHeader("Ej Inloggad") . "		  	
			<form action='?". self::Action ."=". self::ActionLoggingIn ."' method='post' enctype='multipart/form-data'>
				<fieldset>
					<legend>Login - Skriv in användarnamn och lösenord</legend>
					". $prompt."
					<label for='UserNameID' >Användarnamn :</label>
					<input type='text' size='20' name='" . self::UserName ."' id='UserNameID' value='' />" 
					. $this->getErrorMessages(self::UserNameError) .

					"<label for='PasswordID' >Lösenord  :</label>
					<input type='password' size='20' name='" . self::Password ."' id='PasswordID' value='' />" 
					. $this->getErrorMessages(self::PasswordError) . 

					"<label for='AutologinID' >Håll mig inloggad  :</label>
					<input type='checkbox' name='" . self::AutoLogin ."' id='AutologinID' />
					<input type='submit' name=''  value='Logga in' />
				</fieldset>
			</form>"
			 . $this->getFooter(); 
	}

	public function loggedInView(){
		if($this->cookieHandler->cookiesAreSet() && $this->cookieHandler->checkIfCookieExpiries()){
			$this->cookieHandler->saveCookies(); 
		}

		$userName = $this->loginModel->getUserName();
		return $this->getHeader("$userName är inloggad") . "<a href='?" . self::Action ."=". self::ActionLoggingOut ."'>Logga ut</a>" . $this->getFooter();
	}

	public function loginUser($user){
		$this->loginModel->saveSession($user, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]); 
		if($this->getIsAutologinSet()){
			$this->cookieHandler->saveCookies(); 
		} 
		header("Location: " . $_SERVER["PHP_SELF"]); 
	}

 	private function getIsAutologinSet(){
 		return isset($_POST[self::AutoLogin]); 
 	}

	private function getAndVerifyUserByCookies(){
		$userName = $this->cookieHandler->loadUserNameCookie(); 
		if($userName !== ""){
			$user = $this->loginModel->getUserFromDBWithCookie($userName, $this->cookieHandler->loadPasswordCookie()); 
			if($user !== null && $user->isValid()){
				$this->loginModel->saveSession($user, $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
				return true; 
			}else{
				//Hittar inte användarnamnet som sparats i cookien dvs cookien måste ha blivit manipulerad
				$this->cookieHandler->removeCookies(); //Ta bort ev kakor
				$this->loginModel->logout();
				echo "<script language='javascript'>";
				echo "alert('Plz don\"t manipulate any cookie!')";
				echo "</script>";
			}
		} 
		return false;
	}


	public function logout(){
		$this->cookieHandler->removeCookies(); 
		if($this->loginModel->logout()){
  			return $this->renderLoginForm("<p>Du har nu loggat ut!</p>"); 
		}
  		return $this->renderLoginForm(); 
	}	

	private function getHeader($prompt){
 		return "<h1>Laborationskod xx222aa</h1><h2>$prompt</h2>"; 		
	}

	private function getFooter(){
		return "<p> ". strftime("%A") . " Den " .  strftime("%d %B") . " " . " år " . strftime("%Y") .  ". Klockan är [" . strftime("%H:%M:%S") ."]</p>"; 
	}


	//Use $this to refer to the current object. Use self to refer to the current class. 
	//In other words, use $this->member for non-static members, use self::$member for static members.
	public function getUserName(){
		$ret = $this->getCleanInput(self::UserName);
		if($ret === ""){
			$this->errorMessages[self::UserNameError] = "Användarnamnet saknas";
		} 
		return $ret; 
	}

	public function getPassword(){
		$ret = $this->getCleanInput(self::Password);
		if($ret === ""){
			$this->errorMessages[self::PasswordError] = "Lösenordet saknas";
		}
		return $ret; 
	}
	/**
    * @param String input
    * @return String input - tags - trim
    * @throws Exception if something is wrong or input does not exist
    */
	private function getCleanInput($inputName) {
		return isset($_POST[$inputName]) ? $this->sanitize($_POST[$inputName]) : "";
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