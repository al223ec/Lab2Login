<?php

namespace myExtensions; 

class MyDate {
	
	private static $days = array( 1 => "Måndag", 2 => "Tisdag", 3 => "Onsdag", 4 => "Torsdag", 5 => "Fredag", 6 => "Lördag", 7 => "Söndag");
	private static $months =  array(1 => "Januari", 2 => "Februari", 3 => "Mars", 4 => "April", 5 => "Maj", 6 => "Juni", 7 => "Juli", 
		8 => "Augusti", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "December");
	
	public static function getDayName(){
		return MyDate::$days[date('N')]; 
	}

	public static function getMonthName(){
		return MyDate::$months[date('n')];
	}
}