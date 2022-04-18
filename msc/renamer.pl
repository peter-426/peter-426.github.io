
# use File::DosGlob qw( glob );

# @ARGV = map glob, @ARGV;

$directory = $ARGV[0];
opendir (DIR, $directory) or die $!;

while ($f1 = readdir(DIR))
{
  if (-d $f1)  # ignore directories
  {
    next;
  }
  $f2 = $f1;
  $f2 =~ s/^20180306_2mwm_r00000/carl-/;
  print("$directory/$f2\n");
  rename "$directory/$f1","$directory/$f2";
}
