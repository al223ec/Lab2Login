<?php

session_start(); 

define('ROOT_DIR', realpath(dirname(__FILE__)));

require_once("../common/HTMLView.php");
require_once(ROOT_DIR . "/src/controller/LoginController.php");
require_once("config.php");

$view = new HTMLView();
$lc = new \controller\LoginController(); 
$HTMLBody = $lc->getLoginForm(); 

$view->echoHTML($HTMLBody);