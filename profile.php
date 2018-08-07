<?php
require('config/config.inc.php');
?>
<?php
	if (empty($_SESSION['username'])){
		header("Location: login.php");
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
    <script src="style/js/submit.js" language="javascript" type="text/javascript"></script>
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
		<div id="nav">
			<a href="index.php" class="nav"><div class='navi'> Home/Overview </div></a><br/>
			<a href="submit.php" class="nav"><div class='navi'> Submit a Task </div></a><br/>
			<a href="query.php" class="nav"><div class='navi'> Your Activities </div></a><br/>
			<a href="profile.php" class="nav"><div class='navi'> User Profile </div></a><br/>
			<a href="help.php" class="nav"><div class='navi'> Help </div></a><br/>
			<a href="contact.php" class="nav"><div class='navi'> Contact Us</div></a><br/>
		</div>
		
		<div id="main_content"><div class="indexTitle"> Update User Informatoin. </div>

		Please fill in the new user information and submit.
		<br/>
		<br/>

		<?php
		//print_r($_SESSION);
		//die;
			$varlist=array('namefirst','namelast','email','organization');
			if (!isset($_SESSION['loginerror'])) { #first visit to the page
				$userid = $_SESSION['userid'];
				$query = "SELECT username,namefirst,namelast,email,organization ".
				" FROM $tableusers WHERE id='$userid' LIMIT 1";
				$result = mysql_query($query) or die("cannot connet to database");
				$row = mysql_fetch_array($result);
				foreach ($varlist as $var) {
					$_SESSION["save_$var"] = $row[$var];
				}
			}
			
		?>
		<?php  if ($_SESSION['loginerror'] != '0') {  ?>
		
			<form action="profile_check.php" method="post" >
				<table id="frmRegister" border="0" cellpadding="0" cellspacing="0" width="80%">
					<tr>
						<td width='150px'>Username:</td>
						<td><input id="username" type="text" name="username" maxlength="10" disabled
						value="<?php  print($_SESSION['username']); ?>"/></td>
					</tr>
					<?php  if (isset($_SESSION["error_username"])) {echo("<tr><td></td>");myerror('username');echo("</tr>"); } ?>
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
					<tr><td></td></tr>
					<tr> <td></td>
						<td colspan="1" align="left"><input type="Submit"  value="Update" style="width:120px"/></td>
					</tr>
					</table>


			</form>
		<?php  unset($_SESSION['loginerror']);
		} ?>
			<?php  if ($_SESSION['loginerror'] == '0' ) { #successful update 
				unset ($_SESSION['loginerror']);
			?>	
					<p>
					<font color="green">	User information updated  successfully! </font>
					<?php  if ($_SESSION['emailconfirm'] == '1') { 
						unset($_SESSION['emailconfirm']);
					?>
						<br>
						<font color="red">Email address needs to be confirmed. </font>
					<?php  } ?>
			
			<?php   } ?>
			
			<br/>
			<br/>
			<div id="main_content"><div class="indexTitle" style="border:0px"> Change Password. </div>
			<form action="password_check.php" method="post" >
				<table id="frmRegister" border="0" cellpadding="0" cellspacing="0" width="80%">
					<tr>
						<td width='150px'>Old password:</td>
						<td><input id="oldpassword" type="password" name="oldpassword" maxlength="15" /></td>
					</tr>
					<?php  if (isset($_SESSION["error_oldpassword"])) {echo("<tr><td></td>");myerror('oldpassword');echo("</tr>"); } ?>
					<tr>
						<td>New Password:</td>
						<td><input id="newpassword" type="password" name="newpassword" maxlength="15" /></td>
					</tr>
					<?php  if (isset($_SESSION["error_newpassword"])) {echo("<tr><td></td>");myerror('newpassword');echo("</tr>"); } ?>
	
					<tr>
						<td>Confirm New Pass:</td>
						<td><input id="confirm_pass" type="password" name="confirm_pass" maxlength="15" /></td>
					</tr>
					<?php  if (isset($_SESSION["error_confirm_pass"])) {echo("<tr><td></td>");myerror('confirm_pass');echo("</tr>"); } ?>
					<tr><td></td></tr>
					<tr> <td></td>
						<td colspan="1" align="left"><input type="Submit"  value="Change Password" style="width:120px"/></td>
					</tr>
					</table>


			</form>

			<?php  if ($_SESSION['changepasserror'] == '0' ) { #successful login 
				unset ($_SESSION['changepasserror']);
			?>	
					<p>
					<font color="green">Password changed successfully! </font>

			
			<?php   } ?>
	
		
		</div>
		<div id="rightbar">
			<div id="ataglance">
				your infomration at a glance.
			</div>
		</div>
	</div>
</body>
</html>
