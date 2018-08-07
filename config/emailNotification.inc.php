<?php
function emailNotification($email_address,$link){
# $link = "http://eris.dokhlab.org/";
  $link = "http://redshift.med.unc.edu/eris/";
  $eol = "\n"; # for unix
# Boundry for marking the split & Multitype Headers
  $mime_boundary=md5(time());
  
  $headers .= 'From: Eris: protein stability predictor '.$eol;
  $headers .= 'Reply-To: eris@unc.edu'.$eol;
  $headers .= "Message-ID: <".$now." TheSystem@".$_SERVER['SERVER_NAME'].">".$eol;
  $headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters
  $headers .= 'MIME-Version: 1.0'.$eol;
  $headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\"".$eol;

  $mail_subject = "Eris job-status notification";
  

  $msg = "";
  $msg .= "--".$mime_boundary.$eol;
  $msg .= "Content-Type: text/html; charset=iso-8859-1".$eol;
  $msg .= "\nThanks for using Eris!<br/><br/>\n" .
  	"One of your recently jobs has finished.".
        "Please login Eris server to check the results:<br/>" .
        "\n\n<a href=\"$link\">$link</a><br/><br/>\n\nThanks!\n\n<br/><br/>Eris Administrators".$eol.$eol;
#Text version
  $msg .= "--".$mime_boundary.$eol;
  $msg .= "Content-Type: text/plain; charset=iso-8859-1".$eol;
  $msg .= "\nThanks for using Eris!\n One of your recent jobs has finished." .
        "Please login Eris server to check the results:" .
        "\n\n$link\n\nThanks!\n\nEris Administrators".$eol.$eol;
  mail($email_address, $mail_subject, $msg, $headers);
}  
?>
