<?php

define('ROOT_DIR', realpath(dirname(__FILE__)));

require_once("../common/HTMLView.php");
require_once(ROOT_DIR . "/src/controller/LoginController.php");

session_start(); 
//var_dump($_SERVER["HTTP_USER_AGENT"]);
//var_dump($_SERVER["REMOTE_ADDR"]);
//var_dump($_SERVER['HTTP_X_FORWARDED_FOR']); 
//die();

$view = new HTMLView();
$lc = new \controller\LoginController(); 

$HTMLBody = $lc->performAction();
$view->echoHTML($HTMLBody);

//Återstår att genomföra alla tester på webbhotellet.
//Samt gör någon bra DBConfig