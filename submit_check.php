<?php
require('config/config.inc.php');
?>
<?php
	if (empty($_SESSION['username'])){
		header("Location: login.php");
	}
?>
<?php
function mydie($str,$var){
	$_SESSION["error_$var"] = $str;
	header("Location: submit1.php");
	die;
}

#--------save the form data-------------------------#
$varlist = array("pdbid","mut0",'mut1','mut2','mut3','mut4','method','prerelax','uploadedfile','numberofrows');
foreach ($varlist as $var) {
	$_SESSION["save_$var"] = $_POST[$var];
}
foreach ($varlist as $var) {
	unset( $_SESSION["error_$var"]);
}

$error = array();
#-------get sumitted data and check format--------#
$pdbid=$_POST['pdbid'];
$pdbid = trim(strtoupper($pdbid));
if (strlen($pdbid) != 4 || eregi('[^0-9a-zA-Z]',$pdbid)) {
	if ($_FILES['uploadedfile']['size'] == 0 ) {
		$error['pdbid']=("Invalid pdbid\n");
	}
}
#----- mutations--------#
$count = 0;
for ($i=0;$i<$MMut;$i++){
	$mutai = strtoupper($_POST["mut$i"]);
	if ($mutai) {
		if (strlen($mutai>100) || !preg_match('/^(\s*[ACDEFGHIKLMNPQRSTVWY][0-9]+[ACDEFGHIKLMNPQRSTVWY]\s*)+$/',$mutai)) 
		{ 
			$error["mut$i"] = ("Invalid mutation\n");
		}else{
			$mutation[$i] = $mutai;
		}
		$count++;
	}
}
$nmut = $count;
if ($nmut ==0 ) {$error["mut0"] = 'No mutation specified.';}

$method=$_POST['method'];
$prerelax=$_POST['prerelax'];
$ip=$_SERVER['REMOTE_ADDR'];
#-----------return if error---------------------------------#
if (count($error) > 0) {
	foreach ($varlist as $var) {
		$_SESSION["error_$var"] = $error[$var];
	}
	header("Location: submit1.php");
	die;
}
#-----------check if the pdb file exists in database------------#
if ( $pdbid) {
	$query = "SELECT id FROM $tablepdbfiles WHERE pdbid='$pdbid'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	if ($row) { # pdb already exists
		$pdbfileid = $row['id'];
	}else { #need download the file
		unset($output);
		exec("/usr/bin/wget -P pdb http://pdbbeta.rcsb.org/pdb/files/$pdbid.pdb",$output);
		if (! is_file("pdb/$pdbid.pdb") ) {
			mydie("Failed to download pdb of $pdbid! Please check spelling or try later","pdbid");
		}else{ #update the database
			$hash = "";
			exec("md5sum pdb/$pdbid.pdb|cut -d' ' -f1",$output);
			if (sizeof($output) == 0) {
				mydie("Fail to checksum pdbfile $pdbid.",'pdbid');
			}
			$hash = $output[0];
			unset($output);
			exec("gzip -f pdb/$pdbid.pdb", $output);
			if (! is_file( "pdb/$pdbid.pdb.gz")) {
				mydie("Fail to compress pdbfile $pdbid.",'pdbid');
			}
			$pdbfilegz = "pdb/$pdbid.pdb.gz";
			$fp      = fopen($pdbfilegz, 'r');
			$content = fread($fp, filesize($pdbfilegz));
			$content = addslashes($content);
			fclose($fp);
			$query="INSERT INTO $tablepdbfiles (pdbid,hash,structure)".
				" VALUES ('$pdbid','$hash','$content')";
			$result = mysql_query($query) or mydie("Failed to insert to pdbfile database.",'pdbid');
			## now delete the file
			unlink($pdbfilegz);
		}
		$pdbfileid = mysql_insert_id();
	}
} else { # for uploaded files
	if ( $_FILES['uploadedfile']['size'] == 0) { die("No pdb file uploading failed") ;}
	$pdbfile = $_FILES['uploadedfile']['tmp_name'];
	$fileSize = $_FILES['uploadedfile']['size'];
	unset($output);
	exec("$exec_check_mut '$pdbfile'", $output);
	$nres = $output[0];
	if( $nres == 0 ) { mydie("Invalid pdb file.",'uploadedfile');}
	unset($output);
	exec("md5sum $pdbfile|cut -d' ' -f1",$output);
	if (sizeof($output) == 0) {
		mydie("Fail to checksum pdb file.",'uploadedfile');
	}
	$hash = $output[0];
	$query = "SELECT id FROM $tablepdbfiles WHERE hash='$hash'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	if ($row) { # pdb already exists
		$pdbfileid = $row['id'];
	} else { # insert pdbfile to database
		unset($output);
		exec("gzip -f $pdbfile", $output);
		if (! is_file( "$pdbfile.gz")) {
			mydie("Fail to compress pdbfile $pdbid.",'uploadedfile');
		}
		$pdbfilegz = "$pdbfile.gz";
		$fp      = fopen($pdbfilegz, 'r');
		$content = fread($fp, filesize($pdbfilegz));
		$content = addslashes($content);
		fclose($fp);
		$query="INSERT INTO $tablepdbfiles (pdbid,hash,structure)".
			"VALUES ('uploaded_pdb','$hash','$content')";
		$result = mysql_query($query) or mydie("Failed to insert to pdbfile database.",'uploadedfile');
		$pdbfileid = mysql_insert_id();
		## now delete the file
		unlink($pdbfilegz);
	}
}
### now we have the pdbfileid for the submission ###
#------------checkout the pdb file-------------------#
$query = "SELECT structure FROM $tablepdbfiles WHERE id='$pdbfileid'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
# comment: we may want to save some cpu time later by using the newly uploaded or downloaded file #
if (! $row) {die("Failed to retrieve the pdb file from database."); }
$content = $row['structure'];
$tmppdbfile = "pdb/tmp.pdb";
$fp = fopen("$tmppdbfile.gz", 'w') or die("Cannot open temp pdb file for writing.");
fwrite($fp, $content);
fclose($fp);
unset($output);
exec("gunzip -f $tmppdbfile.gz", $output);
if (! is_file( "$tmppdbfile")) {
	die("Fail to decompress pdbfile $tmppdbfile id $pdbfileid.");
}
#-------------check the mutation --------------------#
$error = array();
for ($i=0;$i<$MMut;$i++) {
	$muti = $mutation[$i];
	if ($muti) {
		unset($output);
		exec("$exec_check_mut $tmppdbfile '$muti' ", $output);
		$errlength = sizeof($output);
		if ($errlength > 0) {
			for ($j=0;$j<$errlength;$j++){
				$error["mut$i"].= $output[$j]."<br>";
			}
		}
	}
}
unlink($tmppdbfile);
if (count($error) > 0) {
        foreach ($varlist as $var) {
                $_SESSION["error_$var"] = $error[$var];
        }
        header("Location: submit1.php");
        die;
}

