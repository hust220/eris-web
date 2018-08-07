<?php
require('config/config.inc.php');
require('config/emailConfirm.inc.php');
?>
<?php
/*	if (empty( $_SESSION['username']) ){
		header("Location: login.php");
	}
*/
?>
<?php
#get sumitted data and check format
#--------save the form data-------------------------#
$varlist=array('username','password','confirm_pass','namefirst','namelast','email','organization');
foreach ($varlist as $var) {
	if (strlen($_POST[$var]) > 100 ) {die("invalid input");}
        $_SESSION["save_$var"] = $_POST[$var];
}
foreach ($varlist as $var) {
        unset( $_SESSION["error_$var"]);
}
unset($_SESSION['loginerror']); 
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_pass = $_POST['confirm_pass'];
$namefirst = $_POST['namefirst'];
$namelast = $_POST['namelast'];
$email = $_POST['email'];
$organization = $_POST['organization'];

$error = array();
if (strlen($username) <3 || strlen($username) > 10) {
	$error['username']=("username should contains 3-10 charactors");
}
if (preg_match('/[^0-9a-zA-Z]/',$username)) {
	$error['username']=("only number and letters are allowed in username");
}
if (strlen($password) <3 || strlen($password) > 15) {
	$error['password']=("password should contains 3-15 charactors");
}
if ($password != $confirm_pass) {
	$error['confirm_pass'] = "confirm password is not the same.";
}
if (empty($namefirst)) {
	$error['namefirst'] = "first name cannot be empty";
}
if (empty($namelast)) {
	$error['namelast'] = "last name cannot be empty";
}
if (empty($organization)) {
	$error['organization'] = "organization cannot be empty";
}
if (strlen($email)  >100 || !eregi('^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$',$email)) 
{ 	$error['email'] = ("Invalid email address\n");
}

if (count($error) > 0) {
        foreach ($varlist as $var) {
		$_SESSION['loginerror'] = 1;
                $_SESSION["error_$var"] = $error[$var];
        }
        header("Location: login.php");
	die;
}

$query = "SELECT * FROM $tableusers WHERE email='$email'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
if ($row > 0) {
        $error['email'] = ("email address already used by another id");
}
$query = "SELECT * FROM $tableusers WHERE username='$username'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
if ($row > 0) {
        $error['username'] = ("the username is already in use");
}
	
if (count($error) > 0) {
	foreach ($varlist as $var) {
		$_SESSION["error_$var"] = $error[$var];
	}
	$_SESSION['loginerror'] = 1;
	header("Location: login.php");
	die;
}





#-----------delete the saved forms--------------------#
foreach ($varlist as $var) {
	unset( $_SESSION["save_$var"] ); 
	unset( $_SESSION["error_$var"] ); 
}

$passmd5 = md5($password);
$query="INSERT INTO $tableusers (username,password,namefirst,namelast,email,organization,tlogin) ".
	"VALUES('$username','$passmd5','$namefirst','$namelast','$email','$organization',NOW()) ";
$result = mysql_query($query) or die("failed to insert into user database");
$id = mysql_insert_id();

$query="UPDATE $tableusers SET approved='1' WHERE id='$id' ";
$result = mysql_query($query) or die("failed to update user database");

# send email to user
$secret = "emailconfirmmagiccode";
$link = "http://" . $_SERVER['HTTP_HOST'] . '/eris/emailconfirm.php'."?username=$username&key=".
        sha1(md5($email) . md5($secret) );
 emailConfirm($email, $link);

$_SESSION['loginerror'] = 0;

# send emails to admin
$ip = $_SERVER['REMOTE_ADDR']; 
$mail_subject = "Eris new user registration notice";
$mail_message = "There is a new user registered to eris server\r\n username: $username \r\n" .
		"name: $namefirst $namelast \r\n" .
		"email: $email \r\n" .
		"organization: $organization \r\n" .
		"from ip: $ip\r\n";
$mail_headers = "From: eris server\r\nReply-To:\r\nX-Mailer: eris PHP script" . phpversion ();
#mail("syin@email.unc.edu", $mail_subject, $mail_message, $mail_headers);
mail("krohotin@email.unc.edu,fding@email.unc.edu,dokh@med.unc.edu,syin@email.unc.edu", $mail_subject, $mail_message, $mail_headers);


header("Location: login.php");
?>
<html>
<title> Protein stability prediction server </title>
<body>



</body>
</html>
