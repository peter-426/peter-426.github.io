<pre>
# mk.pl
#
# Makes a set of classads for CONDOR.  Each classad
# defines a subset of ligand files to be docked.
# User specifies gene name, start index, sub set size, max startindex.
# May generate hundreds of classads.
#
# Author :  Peter Hebden,  phebden@gmail.com

$#ARGV == 3 || die "Usage: $0  GN start size maxStart";

$GN        = $ARGV[0];   # Gene name
$START     = $ARGV[1];   # starting with ligand file index number
$SIZE      = $ARGV[2];   # number of ligands in this batch
$MAX_START = $ARGV[3];   # maximum index of first ligand in batch;  
                         # last batch size may be truncated if not that many ligands 

for($i = $START; $i <= $MAX_START; $i += $SIZE)
{
	open(FD, ">d_${GN}_${i}.classad") || die "$!"; # save classad to file

	$end = $i + $SIZE - 1;
	print "\n making ads for GN=$GN $i to $end \n\n";    # following lines will be a classad
	print FD "
	universe     = vanilla
	Executable   = /usr/bin/perl
	Log          = perl.log

	RESERVED_SWAP = 0
		  
	Error   = err.\$(Process)
	Input   = dock_${GN}_${i}_${end}.pl
	Output  = out.\$(Process)
	Log     = foo.log

	Queue";
	close FD;
	open(FD, ">dock_${GN}_${i}_${end}.pl") || die "$!";  # save to file; parameters used by dock.pm

	print FD "use dock;

	\$GN = \"$GN\";

	\$start = \"$i\";
	\$end   = \"$end\";

	runDock();\n";

	close FD;
}
