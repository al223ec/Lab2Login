<?php

namespace model; 

class User{
	private $key; 
	private $userName; 
	private $password; 

	public function __construct($key, $userName, $password){
		$this->key = $key; 
		$this->userName = $userName;
		$this->password = $password;  
	}

}