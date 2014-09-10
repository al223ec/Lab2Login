<?php

namespace model; 

class User{
	private $key; 
	private $userName; 
	private $password; 
	private $valid; 

	public function __construct($key, $userName, $password){
		$this->key = $key; 
		$this->userName = $userName;
		$this->password = $password;  
	}

	public function validate($password){
		return $this->valid = trim($password) === $this->password; 		
	}
	public function isValid(){
		return $this->valid; 
	}
	public function getUserName(){
		return $this->userName; 
	}
}