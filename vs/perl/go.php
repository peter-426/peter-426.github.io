<pre>
# go.pl
#
# submits all classads in each docking folder specified on command line.
#
# Author:  Peter Hebden,  phebden@gmail.com

# docking-ID is the folder that contains classad files and Vina in/output files

use Cwd;

$#ARGV == 0 || die "Usage: $0 docking-ID\n";  

$dr = "$ARGV[0]";
print "\n";

@dirs = glob "$dr*";

foreach $d (@dirs) 
{
  chdir $d;
  print "$d \n";
  @files = glob "*.classad";

  foreach $f (@files)
  {
      `condor_submit $f`;     #  submits classad to condor, job waits in queue as Idle until Run.
       print "$f \n";
  }
  chdir "..";
}