#-----------------update entry in database-------------------------#
if ($pdbid) {
	$pdbstr = $pdbid;
}
else {
	$pdbstr = "uploaded_pdb";
}

$userid = $_SESSION['userid'];
$i = 0;
foreach ($mutation as $muti){
	$i++;
	$muti = preg_replace('/\s+/',' ',$muti);
	$query = "INSERT INTO $tablejobs (created_by,pdbid,pdbfileid,mutation,".
	"flex, relax, ip,tsubmit) ".
	"VALUES('$userid','$pdbstr','$pdbfileid','$muti',".
	"'$method','$prerelax','$ip',NOW())";
	mysql_query($query);
	$id[$i] = mysql_insert_id();
}

mysql_close(); 
?>

<html>
<body>
<title> Protein stability prediction server</title>
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
			<a href="help.php" class="nav"><div class='navi'> Help </div></a><br/>
			<a href="contact.php" class="nav"><div class='navi'> Contact Us</div></a><br/>
		</div>
		
		<div id="main_content"><div class="indexTitle"> Submit a task. </div>

<div class="indexStatus">
	Submission succeeded!
</div>
<table class="queryTable"> 
<tr class="queryTitle"> 
<td>Job Id </td> 
<td>Protein </td> 
<td>Mutation </td> 
<td>Backbone</td> 
<td>Pre-relaxation</td> 
<td>Note</td>
</tr> 
<?php
$i=0;
$cwarning = "";
$cwarningsymbol = "<font color=\"orange\"> C </font>";
foreach ($mutation as $muti) {
		$i++;
		echo "<tr>";
		echo "<td> $id[$i] </td>";
		echo "<td>$pdbstr</td>";
		echo "<td>";
		$chars = preg_split('/ +/', $muti);
		foreach ($chars as $mut) {
			echo $mut."<br>";
		}
		echo "</td>";
		$methodstr= $method==1?"flexible":"fixed";
		$prerelaxstr = $prerelax==1?"yes":"no";
		echo "<td>$methodstr</td>";
		echo "<td>$prerelaxstr</td>";
		if (eregi('C',$muti)){ 
			$cwarning = 1;
			echo "<td> $cwarningsymbol </td>";			
		}else{
			echo "<td></td>";
		}
		echo "</tr>";
}
?>
</table>
<br><br><br>
<?php
if($cwarning) {
	echo "Warnings:<br>";
	echo "$cwarningsymbol : Due to difficulty in modeling disulfide bond, cystein related mutations are not supported yet.<br>";
}
?>		</div>
		<div id="rightbar">
			<div id="ataglance">
				your infomration at a glance.
			</div>
		</div>
	</div>
</body>
</html>
