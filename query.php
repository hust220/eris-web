<?php

require('config/config.inc.php');

?>
<?php
	if (empty( $_SESSION['username']) ){
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
    <script src="style/js/query.js" language="javascript" type="text/javascript"></script>
    <script src="style/js/ajex.js" language="javascript" type="text/javascript"></script>
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
		
		<div id="main_content"><div class="indexTitle"> Check results. </div>
		<div class="indexStatus">
		Your tasks list:
		</div>
<?php
	$page=0;
	$nlimit = 20;
	$page = $_GET['page'];
	if(isset($page)){
		if (preg_match('/[^0-9]/',$page)) { 
			$page = 0;
		}
	}
	$offset = $page*$nlimit;
	
	$sortorder= $_GET['sortorder'];
	if (! isset($sortorder) || $sortorder != "ASC"){
		$sortorder = "DESC";
		$orderswitch = "ASC";
	} else {
		$sortorder = "ASC";
		$orderswitch = "DESC";
	}

	$sortcol = $_GET['sortcol'];
	if (! isset($sortcol)) {
		$sortcol = "id";
	}
	$sortstr = "id";
	$options = "";
	switch ($sortcol) {
	case "pdbid":
		$sortstr = "pdbid";
		break;
	case "mutation":
		$sortstr = "mutation";
		break;
	case "flex":
		$sortstr = "flex";
		break;
	case "relax":
		$sortstr = "relax";
		break;
	case "ddg":
		$sortstr = "ddg";
		break;
	case "status":
		$sortstr = "status";
		break;
	}		
	$options[$sortcol] = $orderswitch;
	
	$userid = $_SESSION['userid'];
	$query="SELECT id,pdbid,mutation,flex,relax,ddg,status,message,!ISNULL(pdb) FROM $tablejobs ".
	" WHERE created_by='$userid' AND flag = '0' ".
	" ORDER BY $sortstr $sortorder ";
	myshowresultssort($query,$page,$nlimit,"query.php?sortorder=$sortorder&sortcol=$sortcol&page",$options);	
?>
   <br>
   <br>
    &Delta;&Delta;G &lt; 0 : stabilizing mutations <br>
    &Delta;&Delta;G &gt; 0 : destabilizing mutations <br>

<?php 
/*
<form action="query_check.php" method="GET">
<table border=0 >
<tr>
<td>
Enter job id: </td><td>
<input name="jobid" type="text"  value="" /> 
</td>
</tr>

<tr>
<td>
<input type="submit" value="check result" />
</td>
</tr>
</table>

</form>
*/
?>

</div>
		<div id="rightbar">
			<div id="ataglance">
				your infomration at a glance.
			</div>
		</div>
	</div>
</body>
</html>
