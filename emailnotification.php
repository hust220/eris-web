<?php
require('config/config.inc.php');
require('config/emailNotification.inc.php');

#get sumitted data and check format
$userid=$_GET['userid'];
if (strlen($userid)>10 || eregi('^0-9',$userid) ) {die("Invalid userid");}
$jobid=$_GET['jobid'];
if (strlen($jobid)>10 || eregi('^0-9',$jobid) ) {die("Invalid jobid");}
#-----find the job status-----
$query = "SELECT status,emailFlag FROM $tablejobs WHERE id='$jobid' AND created_by='$userid' AND flag=0";
$result = mysql_query($query) or die("job query failed");
$row = mysql_fetch_array($result);
if ($row == 0) {
        die("No job found.");
}
$emailFlag = $row['emailFlag'];
if ($emailFlag == 0) { die("No need to send email notification");}
if ($emailFlag == 2) { die("Email notification already sent");}
#-----find the useraddress----
$query = "SELECT email,emailConfirmed FROM $tableusers WHERE id='$userid'";
$result = mysql_query($query) or die("user query failed.");
$row = mysql_fetch_array($result);
if ($row == 0) {
        die("No user found.");
}
$email = $row['email'];
$emailConfirmed = $row['emailConfirmed'];
if ($emailConfirmed == 0) {
	die("email not confirmed");
}
if($email){emailNotification($email,""); print("Email notification sent."); }


#-----now the email is send, update status--------
$query = "UPDATE $tablejobs SET emailFlag='2' WHERE id='$jobid'";
$result = mysql_query($query) or die("Failed to update database");

?>
