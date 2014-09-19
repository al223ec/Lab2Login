<?php 

class HTMLView {
	private $header = ""; 
	private $pageTitel = "PHP page"; 
	private $metaArray = array(
				"<meta http-equiv='content-type' content='text/html; charset=utf-8' />"
			);
	private $cssArray = array(
				"<link rel='stylesheet' type='text/css' href='css/reset.css' media='all'>",
		    	"<link href='css/bootstrap.min.css' rel='stylesheet'>",
    			"<link rel='stylesheet' type='text/css' href='css/style.css' media='all'>"
    		); 

	public function setTitel($pageTitel){
		$this->pageTitel = $pageTitel; 
	}

	
	public function addMetaTag($metaTag){
		if($metaTag == NULL){
			throw new Exception("HTMLView::addMetaTag does not allow an meta tag to be null");
		}
		$this->metaArray[] = $metaTag; //Otestad
	}
	public function addCss($css){
		if($css == NULL){
			throw new Exception("HTMLView::addCss does not allow an css to be null");
		}
		$this->cssArray[] = $css;
	}

	public function echoHTML($body) {
		if($body == NULL){
			throw new Exception("HTMLView::echoHTML does not allow body to be null");
		}
		
		echo "
		<!DOCTYPE html>
		<html>
		<head>" 
			. $this->getTags($this->metaArray) 
			. $this->getTags($this->cssArray) . "

		<title> $this->pageTitel </title>
		</head>
		<body>
			<div class='wrapper'>
				$body
			</div>
		</body>
		</html>"; 
	}

	private function getTags(array $array){
		$ret = ""; 
		foreach ($array as $key => $value) {
			$ret .= $value; 
		}
		return $ret;  
	}

}