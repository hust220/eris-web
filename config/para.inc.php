<?php
$dbusername="other_svc";
$dbpassword="final-S3cr3t";
#$dbusername="ddg";
#$dbpassword="ddg4me";
$database="ddg";
$tablepdbfiles="pdbfiles3";
$tablejobs = "jobs3";
$tableusers = "users3";
$target_path = "uploads/";
$MMut = 5;  # maximum mutations allowed in one submission
#$exec_check_mut = "bin/check-mut-server.linux";
$exec_check_mut = "bin/check-mut-server-nt.pl ";
$MEDUSA_HOME = "workspace/Medusa3";
$EXEC_CHECKPDB = "$MEDUSA_HOME/bin/checkPDB.linux $MEDUSA_HOME/parameter ";
$EXEC_PDBFILTER = "bin/pdbfilter.pl ";
#$EXEC_PDBFILTER = "bin/pdbfilter.linux ";

?>
