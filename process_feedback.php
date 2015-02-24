
<?php
	$concert =  $_GET["concert"];
	echo "<form action='feedback.php' method='get'>";
	echo "Rating : <select name='rating'>
			<option value='1'>1</option>
			<option value='2'>2</option>
			<option value='3'>3</option>
			<option value='4'>4</option>
			<option value='5'>5</option>
		</select><br/><br/>";
	echo "<textarea name='comments' rows='5' cols='50'></textarea><br/><br/>";
	echo "<input type='hidden' name='concert' value=$concert>";
	echo "<button class='btn btn-primary btn-lg' role='button'>Submit</button></form>";
	
?>