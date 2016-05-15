 #!/bin/bash -e
 
 awk '/^diff --git .*/{x=$4;"dirname "x|& getline $test; system("mkdir -p \"" build/quality/tasks-scanner/$test "\"");}/^+\s/{print > x;} ' pull-request.diff
