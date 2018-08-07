<?php
/********************************************************************
*********************************************************************/

require('config/config.inc.php');

?>
<?php
  $filename = 'logasdf';
  $content = $_SERVER['REMOTE_HOST']." ".$_SERVER['REMOTE_ADDR']." ".date(DATE_RFC822)." ". $_SERVER['HTTP_USER_AGENT']."\n";
  if (is_writable($filename)) {
    if ($handle = fopen($filename, 'a')) {
      fwrite($handle, $content);
      fclose($handle);
    }
  }
?>
<html>
<head>
<title> Protein stability prediction server </title>
	<style type="text/css">
      <!--
        @import url(style/ifold.css);
        @import url(style/greybox.css);
      -->
    </style>
    <script src="style/js/login.js" language="javascript" type="text/javascript"></script>
</head>

<body>
<div id="main">
	<div id="header">
		<div style="float:left"><img src="style/img/head.jpg" alt="Eris" border="0" /></div>
		<div style="float:left"><img src="style/img/pstability.jpg" alt="protein stability prediction" border="0" /></div>
		<div style="float:right"><a href="http://dokhlab.unc.edu/main.html"><img src="style/img/dokhlab.jpg" alt="Dokholyan Lab" border="0" /></a></div>
	</div>
	<div id="meta" class="smallfont">
	<?php
		include('meta.php');
	?>
	</div>
	<div id="content">
		<div id="login_content">
		<p>
			Welcome to <b>Eris</b> server. Please use your username and password to login or sign up
			a new account.
		 <p>
		<b>Eris</b>, which takes the name of Greek goddess of discord, is a protein 
			stability  prediction server.  
		
		 <b>Eris</b> server calculates the change of the protein stability induced by mutations (&Delta;&Delta;G)
		 utilizing the recently developed Medusa modeling suite.
		 In our test study, the &Delta;&Delta;G values of a large dataset (&gt;500) were calculated and compared
		 with the experimental
			data and significant correlations are found.
			The correlation coefficients vary from 0.5 to 0.8. <b>Eris</b> also allows refinement of the protein
			structure when high-resolution
			structures are not available. Compared with many other stability prediction servers, our method is not specifically 
			trained using protein stability data and should be valid for a wider range of proteins. Furthermore, <b>Eris</b> models
			backbone flexibility, which turns out to be crucial for &Delta;&Delta;G estimation of small-to-large mutations. More
			details are available in our publications: 

			<p>
				<a href='http://dokhlab.unc.edu/publications.html#ydd_nm07'>
				S. Yin, F. Ding, and N. V. Dokholyan, "Eris: an automated estimator of protein stability"  Nature Methods 4, 466-467 (2007)
				</a> <br>
				<a href='http://dokhlab.unc.edu/publications.html#ydd_struct07'>
				S. Yin, F. Ding, and N. V. Dokholyan, "Modeling backbone flexibility improves protein stability estimation" Structure, 15: 1567-1576 (2007) 
				</a><br>
				<a href='http://dokhlab.unc.edu/papers/dd_plos-cb06.pdf'>
				F. Ding and N. V. Dokholyan, "Emergence of protein fold families through rational design" Public Library of Science Computational Biology, 2: e85 (2006)
				</a><br>
			<br>

			<p>
			The standalone Eris software is available via <a href='http://www.moleculesinaction.com'>Molecules in Action, LLC</a>.

			<p>
			Please read the following <a href="terms.html"> terms and conditions </a> of using Eris server. Please contact eris[put @ here]unc.edu</a> for technical supports and comments.
	
			
		</div>
		<div id="menu">
			<?php 
			if (!empty($_SESSION['error_login'])){
			
				echo '<div style="display: block;" class="errors_box" id="errors_login">'.
			$_SESSION['error_login'].
			'</div>	';
			}

			?>
			<form id="frmLogin" method="post" action="login_check.php">
			<table border="0" cellpadding="0" cellspacing="0" width="75%" style="float:left">
			<tr><td>Username:</td><td><input id="user" type="text" name="user" /></td></tr>
			<tr><td>Password:</td><td><input id="pass" type="password" name="pass" /></td></tr>
			</table>
			<div style="float:left;margin:2px 0 0 2px">
				<input type="Submit" value="Login"  style="width:40px;height:36px;font-weight:bold;background:#f3ebc1;border:0px" />
			</div>
			</form>
