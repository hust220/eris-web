<?php
# This script accept the pdbid / uploaded pdbfile and generate sequence
# it also fill in the hash of the pdbfile in the hidden form, where are
# used to retrieve the pdbfile from the database

require('config/config.inc.php');

if (empty($_SESSION['username'])){
    header("Location: login.php");
}

function mydie($str,$var){
    $_SESSION["error_$var"] = $str;
    header("Location: submit2iframe.php");
    die;
}

function check_pdb($filename){
    include('config/para.inc.php');
    $temp_filted_pdb = "pdb/temp_filted.pdb";
    $temp_Medusa_pdb = "pdb/temp_Medusa.pdb";
    #filter the pdbfile first (filter out unkonw residue type)
    exec("$EXEC_PDBFILTER '$filename'  $temp_filted_pdb",$output);
    #use medusa to read and generate pdbfile
    exec("source /home/html/local/env.sh && $EXEC_CHECKPDB $temp_filted_pdb > $temp_Medusa_pdb",$output);
    #read the seq of the medusa generated pdbfile    
    unset($output);
    exec("$exec_check_mut $temp_Medusa_pdb", $output);
    #die(sprintf("<br>%s<br>%s<br>%s<br>%s<br>%s", $EXEC_CHECKPDB, $temp_filted_pdb, $temp_Medusa_pdb, $output[0], $output[1]));
    $nres=$output[0];
    $seq = $output[1];
    return(array($nres,$seq));
#    unlink($temp_filted_pdb);
#    unlink($temp_Medusa_pdb);
}

#--------save the form data-------------------------#
echo "save the form data";
$varlist = array("pdbid","mut0",'mut1','mut2','mut3','mut4','method','prerelax','uploadedfile','numberofrows');
foreach ($varlist as $var) {
    $_SESSION["save_$var"] = $_POST[$var];
}
foreach ($varlist as $var) {
    unset( $_SESSION["error_$var"]);
}

$error = array();
echo "get submitted data and check format";
#-------get sumitted data and check format--------#
$pdbid=$_POST['pdbid'];
$pdbid = trim(strtoupper($pdbid));
if (strlen($pdbid) != 4 || eregi('[^0-9a-zA-Z]',$pdbid)) {
    if ($_FILES['uploadedfile']['size'] == 0 ) {
        $error['pdbid']=("Invalid pdbid\n");
    }
}
$ip=$_SERVER['REMOTE_ADDR'];
#-----------return if error---------------------------------#
if (count($error) > 0) {
    foreach ($varlist as $var) {
        $_SESSION["error_$var"] = $error[$var];
    }
    header("Location: submit2iframe.php");
    die;
}
#-----------check if the pdb file exists in database------------#
if ( $pdbid) {
    $query = "SELECT id FROM $tablepdbfiles WHERE pdbid='$pdbid'";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    if ($row) { # pdb already exists
        $pdbfileid = $row['id'];
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
        #check the pdbfile first
        list($nres,$seq) = check_pdb($tmppdbfile);
        if( $nres == 0 ) { mydie("Error loading pdb file. See <a href='help.php#troubleshooting' target='_parent'>trouble shooting page</a> for details",'pdbid');}
        #calculate the hash
        unset($output);
        exec("md5sum $tmppdbfile|cut -d' ' -f1",$output);
        if (sizeof($output) == 0) {
            mydie("Fail to checksum pdbfile $pdbid.",'pdbid');
        }
        $hash = $output[0];
        
        unlink($tmppdbfile);

    }else { #need download the file
        unset($output);
        exec("/usr/bin/wget -P pdb http://pdbbeta.rcsb.org/pdb/files/$pdbid.pdb",$output);
        if (! is_file("pdb/$pdbid.pdb") ) {
            mydie("Failed to download pdb of $pdbid! Please check spelling or try later","pdbid");
        }else{ #update the database
            #check the pdbfile first
            list($nres,$seq)=check_pdb("pdb/$pdbid.pdb");
            if( $nres == 0 ) { mydie("Error loading pdb file. See <a href='help.php#troubleshooting' target='_parent'>trouble shooting page</a> for details",'pdbid');}
            #calculate the hash
            $hash = "";
            unset($output);
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
    list($nres,$seq)=check_pdb($pdbfile);
    if( $nres == 0 ) { mydie("Error loading pdb file. See <a href='help.php#troubleshooting' target='_parent'>trouble shooting page</a> for details",'pdbid');}
    unset($output);
    exec("md5sum $pdbfile|cut -d' ' -f1",$output);
    if (sizeof($output) == 0) {
        mydie("Fail to checksum pdbfile $pdbid.",'uploadedfile');
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
mysql_close(); 
if (!($hash && $seq) ){
    die("error");
}

?>

<html>
<head>

<script language="javascript">
myPdbHash = '<?php echo $hash; ?>' ;
seq = '<?php echo $seq; ?>' ;
submitError = 0;
var par = window.parent.document;
var hashfield = par.getElementById("hash");
var seqfield = par.getElementById("seq");
var errorfield = par.getElementById("error");
hashfield.value = myPdbHash;
seqfield.value= seq;
errorfield.value=submitError;
</script>


</head>
</html>
