<?php 
require_once('charts.php'); // import the chart class
$asthma_info = 'asthmadiary_charts'; // instantiate the class

// set var and global
global $current_user;
get_currentuserinfo();	

// test whether user is logged in
if ( is_user_logged_in() ){
	echo "<p>The Asthma Diary of : ";
	echo $current_user->user_firstname . ' ' . $current_user->user_lastname . ".<br />";
	echo "Date: " . date('d-m-Y H:i') . "</p><hr />";


	/*
	PRESENTATION OF OBSERVATIONS
	*/
	// echo "<h3>Summaries</h3><p>The graphs below consists of the 30 newest posts (or less).</p>";
	// includes
	$asthma_info::ad_peakflow_chart(); // drawing the peakflow charts

	$asthma_info::ad_pef_interpretation(); // peakflow interpretation
	echo "<p style='font-size: x-small;'><strong>Caution:</strong> the interpretation of the 
	peakflow values should be read with care.
	The green value is your best peakflow value in the database. Therefore the first entries
	will always be green. After a while however the peakflow interpretations will become
	more precise. </p><hr />";


	$asthma_info::ad_show_column('ab_cough', 'Cough');
	// echo "<hr />";
	$asthma_info::ad_show_column('ab_wheeze', 'Wheeze');
	// echo "<hr />";
	$asthma_info::ad_show_column('ab_sleep', 'Sleep');
	// echo "<hr />";
	$asthma_info::ad_show_column('ab_activity', 'Activity');
	echo "<div style='font-size: x-small;'><br /><br />";
	// echo "<hr />";
	$asthma_info::ad_showall();
	echo "'The Asthma Diary' - &copy; Per Thykjaer Jensen, 2011. All rights reserved.";
	echo "</div>";
	}
	else {
	echo "<p>This page is only for registered users. <br />Users can log in <a href='http://multimusen.dk/wp-login.php'>here</a>. </p>";
}





	
?>

