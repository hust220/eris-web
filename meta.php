<?php
#require('config/config.inc.php');
?>

<?php
if(!empty($_SESSION['username'])) { #already logged in
	$username = $_SESSION['username'];
	echo "Logged in as <strong> $username </strong>";
	echo '<a href="logout.php"> Logout </a>';
}else{
	echo "not logged in";
}
?>
