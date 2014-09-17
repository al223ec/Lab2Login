<?php

namespace model; 

class User{

	private $userID; 
	private $userName;
	private $hash; 
	private $cookieValue; 
	private $cookieExpiration; 
	private $valid = false;


	public function __construct($userID, $userName, $hash, $cookieValue, $cookieExpiration){
		$this->userID = $userID; 
		$this->userName = $userName;
		$this->hash = $hash; 
		$this->cookieValue = $cookieValue; 
		$this->cookieExpiration = $cookieExpiration; 
	}

	public function validate($password){
		//hash_equals($this->hash, crypt($password, $this->hash)); finns inte förrän php 5.6
		return $this->valid = crypt($password, $this->hash) === $this->hash; 
	}
	public function validateByCookieValue($value, $cookieExpiration){
		return $this->valid = $this->cookieValue === $value && $this->cookieExpiration === $cookieExpiration && $this->cookieExpiration > time(); 
	}

	public function isValid(){
		return $this->valid; 
	}

	public function getUserName(){
		return $this->userName; 
	}
	
	public function getUserID(){
		return $this->userID; 
	}
}