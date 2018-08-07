<?php
#the iframe in the submit.php page
# accept the pdbid or uploaded files
# submissions are checked by submit3.php

require('config/config.inc.php');

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

?>


<html>
<head>
<title> Protein stability prediction server</title>
	<style type="text/css">
      <!--
        @import url(style/ifold.css);
        @import url(style/greybox.css);
      -->
    </style>

<script language="javascript">

function upload(){
	document.iform.submit();
}

</script>
    
</head>
<body style="text-align:left">
Please provide pdb code or upload a pdb file and submit the request. <br> <br>
<form name="iform" enctype="multipart/form-data" action="submit3.php" method="POST">
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
</tr>

<tr> <?php myerror("uploadedfile");?> </tr>
<tr>
<td> </td>
<td>
<input type="button" value="Next &gt &gt" onclick='upload();'/>
</td>
</tr>
</table>
</form>

</body>
</html>
