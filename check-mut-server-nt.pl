#!/usr/bin/perl -w
$pdbf = $ARGV[0];
$mutation = $ARGV[1];

$pdbfile = $pdbf;
@AA3=('ALA','CYS','ASP','GLU','PHE','GLY','HIS','ILE','LYS','LEU',
     'MET','ASN','PRO','GLN','ARG','SER','THR','VAL','TRP','TYR');
@AA1=('A','C','D','E','F','G','H','I','K','L',
        'M','N','P','Q','R','S','T','V','W','Y');
$AA321{'test'} = 'A';
for ($i=0;$i<@AA3;$i++) { $AA321{$AA3[$i]} = $AA1[$i];}
for ($i=0;$i<@AA3;$i++) { $AA123{$AA1[$i]} = $AA3[$i];}
open(PDB,"<$pdbfile") or die "cannot open pdb file $pdbfile";
$resPatt = '^REMARK   \d RESOLUTION. ([\d\.]+)\s*ANGSTROMS.';
$nres = 0;
$nstart = 0;
$nend = 0;
@chain0 = ();
$flagHeader = 1;
$flagchain = 0;
$flag_unnatural = 0;
$resiold = '';
$index = 0;
foreach(<PDB>) {
        $res = $1 if /$resPatt/;
        if (/^ATOM.{13}(.{3}).{2}(.{4})/ ) {
                $resname = $1;
                $resi = $2;
		$resi =~ s/\s//g;
		if (! $AA321{$resname} ) {# unatural amino acid
			$nres = 0;
			$flag_unnatural = 1; 
			last;
		};
		if ($resi eq $resiold) { next; }  #same amino acid
                $chain0[$index] = $AA321{$resname};
		$index++;
		$resiold = $resi;
                if ($flagchain == 0) {$nstart = $resi; $flagchain = 1}  # record the first residue position in the pdb file
        }
        if (/^TER/) {$nend = $resi;last;}
}
close PDB;
$chainstring = join('',@chain0);
$nres = length($chainstring);
#print "resolution: $res\n";
#print "chain $nstart - $nend ($nres):\n$chainstring \n";

if (! $mutation) { #only check the pdf file if no mutation supplied
	print "$nres\n";
	print "$chainstring\n";
	exit;
}
#..................READ MUTATION DATA FILE AND VALIDATE MUTATION.....................#
if ( $flag_unnatural == 1) {
	print "Unatural amino acid is not supported yet. Please change/delete and upload again\n";
	exit;
}
@aas = $mutation=~m/([A-Z]\d+[A-Z])/g;    # match the I 4 V; pattern
if (@aas == 0) { print "Invalid mutation description $mutation \n"; exit 1;}
foreach $mut (@aas) {
	$mut=~m/([A-Z])(\d+)([A-Z])/g;
	$oaa = $1; $pos = $2; $naa = $3;
	$pos2 = $pos - 1;
	$pdbaa = substr($chainstring,$pos2,1);
	if ($pdbaa ne $oaa) {
		print "Mutation mismatch for $mut: $pdbaa is found at position $pos in pdb file\n";
	}
}

