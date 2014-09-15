<?php 

namespace model; 

class LoginSessionHandler{
	
	private static $userIndex = "SESSION::USER"; 
	private static $clientIpIndex = "SESSION::CLIENTIP"; 
	private static $clientBrowserAgentIndex = "SESSION::BROWSERAGENT";

	public function __construct(){
		if(!isset($_SESSION))
			throw new \Exception("LoginSessionHandler::construct SESSION finns inte!", 1);			
	}

	public function saveSession($user, $clientIp, $clientBrowserAgent){
		$_SESSION[self::$clientIpIndex] = $clientIp; 
		$_SESSION[self::$clientBrowserAgentIndex] = $clientBrowserAgent; 
		$_SESSION[self::$userIndex] = $user; 
	}

	public function getUserFromSession(){
		$ip = isset($_SESSION[self::$clientIpIndex]) ? $_SESSION[self::$clientIpIndex] : null; 
		$browser = isset($_SESSION[self::$clientBrowserAgentIndex]) ? $_SESSION[self::$clientBrowserAgentIndex] : null;  
		$user = isset($_SESSION[self::$userIndex]) ? $_SESSION[self::$userIndex] : null; 

		if($ip === null || $browser === null || $user === null){
			return null; 
		}
		return $user; 
	}

	public function removeSession(){
		if(isset($_SESSION[self::$userIndex])){
			unset($_SESSION[self::$userIndex]); 
			return true; 
		}
		return false; 
	}
}