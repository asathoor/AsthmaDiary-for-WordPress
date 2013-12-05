<?php
function lavRadio($astma, $n){ // creates radio buttons
	$t = 1;

	echo $astma."<br />1";

	while ($n > 0) {
		print "<input type='radio' name='$astma' value='$t' />\n";;
		$t = $t +1;
		$n = $n - 1;
	}
	
	echo " 5<br />";

}
// use it like this: lavRadio('Hoste', 5);

// testing form logic
if ($_POST['submit']) 
{
	// logic to be performed after clicking submit
   new_asthma_observation(); 
}
else {
	echo "<h3>New Asthma Diary Entry</h3><p>Please enter a note, your observations and peakflow in the form.</p>";
	}

// this function will add observations from the form to the database
function new_asthma_observation(){

	// database operations
	global $wpdb; // The WP database controller class
	$table_name = "asthmablog"; // name of table
   $valuestring = "'". $_POST['Note'] . "','" . $_POST['Peakflow'] . "','" . $_POST['Cough'] . "','" . $_POST['Wheeze'] . "','" . $_POST['Sleep'] . "','" . $_POST['Activity'] . "','" . $_POST['Name'] . "','" . $_POST['Date'] . "'";

	$insert = "INSERT INTO " . $table_name .
	" (ab_note, ab_peakflow, ab_cough, ab_wheeze, ab_sleep, ab_activity, ab_name, ab_date) " .
	"VALUES (" . $valuestring .")"; 

    $results = $wpdb->query($wpdb->prepare( $insert )); // don't forget to execute the query....
    // prepare is the sanitizing function in order to avoid SQL inject etc.

	echo "The Asthma Diary is updated.";
}

function asthmablog_username() {
    // prints username
    
    // user informations prepared
	global $current_user;
    get_currentuserinfo();
    echo $current_user->user_login;

/*
    Samples from Codex:
      echo 'Username: ' . $current_user->user_login . "\n";
      echo 'User email: ' . $current_user->user_email . "\n";
      echo 'User first name: ' . $current_user->user_firstname . "\n";
      echo 'User last name: ' . $current_user->user_lastname . "\n";
      echo 'User display name: ' . $current_user->display_name . "\n";
      echo 'User ID: ' . $current_user->ID . "\n";	
*/	
    
    }

?>

<div id='asthma-help'>
	<a href='http://multimusen.dk/asthmadiaryhelp/' target='_blank'> Help </a>
</div>


<!-- FORM Input to asthma diary -->
<form id="asthmablog" action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="message" value="OK - the Asthma Diary is updated." />
	<input type="hidden" name="Date" value="<?php echo date('Y-m-d H:i:s'); ?>" /> <br />
	<input type="hidden" name="Name" value="<?php asthmablog_username(); ?>" /> <br />

	<br />
	<!-- buttons -->
	<?php lavRadio('Cough', 5); ?>
	<?php lavRadio('Wheeze', 5); ?>
	<?php lavRadio('Sleep', 5); ?>
	<?php lavRadio('Activity', 5); ?>
	Peakflow: <input type="text" name="Peakflow" /> <br />
	<textarea name="Note" rows="5" cols="20">Note</textarea> <br />
	<br /><br />
	<input type="submit" name="submit" value="OK" /> <input type="reset" name="Cancel" value="Cancel">
</form>
