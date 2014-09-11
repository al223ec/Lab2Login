<?php

namespace model; 

class User{

	private $userID; 
	private $userName; 
	private $valid; 
	private $hash;

	public function __construct($userID, $userName, $hash){
		$this->userID = $userID; 
		$this->userName = $userName;
		$this->hash = $hash; 
	}

	public function validate($password){
		return $this->valid = crypt($password, $this->hash) === $this->hash; 
	}
	public function isValid(){
		return $this->valid; 
	}
	public function getUserName(){
		return $this->userName; 
	}
}