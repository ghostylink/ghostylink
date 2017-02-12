export PATH="$PATH:$PWD/node_modules/.bin/"
alias ant="$PWD/vendor/bin/phing"
alias lssc="find build/logs/selenium-screenshots/ -type f -printf '%T@ %p\n' | sort -n | tail -1 | cut -f2- -d\" \"  |xargs eog"