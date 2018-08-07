<?php
function myerror($str){
	$errormessage = $_SESSION["error_$str"];
	echo "<td> <font color='red'> $errormessage </font> </td>";
	unset($_SESSION["error_$str"]);
}
function myform($str){
	$message = $_SESSION["save_$str"];
	echo "$message";
	unset($_SESSION["save_$str"]);
}
?>