<?php 
#			<font color="0x5500AA">
#
#			For testers, please use username "test" and password "test123". <br>
#			</font>
?>
			<a href="javascript:register()"><div style="float:left;border:1px outset #6c6015;cursor:pointer;font:bold"><font color="#5c5010 ">Sign up</font></div></a>
<!--			<a href="lostPassword.php"><div style="float:right;border:1px outset;cursor:pointer;">Lost Password?</div></a> 
-->
			<br/>
			<br/>
			<form action="register.php" method="post" >
					<table id="frmRegister" border="0" cellpadding="0" cellspacing="0" width="100%"  
					<?php  if (!isset($_SESSION['loginerror'])){
						echo ( ' style="display:none"');
					}elseif($_SESSION['loginerror']=='0') {
						echo ( ' style="display:none"');
					}else{
						unset($_SESSION['loginerror']);
					}?>  >
						<tr>
							<td>Username:</td>
							<td><input id="username" type="text" name="username" maxlength="10" value="<?php myform("username")?>"/></td>
						</tr>
						<?php  if (isset($_SESSION["error_username"])) {echo("<tr><td></td>");myerror('username');echo("</tr>"); } ?>
						<tr>
							<td>Password:</td>
							<td><input id="password" type="password" name="password" maxlength="15" /></td>
						</tr>
						<?php  if (isset($_SESSION["error_password"])) {echo("<tr><td></td>");myerror('password');echo("</tr>"); } ?>
						<tr>
							<td>Confirm Pass:</td>
							<td><input id="confirm_pass" type="password" name="confirm_pass" maxlength="15" /></td>
						</tr>
						<?php  if (isset($_SESSION["error_confirm_pass"])) {echo("<tr><td></td>");myerror('confirm_pass');echo("</tr>"); } ?>
						<tr>
							<td>First Name:</td>
							<td><input id="namefirst" type="text" name="namefirst"  maxlength="15" value="<?php myform("namefirst")?>" /></td>
						</tr>
						<?php  if (isset($_SESSION["error_namefirst"])) {echo("<tr><td></td>");myerror('namefirst');echo("</tr>"); } ?>
						<tr>
							<td>Last Name:</td>
							<td><input id="namelast" type="text" name="namelast"  maxlength="15" value="<?php myform("namelast")?>" /></td>
						</tr>
						<?php  if (isset($_SESSION["error_namelast"])) {echo("<tr><td></td>");myerror('namelast');echo("</tr>"); } ?>
						<tr>
							<td>Email:</td>
							<td><input id="email" type="text" name="email" maxlength="100"  value="<?php myform("email")?>" /></td>
						</tr>
						<?php  if (isset($_SESSION["error_email"])) {echo("<tr><td></td>");myerror('email');echo("</tr>"); } ?>
						<tr>
							<td>Organization:</td>
							<td><input id="organization" type="text" name="organization" maxlength="20"  value="<?php myform("organization")?>" /></td>
						</tr>
						<?php  if (isset($_SESSION["error_organization"])) {echo("<tr><td></td>");myerror('organization');echo("</tr>"); } ?>
						<tr>
							<td colspan="2" align="center"><input type="Submit" onClick="return validateRegister();" value="Register an Account" style="width:150px"/></td>
						</tr>

						<tr>
						<br>Notes: Please fill in your name and affiliation accurately. Improperly registered account
						will be denied. </br>
						</tr>

					</table>


			</form>
			<?php  if ($_SESSION['loginerror'] == '0' ) { #successful register 
				unset ($_SESSION['loginerror']);
			?>	
					<p>
					<font color="green">	Registered successfully! <br><br>Email address needs to be confirmed. 
					Please check your email for the confirmation letter and following the instructions.
					If you have done so, please use the username and password to login. </font>

			
			<?php   } ?>
		</div>
	</div>
</body>
</html>
