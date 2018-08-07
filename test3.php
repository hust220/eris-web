<?php
# This script accept the pdbid / uploaded pdbfile and generate sequence
# it also fill in the hash of the pdbfile in the hidden form, where are
# used to retrieve the pdbfile from the database

require('config/config.inc.php');

function check_pdb($filename){
    include('config/para.inc.php');
    $temp_filted_pdb = "pdb/temp_filted.pdb";
    $temp_Medusa_pdb = "pdb/temp_Medusa.pdb";
    #filter the pdbfile first (filter out unkonw residue type)
    echo "$EXEC_PDBFILTER '$filename'  $temp_filted_pdb";
    exec("$EXEC_PDBFILTER '$filename'  $temp_filted_pdb",$output);
    #use medusa to read and generate pdbfile
    echo "export LD_LIBRARY_PATH=/home/html/lib:\$LD_LIBRARY_PATH && $EXEC_CHECKPDB $temp_filted_pdb > $temp_Medusa_pdb";
    exec("export LD_LIBRARY_PATH=/home/html/lib:\$LD_LIBRARY_PATH && $EXEC_CHECKPDB $temp_filted_pdb > $temp_Medusa_pdb",$output);
    #read the seq of the medusa generated pdbfile    
    unset($output);
    exec("$exec_check_mut $temp_Medusa_pdb", $output);
    #die(sprintf("<br>%s<br>%s<br>%s<br>%s<br>%s", $EXEC_CHECKPDB, $temp_filted_pdb, $temp_Medusa_pdb, $output[0], $output[1]));
    $nres=$output[0];
    $seq = $output[1];
    return(array($nres,$seq));
}

check_pdb('pdb/1SHM.pdb');

