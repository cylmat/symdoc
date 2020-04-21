#!/bin/bash

#############################################
# - Fancy Git                               #
# https://github.com/diogocavilha/fancy-git #
#############################################

if [[ ! -d $HOME/.fancy-git ]]; then
	  echo "Fancygit not installed in $HOME/.fancy-git!"
	    echo 'Please "curl -sS https://raw.githubusercontent.com/diogocavilha/fancy-git/master/install.sh | sh"' 
fi;

fancygit configure-fonts
fancygit simple

echo "Fancygit simple setted."
