<?php

class asthmadiary_charts {

	/*
	This class should draw the images and graphs for the Asthma Diary,
	and print interpretation of the daily peakflow value.

	It will also be able to echo the post entries.
	*/

	public function ad_peakflow_chart(){
		// This function will echo a comma separated list from the database
		global $wpdb; // wp database class
		global $current_user; // wp user info class

		$username = $current_user->user_login; // the string of the logged in user

		// sql string
		$sql = $wpdb->get_results( "SELECT * FROM `asthmablog` WHERE ab_name='$username' ORDER BY `ab_date` DESC LIMIT 0,30" ); // selecting only inputs from the active user
		$sql = array_reverse($sql, true); // reversed in order to have last result to the right
		// form array of peakflow results (better: ab_peakflow could be an attribute)
	    	foreach($sql as $nummer){
			$resultat[] = $nummer->ab_peakflow;
		}
		//return $resultat;
		$comma_result = implode(",", $resultat);


		// Google Graph API strings
		$img_start = "<img src='http://chart.apis.google.com/chart?chf=a,s,000000B4&chxr=0,0,800&chxt=y&chs=300x200&cht=lc&chco=3D7930&chds=0,800&chd=t:";
		$img_end = "&chg=14.3,-1,1,1&chls=2,4,0&chma=5,5,5,5&chm=B,C5D4B5BB,0,0,0&chtt=Peakflow' width='300' height='200' alt='Peakflow' />";

		// Echo the graph to the browser
		echo "<div class='asthmadiary_pef'>";
		echo $img_start . $comma_result . $img_end; // here's the img tag
		echo "</div>";
	}

	/*
	DRAWING CHARTS
	Depending on the parameter this function will draw charts:
	cough, wheeze, sleep etc. 
	*/

	public function ad_show_column($column, $label){
		// This function will print a chart to the webpage
		// Intended for cough, wheeze, sleep, activity
		global $wpdb; // wp database class
		global $current_user; // wp user info class

		$username = $current_user->user_login; // the string of the logged in user

		// sql string, selecting only obervations from logged in user
		$sql = $wpdb->get_results( "SELECT * FROM `asthmablog` WHERE ab_name='$username' ORDER BY `ab_date` DESC LIMIT 0,30" ); // selecting only inputs from the active user
		$sql = array_reverse($sql, true); // reversed in order to have last result to the right
		
		// form array of peakflow results (better: ab_peakflow could be an attribute)
	    	foreach($sql as $nummer){
			$resultat[] = $nummer->$column;
		}
		//return $resultat;
		$comma_result = implode(",", $resultat);

		// Google Graph API strings
		$img_start = "<img src='http://chart.apis.google.com/chart?chxr=0,0,5&chxt=y&chbh=a&chs=300x225&cht=bvg&chco=A2C180&chds=0,5&chd=t:";
		$img_end = "&chtt=" 
		. $label
		. "&chts=676767,14" 
		. "' width='300' height='225' alt='"
		. $label
		. "' />";

		// Echo the graph to the browser
		echo "<div class='asthmadiary_pef'>";
		echo $img_start . $comma_result . $img_end; // here's the img tag
		echo "</div>";
	}

	/*
	INDICATIONS OF PEAKFLOW MEASUREMENTS
	This function will get the last peakflow result and compare to the highest value.
	Then the result is evaluated as a green, yellow or red value.
	*/
	public function ad_pef_interpretation(){
		global $wpdb; // wp database class
		global $current_user; // wp user info class

		$username = $current_user->user_login; // the string of the logged in user

		// sql string
		$sql = $wpdb->get_results( "SELECT * FROM `asthmablog` WHERE ab_name='$username' ORDER BY ab_date DESC " ); // selecting only inputs from the active user
		
		// form array of peakflow results (better: ab_peakflow could be an attribute)
	    	foreach($sql as $nummer){
			$resultat[] = $nummer->ab_peakflow;
		}
		$pefmax = max($resultat);
		$pefnow = $resultat[0];

		echo "<p><strong>Peakflow Indications</strong><br />";
		echo "Your Current peakflow: " . $pefnow . " It is a ";

		// Interpretation
		if ($pefnow >= ($pefmax * 0.8)) {
			echo "<strong>Green Peakflow Value</strong> - A peak flow reading in the green zone indicates that the asthma is under good control.";
		}
		if (($pefnow < ($pefmax * 0.79)) && ($pefnow >=($pefmax * 0.50))){
			echo "<strong>Yellow Peakflow Value</strong> - Indicates caution. It may mean respiratory airways are narrowing and additional medication may be required.";
		}
		if ($pefnow < ($pefmax * 0.50)){
			echo "<strong>Red Peakflow Value</strong> - Indicates a medical emergency. Severe airway narrowing may be occurring and immediate action needs to be taken. This would usually involve contacting a doctor or hospital.";
		}
		echo " Your highest peakflow value is: " . $pefmax . "<br />";
		echo "</p>";
	}


	/*
	ECHO ALL POSTS OF A USER
	This function will echo all the posts of the active user.
	*/
	public function ad_showall(){
		global $wpdb; // wp database class
		global $current_user; // wp user info class

		$username = $current_user->user_login; // the string of the logged in user

		// sql query
		$sql = $wpdb->get_results( "SELECT * FROM `asthmablog` WHERE ab_name='$username' ORDER BY ab_date DESC" ); // selecting only inputs from the active user

		// the table
		echo "<table style='margin-left: auto; margin-right: auto;'>
			<caption> The Complete Asthma Diary of " . $current_user->user_firstname . " " . $current_user->user_lastname . "</caption>
			<th> Id </th>
			<th> Date </th>
			<th> Note </th>
			<th> User </th>
			<th> Pef </th>
			<th> Cough </th>
			<th> Wheeze </th>
			<th> Sleep </th>
			<th> Activity </th>
			";

		$t = "<td>"; // shorthand html tags
		$te = "</td>";

	    	foreach($sql as $nummer){
			echo "<tr>"; // new row
			echo $t . $nummer->ab_id . $te; // echoing the content as table cells
			echo $t . $nummer->ab_date . $te;
			echo $t . $nummer->ab_note . $te;
			echo $t . $nummer->ab_name . $te;
			echo $t . $nummer->ab_peakflow . $te;
			echo $t . $nummer->ab_cough . $te;
			echo $t . $nummer->ab_wheeze . $te;
			echo $t . $nummer->ab_sleep . $te;
			echo $t . $nummer->ab_activity . $te;
			echo "</tr>"; // end row
		}

		echo "</table>";

	}	
} // class end
?>
