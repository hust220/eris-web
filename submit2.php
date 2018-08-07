<?php
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
    <style>
	iframe {
        border-width: 0px;
	height: 200px;
        width: 760px;
	}
	iframe.hidden {
        visibility: hidden;
        width:0px;
        height:0px;
	}

    </style>
    <script src="style/js/ajex.js" language="javascript" type="text/javascript"></script>
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
		
		<div id="main_content">

<div class="indexTitle" id="step"> Submit a task - Step 1. </div>

<iframe id="iframe" name="iframe" src="submit2iframe.php" scrolling="no" frameborder=0 onload='oniframeload();'>
<!--
Please provide pdb code or upload a pdb file and submit the request. <br> <br>
<form enctype="multipart/form-data" action="submit3.php" method="POST">
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
<td> </td>
<td>
<input type="submit" value="Next &gt &gt" >
</td>
</tr>
</table>
</form>
-->
</iframe>
<br>

<div id="instruction">
If you want submit multiple tasks or experience trouble submitting your task, please click 
<a href="submit1.php"> here </a>.
</div>

<div id="selectordiv">
<table id="selector">
</table>
</div>

<form id="mutform" style="display:none">
<table border=0 >
<tr>
<td> mutation: &nbsp </td>
<td > <input type="text" id="mut" value=""><td>
</tr>
</table>
</form>

<div id="next" style="display:none">
<table><tbody>
<tr> 
<td> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp  &nbsp  &nbsp  &nbsp  &nbsp  &nbsp  &nbsp  &nbsp  </td> 
<td> <input type="button" value="Next &gt &gt" id="nextb" onclick='nextstep();'></td>
</tr>
</tbody></table>
</div>

<div style="position:absolute;z-index:6000;display:none;background-color:#FFF;border:solid 1px #f3ebc1" id="seqpopup">
<div style="background-color:#f0f0f0;border:1px #5c5010">Select mutation:</div>
<table>
<tr>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(0)'> A </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(1)'> C </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(2)'> D </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(3)'> E </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(4)'> F </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(5)'> G </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(6)'> H </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(7)'> I </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(8)'> K </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(9)'> L </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(10)'> M </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(11)'> N </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(12)'> P </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(13)'> Q </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(14)'> R </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(15)'> S </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(16)'> T </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(17)'> V </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(18)'> W </div> </td>
<td> <div class='seqpopup' style='cursor:pointer' onclick='selectM(19)'> Y </div> </td>
</tr>
</table>
</div>


<form style="display:none">
<input id="hash" type="hidden" value="aa">
<input id="seq" type="hidden" value = "bb">
<input id="error" type="hidden" value = "1">
</form>	

		</div>

		<div id="rightbar">
			<div id="ataglance">
				your infomration at a glance.
			</div>
		</div>

	</div>
</div>
<script language="javascript">
var errorfield = document.getElementById("error");
errorfield.value = "1";
</script>
</body>
</html>
