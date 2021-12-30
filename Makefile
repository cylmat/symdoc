SHELL := /bin/bash

######
alias:
	alias s="./bin/symfony"
	alias sc="./bin/symfony console"
	alias sr="./bin/symfony composer"

# Submodule for Bundle
# Add cylmat/phpext php langage and extensions playground
phpext:
	git submodule add https://github.com/cylmat/phpext-sample.git Bundle/Phpext 

##############
# GIT REBASE #
##############
# git config user.name "cylmat"
# git config user.email "cyrilmatte.pro@gmail.com"
rebase:
	git rebase main -s recursive -X theirs

#########
# CI/CD #
#########
cs:
	vendor/bin/phpcs

tests:
	bin/phpunit
.PHONY: tests

##########
# SERVER #
##########
serve:
	./bin/symfony serve --port=88 -d

###########
#   BIN   #
###########
composer-bin:
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"

deployer-bin:
	curl -LO https://deployer.org/releases/v6.8.0/deployer.phar
	mv deployer.phar /usr/local/bin/dep
	chmod +x /usr/local/bin/dep
	dep -V -f -

kint-bin:
	curl -LO https://raw.githubusercontent.com/kint-php/kint/master/build/kint.phar

symfony-bin:
	wget https://get.symfony.com/cli/installer -O - | bash
	mv /root/.symfony/bin/symfony /usr/local/bin/symfony