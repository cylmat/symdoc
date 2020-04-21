#!/bin/bash

#######################
# GIT PROMPT          #
# https://git-scm.com #
#######################

# unstaged(*) and staged(+)
export GIT_PS1_SHOWDIRTYSTATE=1

# stashes ($)
export GIT_PS1_SHOWSTASHSTATE=1

# untracked (%)
export GIT_PS1_SHOWUNTRACKEDFILES=1

# upstream (<=>)
# auto verbose name legacy git
export GIT_PS1_SHOWUPSTREAM="verbose"

export GIT_PS1_STATESEPARATOR=" "

# contains (v1.6.3.2~35)
# branch (master~4)
# describe (v1.6.3.1-13-gdd42c2f)
# tag (v1.6.3.1-13-gdd42c2f)
# default
export GIT_PS1_DESCRIBE_STYLE="branch"

export GIT_PS1_SHOWCOLORHINTS=1

# do nothing if not git repo
export GIT_PS1_HIDE_IF_PWD_IGNORED=1

#export PS1='\w$(__git_ps1 " (%s)")\$ '
#export PS1='[\u@\h \W$(__git_ps1 " (%s)")]\$ '

RED="\[\033[0;31m\]"
YELLOW="\[\033[0;33m\]"
GREEN="\[\033[0;32m\]"
BLUE="\[\033[0;34m\]"
LIGHT_RED="\[\033[1;31m\]"
LIGHT_GREEN="\[\033[1;32m\]"
LIGHT_BLUE="\[\033[1;34m\]"
LIGHT_GRAY="\[\033[0;37m\]"
LIGHT_YELLOW="\[\033[1;33m\]"
WHITE="\[\033[1;37m\]"
COLOR_NONE="\[\e[0m\]"

export PROMPT_COMMAND="__git_ps1 '$LIGHT_GREEN\u$COLOR_NONE@\h:$LIGHT_BLUE\w$COLOR_NONE' '\$ '"
# or
#export PS1="\u@\h $GREEN\w$COLOR_NONE$(__git_ps1 " (%s) ")$ "
