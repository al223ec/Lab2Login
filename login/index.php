<?php


require_once("../common/HTMLView.php");
require_once("src/controller/LoginController.php");
require_once("config.php");

//$config = new Config(); 
//$config->DBLogin(); 

$view = new HTMLView();
$lc = new \controller\LoginController(); 
$HTMLBody = $lc->getLoginForm(); 

$view->echoHTML($HTMLBody);