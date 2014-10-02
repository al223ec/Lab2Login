<?php 

namespace model; 

class LoginSessionHandler{
	
	private static $userIndex = "SESSION::USER"; 
	private static $clientIpIndex = "SESSION::CLIENTIP"; 
	private static $clientBrowserAgentIndex = "SESSION::BROWSERAGENT";
	private static $rememberUserIndex = "SESSION::REMEMBERUSER"; 
	private static $readOnceMessage = "SESSION::READONCE"; 

	public function __construct(){
		if(!isset($_SESSION))
			throw new \Exception("LoginSessionHandler::construct SESSION finns inte!", 1);			
	}

	public function saveSession($user, $clientIp, $clientBrowserAgent, $rememberUser){
		$_SESSION[self::$clientIpIndex] = $clientIp; 
		$_SESSION[self::$clientBrowserAgentIndex] = $clientBrowserAgent; 
		$_SESSION[self::$userIndex] = $user; 
		if($rememberUser){
			$_SESSION[self::$rememberUserIndex] = $rememberUser; 
		}
	}

	public function setReadOnceMessage($message){
		$_SESSION[self::$readOnceMessage] = $message; 
	}
	public function getReadOnceMessage(){
		$ret = isset($_SESSION[self::$readOnceMessage]) ? $_SESSION[self::$readOnceMessage] : ""; 
		unset($_SESSION[self::$readOnceMessage]); 
		return $ret;   
	}

	public function getUserFromSession($clientIp, $clientBrowserAgent){
		$ip = isset($_SESSION[self::$clientIpIndex]) ? $_SESSION[self::$clientIpIndex] : null; 
		$browser = isset($_SESSION[self::$clientBrowserAgentIndex]) ? $_SESSION[self::$clientBrowserAgentIndex] : null;  
		$user = isset($_SESSION[self::$userIndex]) ? $_SESSION[self::$userIndex] : null; 

		if($ip === null || $browser === null || $user === null || $ip !== $clientIp || $browser !== $clientBrowserAgent){
			return null; 
		}
		return $user; 
	}

	public function isRememberUserSet(){
		if(isset($_SESSION[self::$rememberUserIndex])){
			unset($_SESSION[self::$rememberUserIndex]); 
			return true; 	
		} 
		return false;
	}

	public function removeSession(){
		if(isset($_SESSION[self::$userIndex]) && isset($_SESSION[self::$clientBrowserAgentIndex]) && isset($_SESSION[self::$clientIpIndex]) ){
			unset($_SESSION[self::$clientBrowserAgentIndex]); 
			unset($_SESSION[self::$clientIpIndex]); 
			return true; 
		}
		return false; 
	}
}