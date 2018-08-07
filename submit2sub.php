<?php
require('config/config.inc.php');
?>
<?php
	if (empty($_SESSION['username'])){
		header("Location: login.php");
	}
?>

<?php 
#print_r($_GET);
#--------save the form data-------------------------#

$error = array();
#-------get sumitted data and check format--------#
$hash = $_GET['hash'];
#$pdbname = $_GET['pdbname'];
if ( eregi('[^0-9a-zA-Z]',$hash)) {
	die("invalid hash");
}
#if ( eregi('[^0-9a-zA-Z]',$pdbname)) {
#	die("invalid pdbname");
#}
$mut = strtoupper($_GET["mut"]);
if (strlen($mut>500) || !preg_match('/^(\s*[ACDEFGHIKLMNPQRSTVWY][0-9]+[ACDEFGHIKLMNPQRSTVWY]\s*)+$/',$mut)) 
{ 
	die("invalid mutation");
}
$method=$_GET["method"];
if ( ($method !=0) && ($method !=1)) { die("wrong method");};
$prerelax=$_GET['prerelax'];
if ($prerelax == "true" ) {
	$prerelax = 1;
}elseif ($prerelax == "false") {
	$prerelax = 0;
}else{ 
	die("wrong prerelax method");
};
$emailFlag=$_GET['emailFlag'];
if ($emailFlag == "true" ) {
	$emailFlag = 1;
}else{
	$emailFlag = 0;
};

$ip=$_SERVER['REMOTE_ADDR'];
#-----------check if the pdb file exists in database------------#
$query = "SELECT id,pdbid FROM $tablepdbfiles WHERE hash='$hash'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
if ($row) { # pdb already exists
	$pdbfileid = $row['id'];
	$pdbname = $row['pdbid'];
}else{
	die("invalid hash check");
}


### not we have the pdbfileid for the submission ###
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
unset($output);
exec("$exec_check_mut $tmppdbfile '$mut' ", $output);
$errlength = sizeof($output);
if ($errlength > 0) {
	die("wrong mutations");
}
unlink($tmppdbfile);

#-----------------update entry in database-------------------------#

$userid = $_SESSION['userid'];
$mut= preg_replace('/\s+/',' ',$mut);
$query = "INSERT INTO $tablejobs (created_by,pdbid,pdbfileid,mutation,".
"flex, relax, ip,tsubmit,emailFlag) ".
"VALUES('$userid','$pdbname','$pdbfileid','$mut',".
"'$method','$prerelax','$ip',NOW(),'$emailFlag')";
mysql_query($query) or die ("job sumission failed");
$id = mysql_insert_id();

mysql_close(); 
?>


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

$cwarning = "";
$cwarningsymbol = "<font color=\"orange\"> C </font>";
	echo "<tr>";
	echo "<td> $id </td>";
	echo "<td>$pdbname</td>";
	echo "<td>";
	$chars = preg_split('/ +/', $mut);
	$i = 0;
	foreach ($chars as $mutii) {
		if ($i>0) {echo "<br>";}
		echo $mutii;
		$i++;
	}
	echo "</td>";
	$methodstr= $method==1?"flexible":"fixed";
	$prerelaxstr = $prerelax==1?"yes":"no";
	echo "<td>$methodstr</td>";
	echo "<td>$prerelaxstr</td>";
	if (eregi('C',$mut)){ 
		$cwarning = 1;
		echo "<td> $cwarningsymbol </td>";			
	}else{
		echo "<td></td>";
	}
	echo "</tr>";
?>
</table>
Please check back later for the results.
</div>
<br><br><br>
<?php
if($cwarning) {
	echo "Warnings<br>";
	echo "$cwarningsymbol : Due to difficulty in modeling disulfide bond, cysteine related mutations are not supported yet.<br>";
}
?>
