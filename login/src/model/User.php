<?php

namespace model; 

class User{

	private $userID; 
	private $userName;
	private $hash; 
	private $cookieValue; 
	private $valid;

	public function __construct($userID, $userName, $hash, $cookieValue){
		$this->userID = $userID; 
		$this->userName = $userName;
		$this->hash = $hash; 
		$this->cookieValue = $cookieValue; 
	}

	public function validate($password){
		//hash_equals($this->hash, crypt($password, $this->hash)); finns inte förrän php 5.6
		return $this->valid = crypt($password, $this->hash) === $this->hash; 
	}
	public function validateByCookieValue($value){
		return $this->valid ? true : $this->valid = $this->cookieValue === $value; 
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