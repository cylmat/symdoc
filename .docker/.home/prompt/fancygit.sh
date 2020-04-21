#!/bin/bash

#############################################
# - Fancy Git                               #
# https://github.com/diogocavilha/fancy-git #
#############################################

if [[ ! -d $HOME/.fancy-git ]]; then
  echo "Fancygit not installed in $HOME/.fancy-git!"
  echo "Please install fontconfig"
  echo 'And "curl -sS https://raw.githubusercontent.com/diogocavilha/fancy-git/master/install.sh | bash"'
  return
fi;

fancygit configure-fonts
# simple, human-single-line, human-dark-single-line, etc... 
# then "source ~/.fancy-git/prompt.sh" again
fancygit simple

source ~/.fancy-git/prompt.sh

echo "Fancygit simple setted."
