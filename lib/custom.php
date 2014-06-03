<?php

/**
 * homepage fucntion for calculating date of last update
*/
function isWeekday() {
	
	  $date = date("w");
	  $time = date('H');
	  
	  if ( $date != "0" && $date != "6" ) {
		  
		 $date = date("Y-m-d");// current date

		 if($time < 13)
		 { 
		 	$dateCurrent = strtotime(date("Y-m-d", strtotime($date)) . " -1 day");
			$thedate = date('Y-m-d', $dateCurrent);
			$thedate = mysql2date('j M Y', $thedate );
			return $thedate;
		 } 
		 else 
		 {
			 $dateCurrent = date("Y-m-d");// current date
		 	 $thedate = mysql2date('j M Y', $dateCurrent );
			 return $thedate;
		 } 
	  }
	  else if($date == "0") { 
	  	
		$date = date("Y-m-d");// current date
		
		$dateCu = strtotime(date("Y-m-d", strtotime($date)) . " -2 day");
		$thedate = date('Y-m-d', $dateCu);
		$thedate = mysql2date('j M Y', $thedate );
	  	return $thedate ;  
		
	  }
	  else if($date == "6") { 
	  	
		$date = date("Y-m-d");// current date
		
		$dateCu = strtotime(date("Y-m-d", strtotime($date)) . " -1 day");
		$thedate = date('Y-m-d', $dateCu);
		$thedate = mysql2date('j M Y', $thedate );
	  	return $thedate ;  
		
	  }
}

function lastDayUpdated(){

	global $wpdb;
			
	// Get last date
	$lastDate = $wpdb->get_row('SELECT datep_nouveaute FROM wp_nouveautes ORDER BY datep_nouveaute DESC LIMIT 0,1 ');	
	
	$date = ( !empty($lastDate) ? $lastDate->datep_nouveaute : '');
	
	return mysql2date('j M Y', $date );
	
}	