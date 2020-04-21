SHELL := /bin/bash

##############
# GIT REBASE #
##############
rebase:
	git rebase main -s recursive -X theirs

###########
#   BIN   #
###########
deployer-bin:
	curl -LO https://deployer.org/releases/v6.8.0/deployer.phar
	mv deployer.phar /usr/local/bin/dep
	chmod +x /usr/local/bin/dep
	dep -V -f -

symfony-cli:
	wget https://get.symfony.com/cli/installer -O - | bash
	mv /root/.symfony/bin/symfony /usr/local/bin/symfony