<?php
/*
CONCERNING THE SHORTCODE FOR THE PRESENTATION LAYOUT
The shortcode [MiniKanban] for pages etc.
*/

// add shortcode [minimalKanban] for use on pages or in templates

function register_shortcode(){
	add_shortcode('MiniKanban', 'MiniKanban');
}


function MiniKanban(){
	echo "Helloooo Woooooorlllldddd!";
	include('KbnBoardMinimal.php');
}

add_action('init','register_shortcode');

/**
* Import Dashboard Widgets
*/
include('kbnWidgets.php');
?>
