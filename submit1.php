<?php
# the origianl submit page
# accept 5 jobs at a time
# submissions are checked by submit_check.php
require('config/config.inc.php');
?>
<?php
	if (empty($_SESSION['username'])){
		header("Location: login.php");
	}
	
$varlist = array("pdbid","mut0",'mut1','mut2','mut3','mut4','method','prerelax','uploadedfile','numberofrows');
$formdefault= array();
$formdefault["pdbid"] = '';
$formdefault["mut0"] = '';
$formdefault["mut1"] = '';
$formdefault["mut2"] = '';
$formdefault["mut3"] = '';
$formdefault["mut4"] = '';
$formdefault["method"] = '0';
$formdefault["prerelax"] = '1';
$formdefault["numberofrows"] = '1';
$formdefault["uploadedfile"] = '';

foreach ($varlist as $var) {
	if (!isset($_SESSION["save_$var"])){
		$_SESSION["save_$var"] = $formdefault[$var];
	}
}

//print_r($_SESSION);die;
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
		
		<div id="main_content"><div class="indexTitle"> Submit a task. </div>


Please provide a PDB code or upload a PDB file and submit the request. <br> <br>
<form enctype="multipart/form-data" action="submit_check.php" method="POST">
<table border=0 >
<tr>
<td>
Enter pdb code:<small><br> (e.g. 2ci2)</small> </td><td>
<input id="pdbid" name="pdbid" type="text" value="<?php myform("pdbid")?>" /> 
</td>
<?php  myerror("pdbid");?>
</tr>
<tr>
<td>
or upload a pdb file: </td><td>
<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
<input name="uploadedfile" type="file" value="<?php myform("uploadedfile")?>"/>
</td>
<?php  myerror("uploadedfile");?>
</tr>
<tr>
<td >
Mutation:<br> <small>(e.g. E5A L22V)</small> </td><td>
<input id="numberofrows" type="hidden" name="numberofrows" 
value="<?php myform("numberofrows")?>" />
<input id="mut0" name="mut0" type="text" value="<?php myform("mut0")?>" > <i></i>
</td>
<?php  myerror("mut0");?>
</tr>
<tr id="mut1" style="display:"> <td> </td><td> <input  name="mut1" type="text" value="<?php myform("mut1")?>" > </td> 
<?php  myerror("mut1");?>
</tr>
<tr id="mut2" style="display:"> <td> </td><td> <input  name="mut2" type="text" value="<?php myform("mut2")?>" > </td>
<?php  myerror("mut2");?>
</tr>
<tr id="mut3" style="display:"> <td> </td><td> <input  name="mut3" type="text" value="<?php myform("mut3")?>" > </td>
<?php  myerror("mut3");?>
</tr>
<tr id="mut4" style="display:"> <td> </td><td> <input  name="mut4" type="text" value="<?php myform("mut4")?>" > </td>
<?php  myerror("mut4");?>
</tr>

<tr id='moremut'>
<td> </td>
<td>
<div > (upto 5 jobs) </div>
</td>
</tr>

<tr>
<td>
Prediction method: </td><td>
<select id="method" name='method' value="<?php myform("method")?>">
<option value='0'> Fixed Backbone </option>
<option value='1'> Flexible Backbone </option>

</select>
</td>
</tr>


<tr>
<td>
Backbone Pre-relaxation: </td><td>
<input name="prerelax" type="checkbox" value="<?php myform("prerelax")?>" style="width:30px" /> 
</td>
</tr>

<tr>
<td> </td>
<td>
<input type="submit" value="Submit" />
</td>
</tr>
</table>
</form>


		
		</div>
		<div id="rightbar">
			<div id="ataglance">
				your infomration at a glance.
			</div>
		</div>
	</div>
</body>
</html>
