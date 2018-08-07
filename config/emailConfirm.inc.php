<?php
function emailConfirm($email_address, $link){
  $eol = "\n"; # for unix
# Boundry for marking the split & Multitype Headers
  $mime_boundary=md5(time());
  
  $headers .= 'From: eris@unc.edu '.$eol;
  $headers .= 'Reply-To: eris@unc.edu'.$eol;
  $headers .= "Message-ID: <".$now." TheSystem@".$_SERVER['SERVER_NAME'].">".$eol;
  $headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters
  $headers .= 'MIME-Version: 1.0'.$eol;
  $headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\"".$eol;

  $mail_subject = "Eris user email confirmation";
  

  $msg = "";
  $msg .= "--".$mime_boundary.$eol;
  $msg .= "Content-Type: text/html; charset=iso-8859-1".$eol;
  $msg .= "\nThanks for using Eris!<br/><br/>\n" .
        "\nPlease click on the following link to confirm your email address:<br/>" .
        "\n\n<a href=\"$link\">$link</a><br/><br/>\n\nThanks!\n\n<br/><br/>Eris Administrators".$eol.$eol;
#Text version
  $msg .= "--".$mime_boundary.$eol;
  $msg .= "Content-Type: text/plain; charset=iso-8859-1".$eol;
  $msg .= "\nThanks for using Eris!\n" .
        "\nPlease copy and paste the following link to web browser confirm your email address:" .
        "\n\n$link\n\nThanks!\n\nEris Administrators".$eol.$eol;
  mail($email_address, $mail_subject, $msg, $headers);
}  
?>
