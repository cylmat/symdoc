# ~/.bashrc: executed by bash(1) for non-login shells.

###########
# SAMPLES #
# https://gist.github.com/zachbrowne/8bc414c9f30192067831fafebd14255c
###########

# Note: PS1 and umask are already set in /etc/profile. You should not
# need this unless you want different defaults for root.
# PS1='${debian_chroot:+($debian_chroot)}\h:\w\$ '
# umask 022

#########
# ALIAS #
#########
[[ -f $HOME/.bash-aliases ]] && source $HOME/.bash-aliases

#########
# LOCAL #
# export LANGUAGE=en_US.UTF-8
# export LANG=en_US.UTF-8
# export LC_ALL=en_US.UTF-8
#########

##################
# Autocompletion #
# Use "apt install bash-completion"


if [[ ! -d ~/.bash_it ]]; then
  git clone --depth=1 https://github.com/Bash-it/bash-it.git ~/.bash_it
  cp ~/.bash_it/template/bash_profile.template.bash ~/.bash_it/bash-it
fi
. ~/.bash-it
