



open(FD, $ARGV[0]) || die "$!";

while( <FD> )
{
  s/ *$//;
  s/^/<td>/;
  s/&/<\/td> <td>/;
  s/&/<\/td> <td><i>/;
  s/\\/<\/td><tr>/;
  s/\\//;
  
  print "$_";

}