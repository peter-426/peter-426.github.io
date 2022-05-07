<pre>
# dock.pm
#
# This module is used by a unique perl script for each classad, 
# and runs Vina to dock ligands with receptor.
#
# Author:  Peter Hebden,  phebden@gmail.com

use Cwd;

sub runDock()
{
	$d = getcwd;

	$vina = "/common/home/YOUR-LOGIN-ID/bin/vina";
	$startingDir = "$d";

	$ligandFolder   = "$startingDir/ligand";
	$receptorFolder = "$startingDir/receptor-$GN";
	$outFolder      = "$startingDir/out-$GN";
	$logFolder      = "$startingDir/log-$GN";

	print "\nstarting dir = $startingDir \n";
	print "ligandFolder   = $ligandFolder \n";
	print "receptorFolder = $receptorFolder \n";
	print "outFolder      = $outFolder \n";
	print "logFolder      = $logFolder \n\n";


	-d $receptorFolder || die "receptor folder does not exist\n"; 
	-d $ligandFolder   || die "ligand folder does not exist\n"; 

	mkdir $logFolder;
	mkdir $outFolder;

	chdir($ligandFolder) or die "Error: failed to chdir to $path $!";

	opendir(DIR, $ligandFolder);
	@files = sort ( grep /pdbqt$/, readdir(DIR) ); 
	closedir(DIR);

	$count = 0;

	print "Docking ... \n";
	$t = localtime();
	print "$t\n";

	$config   = "${receptorFolder}/conf.txt";
	$receptor = "$receptorFolder/$GN.pdbqt";

	-e $config   || die "config file does not exist\n"; 
	-e $receptor || die "receptor file does not exist\n"; 

	print "\n config=$config \n receptor=$receptor \n";
	$max = $#files;

	if( $start < 0 ) { $start = 0; }

	if( $end > $max )  { $end = $max; }

	if ($start > $end ) {  $start = $end;  }

	@files = @files[$start..$end]; #    here we select a slice of ligands from the ligand folder

	foreach $file (@files) 
	{
	   if ($file =~ /.*pdbqt$/) 
	   {
		  print "$file \n";
		  $count += 1;
		  if ($count % 100 == 1) { print "$count >> $ligandFolder/$file\n";  }  # feedback for when not using nohup
		  
		  $ligand   = "$ligandFolder/$file";
		  $out      = "$outFolder/out_$file";
		  $log      = "$logFolder/${file}_log.txt";
		  
		  if ( ! -e $out ) 
		  {
			`$vina --config $config --ligand $ligand --receptor $receptor --out $out --log $log`  
		  }
	   }   
	}
	$t = localtime();
	print "$t\n";

	print "docked $count ligands\n";
}
1
