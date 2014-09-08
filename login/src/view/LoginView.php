<?php
//DBPassword un: LoginUser
namespace view; 

class LoginView{
 
 	//Kanske bör förändra dessa till
 	private static $UserName =  "LoginView::UserName";  
	private static $Password =  "LoginView::Password"; 
	private static $AutoLogin = "LoginView::AutoLogin"; 

	private $isLogginIn = "login";
	private $errorMessages = array();

	private $loginDAL; 

	public function __construct($loginDAL) {
		$this->loginDAL = $loginDAL; 
	}

	public function userIsLoggingIn(){
		return isset($_GET[$this->isLogginIn]) ? true : false;
	}

	public function loginSuccessFull(){
		return $this->loginDAL->checkCredentials($this->getUserName(), $this->getPassword()); 
	}
	public function checkCredentials(){
		$un = getUserName();
		$pw = getPassword(); 
		if(!$un || !$pw){
			return; 
		}
	}

	public function getLoginForm(){
 		return "
		    <h1>Laborationskod xx222aa</h1><h2>Ej Inloggad</h2>				  	
			<form action='?login' method='post' enctype='multipart/form-data'>
				<fieldset>
					<legend>Login - Skriv in användarnamn och lösenord</legend>
					<label for='UserNameID' >Användarnamn :</label>
					<input type='text' size='20' name='" . self::$UserName ."' id='UserNameID' value='' />" 
					. $this->getErrorMessages("UserNameError") .

					"<label for='PasswordID' >Lösenord  :</label>
					<input type='password' size='20' name='" . self::$Password ."' id='PasswordID' value='' />" 
					. $this->getErrorMessages("PasswordError") . 

					"<label for='AutologinID' >Håll mig inloggad  :</label>
					<input type='checkbox' name='" . self::$AutoLogin ."' id='AutologinID' />
					<input type='submit' name=''  value='Logga in' />
				</fieldset>
			</form>
			<p> ". (new \DateTime())->format('Y-m-d H:i:sP') . "</p>"; 
	}

	//Use $this to refer to the current object. Use self to refer to the current class. 
	//In other words, use $this->member for non-static members, use self::$member for static members.
	public function getUserName(){
		$ret = $this->getCleanInput(self::$UserName);
		if($ret === ""){
			$this->errorMessages["UserNameError"] = "Användarnamnet saknas";
		} else if (!$this->loginDAL->ceckIfUserNameExists($ret)) {
			$this->errorMessages["UserNameError"] = "Felaktigt användarnamn";
		}
		return $ret; 
	}

	public function getPassword(){
		$ret = $this->getCleanInput(self::$Password);
		if($ret === ""){
			$this->errorMessages["PasswordError"] = "Lösenordet saknas";
		} else if($this->loginDAL->passwordIsNotCorrect()){
			$this->errorMessages["PasswordError"] = "Fel lösenord";
		}
		return $ret; 
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