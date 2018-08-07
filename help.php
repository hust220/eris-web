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
			<a href="profile.php" class="nav"><div class='navi'> User Profile </div></a><br/>
			<a href="help.php" class="nav"><div class='navi'> Help </div></a><br/>
			<a href="contact.php" class="nav"><div class='navi'> Contact Us</div></a><br/>
		</div>
		
		<div id="main_content"><div class="indexTitle"> The Eris server. </div>
			<h2> Introduction </h2>
			<p>
		<b>Eris</b>, which takes the name of Greek goddess of discord, is a protein 
			stability  prediction server.  
		
		 <b>Eris</b> server calculates the change of the protein stability induced by mutations (&Delta;&Delta;G)
		 utilizing the recently developed Medusa atomic-modeling suite.
		 In our test study, the &Delta;&Delta;G values of a large dataset (&gt; 500) were calculated and compared
		 with the experimental
			data and significant correlations are found.
			The correlation coefficients vary from 0.5 to 0.8. <b>Eris</b> also allows refinement of the protein
			structure when high-resolution
			structures are not available. Compared with many other stability prediction servers, our method is not specifically 
			trained using protein stability data and should be valid for a wider range of proteins. Furthermore, <b>Eris</b> models
			backbone flexibility, which turns out to be crucial for &Delta;&Delta;G estimation of small-to-large mutations.

			<p>

			<h2> Usage</h2>
			<h3> Interactive Single-Job Submission </h3>
			For every prediction request, the server requires a protein structure, which can be provided
			either by specifying a PDB identifier or by a direct upload. The users can then interactively
			select their interested mutations and the suitable method for the &Delta;&Delta;G prediction. 
			<h3> Multiple Job Submission </h3>
				Each submission requires the following inputs:
				<ul>
				<li> Pdb Id or Uploaded Pdb File: </li> 
					Users can either provide a pdb id or upload a pdb file.
					Note that currently only
					natural amino acids are allowed and only the first chain in the pdb file is recognized.
				<li> Mutations: </li>
					The mutation description is in the format of "XnY", where X is the one-letter code of the 
					wide-type residue (the server will check if X matches the pdb file to avoid mistakes),
					Y is the target mutation-residue and n is the position of the mutation site (counted from
					the first residue in the chain). 
					The description can be concatenated for multiple-site mutations.
					For example "T2A R8K" replace the theronine residue at position 2  with 
					alanine and arginine at position 8 with lysine.
				<li> Prediction Method </li>
					Users can choose to use the fixed-backbone or flexible-backbone method for the prediction.
					The fixed-backbone method is faster but cannot resolve atomic clashes by doing backbone-relaxation.
					<br> We suggest start with the fixed-backbone method and
					if the fixed-backbone prediction request fails (due to severe clashes) or a high backbone
					strain is expected within the mutant (for example, by substituting a small core residue with 
					a larger one), a flexible-backbone prediction is desirable. 
					
				<li> Pre-relaxation </li>
					By allowing pre-relaxation, the backbone structure of the wide-type protein is
					relaxed before stability prediction. Such treatment improves the
					prediction accuracy if the initial protein structure is not of high-resolution, therefore
					it is recommended.
				</ul>
				Each submission can consist up to 5 prediction requests using the same methods. 
				<p> 
				The standalone software of Eris is distributed by <a href='http://www.moleculesinaction.com'>Molecules in Action, LLC</a>.
			<h3> Results </h3>
				The submitted tasks are queued and calculated when there are available computational resources.
				The recently submitted tasks (since last login) are listed under the "Home/Overview" menu. All the previous
				 results are listed under the "Your Activities" menu, where you can also download the result structure or 
				 delete the job.

			<p>
			<a name="troubleshooting"></a><h2> Trouble Shooting</h2>
				<h3> "Error loading pdb file" </h3> 
				This error happends when our program encounters an error reading the pdb file. Possible reasons are:
				<ul>
				<li> Your pdb file contains unnatural amino acid. </li> 
				Our current implementation only supports 20 natural 
				amino acid types: ALA,CYS,ASP,GLU,PHE,GLY,HIS,ILE,LYS,LEU,MET,ASN,PRO,GLN,ARG,SER,THR,VAL,TRP,TYR. Existance
				of residues other than the listed types in the ATOM record can cause the error. 
				<li> Backbone heavy atoms are missing.</li>
				The program requires coordinates of all backbone N,C,and CA atoms for each
				residue, and will reconstruct other missing atoms from the input pdb file.
				</ul>
				<h3> "Only part of the protein are loaded" </h3> 
				Eris currently only works with single-chain proteins. Only the first chain and first model are read
				from the pdb files. The chains are identified by a TER record. All the HETATM record are also ignored.
				<h3> "The job fails" </h3> 
				Several reasons could cause the calculation to fail. If the protein chain has too many broken linkage, the 
				lexible-backbone calculation may fail. Please try a fixed-backbone calculation without pre-relaxation in
				this case. If the problem persists, please contact us and we will check the log files and correct the 
				problems for you.

			<p>
			<h2> Reference </h2>
				<ul>
				<li>
				<a href='http://dokhlab.unc.edu/publications.html#dd_plos-cb06'> 
				F. Ding, and N. V. Dokholyan, "Emergence of protein fold families through rational design" PLoS. Comput. Biol. 2, e85 (2006)
				</a>
				</li>
				<li>
				<a href='http://dokhlab.unc.edu/publications.html#ydd_nm07'>
				S. Yin, F. Ding, and N. V. Dokholyan, "Eris: an automated estimator of protein stability"  Nature Methods 4, 466-467 (2007)
				</a>
				</li>
				<li>
				<a href='http://dokhlab.unc.edu/publications.html#ydd_struct07'>
				S. Yin, F. Ding, and N. V. Dokholyan, "Modeling backbone flexibility improves protein stability estimation" Structure, 15: 1567-1576 (2007) 
				</a><br>
				</li>
				</ul>
			
		</div>
		<div id="rightbar">
			<div id="ataglance">
				your infomration at a glance.
			</div>
		</div>
	</div>
</body>
</html>
