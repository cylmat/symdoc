SHELL := /bin/bash

######
alias:
	alias s="./bin/symfony"
	alias sc="./bin/symfony console"
	alias sr="./bin/symfony composer"

##############
# GIT REBASE #
##############
# git config user.name ""
# git config user.email ""
rebase:
	git rebase main -s recursive -X theirs

#########
# CI/CD #
#########
.PHONY: cs tests stan md

cs:
	vendor/bin/phpcs

md:
	vendor/bin/phpmd src ansi phpmd.xml

stan:
	vendor/bin/phpstan --memory-limit=1G

tests:
	bin/phpunit

##########
# SERVER #
##########
PORT?=88
serve:
	./bin/symfony local:server:start --port=${PORT} -d

###########
#   BIN   #
###########
composer-bin:
	php -r 'copy("https://composer.github.io/installer.sig", "/tmp/installer.sig");'
	php -r 'copy("https://getcomposer.org/installer", "composer-setup.php");'
	php -r "if (hash_file('sha384', 'composer-setup.php') === file_get_contents('/tmp/installer.sig')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('/tmp/composer-setup.php'); } echo PHP_EOL;"
	php composer-setup.php --install-dir=bin --filename=composer
	php -r "unlink('composer-setup.php'); unlink('/tmp/installer.sig');"

deployer-bin:
	curl -LO https://deployer.org/releases/v6.8.0/deployer.phar
	mv deployer.phar /usr/local/bin/dep
	chmod +x /usr/local/bin/dep
	dep -V -f -

kint-bin:
	curl -LO https://raw.githubusercontent.com/kint-php/kint/master/build/kint.phar

symfony-bin:
	wget https://get.symfony.com/cli/installer -O /usr/local/bin/symfony-installer
	bash /usr/local/bin/symfony-installer --install-dir=/usr/local/bin
