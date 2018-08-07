<?php
require('config/config.inc.php');
?>
<?php
#$pdbid = "1ITF";
#$hash= "39cd71d7b0bedf8c8c7219c41d0d11e9";
#$pdbfilegz = "tmp/1itf.pdb.gz";
#$fp      = fopen($pdbfilegz, 'r') or die("open file failed");
#$content = fread($fp, filesize($pdbfilegz)) or die("read file failed");
#	$content = addslashes($content);
#
#fclose($fp);
#$query="INSERT INTO $tablepdbfiles (pdbid,hash,structure) VALUES ('1ITF','$hash','$content')";
#$result = mysql_query($query) or print("Failed to insert to pdbfile database");



include('config/email.inc.php');
$mail_message = "Test abcd \n" .
		"email: unknown \n" .
		"organization: now sure\n" .
		"http://eris.unc.edu/eris/confirm.php?txt=12&userid=12 \n" .
		"from ip: \n";
		
$mail_headers = "From: eris server <syin@email.unc.edu> \r\nReply-To: <syin@email.unc.edu> \r\nX-Mailer: eris PHP script" . phpversion ();
#mail("syin@email.unc.edu", $mail_subject, $mail_message, $mail_headers);
eris_mail('syin@email.unc.edu', "No more that's it!", $mail_message);
#
#
#



#require('config/emailConfirm.inc.php');
#emailConfirm("syin@email.unc.edu", "http://emailconfirm.unc.edu");


#$a = 0;
#$b = "test";
#$c = "no";
#if ("$a"==$b) {print("0=test\n");}
#if ("$a"==$c) {print("0=no\n");}
#if ($b==$c) {print("test=no\n");}

?>
