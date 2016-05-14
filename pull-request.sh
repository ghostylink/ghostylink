 #!/bin/bash -e
 
 awk '/diff --git .*/{x=$4;"dirname "x|& getline $test; system("mkdir -p \"" $test "\"");}/^+\s/{print > x;} ' pull-request.diff
