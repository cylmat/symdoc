#!/bin/bash

##############
# GIT PROMPT #
##############
# https://raw.githubusercontent.com/git/git/master/contrib/completion/git-completion.bash
# https://github.com/git/git/blob/master/contrib/completion/git-prompt.sh

PROMPT="~/prompt"

### CHANGE PROMPT
if [[ ! -f $PROMPT/git-prompt.sh ]] || [[ ! -f $PROMPT/git-completion.bash ]]; then
  echo "git-prompt or git-completion not found!"
  return 1
fi

source $PROMPT/git-completion.bash
source $PROMPT/git-prompt.sh

##########################
# CHOOSE ONE PROMPT FILE #
# default, fancygit, git, informative, matthew
##########################
[[ -z "$SET_CUSTOM_PROMPT" ]] && SET_CUSTOM_PROMPT="informative"

if [[ $SET_CUSTOM_PROMPT != 'default' ]]; then
  CUSTOM_PROMPT="${SET_CUSTOM_PROMPT}.sh"
  if [[ -f $PROMPT/$CUSTOM_PROMPT ]]; then
    export PROMPT_COMMAND=''
    source $PROMPT/$CUSTOM_PROMPT
  else 
    echo "$PROMPT/$CUSTOM_PROMPT not found!"
  fi
else
  echo "Default prompt selected"
  export PROMPT_COMMAND=''
  export PS1='${debian_chroot:+($debian_chroot)}\u@\h:\w\$ '
fi
