<?php
/*
Plugin Name: Asthma Diary
Plugin URI: http://multimusen.dk/
Description: If you suffer from asthma monitoring the symptoms on a regular basis is a good idea. This plugin will help you to monitor your peakflow and other important asthmatic symptoms in the Dashboard. The results will be stored in a database. You can add the results on any page, post or template by this shortcode [asthmadiary]
Version: 0.5
Author: Per Thykjaer Jensen
Author URI: http://multimusen.dk
License: &copy; Per Thykjaer Jensen - free to use by any asthma patient. For commercial use, please contact the author.
*/

/*
CONCERNING THE DATABASE
Checks whether the requred table exists or no.
If the table isn't present a new table is created.
Source of inspiration: http://jacobschatz.com/?p=23 - however the versioncheck is disabled by now.
The code is basically the same for other database calls. See form.inc.
*/

check_asthmablog_database(); // Is the table present?

function check_asthmablog_database()
{
    global $wpdb; // The WP database class
	// $rq_db_version = 1.0; 
	// I don't use this versioning method, but it's a good idea though.
	$table_name = "asthmablog";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
	{
		$sql = "CREATE TABLE " . $table_name . " (

		ab_id INT(11) NOT NULL AUTO_INCREMENT,
		ab_date DATE,
		ab_note TEXT,
		ab_name TEXT,
		ab_peakflow INT(4),
		ab_cough INT(1),
		ab_wheeze INT(1),
		ab_sleep INT(1),
		ab_activity INT(1),
		PRIMARY KEY  id (ab_id)
		);";
 
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
 
		$insert = "INSERT INTO " . $table_name .
		" (ab_note, ab_name, ab_peakflow, ab_cough, ab_wheeze, ab_sleep, ab_activity) " .
            "VALUES ('test', 'test', '0', '0', '0', '0', '0')";
 
		$results = $wpdb->query( $insert ); // test data inserted.
		// add_option("rq_db_version", $rq_db_version);
	}
	//for updates
 	//$installed_ver = get_option( "cl_db_version" );
}


/*
CONCERNING THE SHORTCODE FOR THE PRESENTATION LAYOUT
The shortcode [asthmadiary] for pages etc.
*/
function ad_present(){
	require_once('asthmadiary-result-presentation.php');
}

// add shortcode [asthmadiary] for use on pages or in templates
add_shortcode('asthmadiary', 'ad_present');


/*
CONCERNING THE DASHBOARD WIDGETS
The following outputs the contents of the dashboard widget.
*/
function dashboard_widget_function() {
	// Introduction
	echo "<div class='wrap'>";

	// Form
	require('form.inc');
	echo "<br /><br />";

	// call class
	require_once('charts.php'); // import the chart class
	$asthma_info = 'asthmadiary_charts'; // instantiate the class

	// Interpretation
	echo "<h3>Results</h3><p>A summary of the last 30 entries.</p>";
	$asthma_info::ad_pef_interpretation();

	// Results in charts
	//echo "<h3>Charts</h3><p>A summary of the last 30 entries.</p>";
	require_once('charts.php'); // import the chart class
	$asthma_info = 'asthmadiary_charts'; // instantiate the class
	$asthma_info::ad_peakflow_chart(); // drawing the peakflow charts
	//echo "<hr />";
	$asthma_info::ad_show_column('ab_cough', 'Cough');
	//echo "<hr />";
	$asthma_info::ad_show_column('ab_wheeze', 'Wheeze');
	//echo "<hr />";
	$asthma_info::ad_show_column('ab_sleep', 'Sleep');
	//echo "<hr />";
	$asthma_info::ad_show_column('ab_activity', 'Activity');

	echo "</div>";
}

// Function that being used in the action hook
function add_dashboard_widgets() {
	wp_add_dashboard_widget('dashboard_widget', 'Asthma Diary', 'dashboard_widget_function');
}

// Register the new dashboard widget into the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );
?>
