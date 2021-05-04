https://symfony.com/doc/5.0/the-fast-track/fr/index.html

# Etape 2: Projet
symfony server:ca:install
symfony book:check-requirements
symfony new --version=5.2-1 --book guestbook
symfony book:checkout 10.2
git diff step-10-1...step-10-2
git log -- src/Controller/ConferenceController.php

# Etape 3: Prod
[https://github.com/symfony/skeleton]
symfony new guestbook --version=5.2
symfony server:start -d
symfony open:local
symfony server:log
[https://symfony.com/cloud/]
    symfony project:create --title="Guestbook" --plan=development
    symfony deploy
    symfony open:remote
    symfony server:ca:install

* https://flex.symfony.com/
* https://github.com/symfony/recipes
* https://symfony.com/doc/current/setup/symfony_server.html

# Etape 4: Méthode
git

# Etape 5: Diagnostic

**Composants Symfony** : Paquets qui implémentent les fonctionnalités de base et les abstractions de bas niveau dont la plupart des applications ont besoin (routage, console, client HTTP, mailer, cache, etc.) ;
**Bundles Symfony** : Paquets qui ajoutent des fonctionnalités de haut niveau ou fournissent des intégrations avec des bibliothèques tierces (les bundles sont principalement créés par la communauté).

symfony composer req profiler --dev
_profiler est un alias pour le paquet symfony/profiler-pack._
APP_ENV=dev => .env => .env.local
symfony composer req logger
symfony composer req debug --dev
symfony server:log
<?php dump($request); ?>
php.ini: xdebug.file_link_format=vscode://file/%f:%l
symfony logs
symfony ssh

* https://symfonycasts.com/screencast/symfony4-fundamentals/environment-config-files
* https://symfonycasts.com/screencast/symfony-fundamentals/environment-variables
* https://symfonycasts.com/screencast/symfony/debug-toolbar-profiler
* https://symfony.com/doc/current/configuration.html#managing-multiple-env-files

# Etape 6: Contrôleur
symfony composer req maker --dev
symfony console list make
symfony composer req annotations
symfony console make:controller ConferenceController

* https://symfony.com/doc/current/routing.html
* https://symfonycasts.com/screencast/symfony/route-controller
* https://www.doctrine-project.org/projects/doctrine-annotations/en/1.6/annotations.html
* https://symfony.com/doc/current/components/http_foundation.html
* https://owasp.org/www-community/attacks/xss/
* https://github.com/andreia/symfony-cheat-sheets/blob/master/Symfony4/routing_en_part1.pdf

# Etape 7: Database
symfony run psql
docker-compose exec database psql main
[https://symfony.com/cloud/]
    symfony tunnel:open --expose-env-vars
    symfony run psql
    symfony tunnel:close
symfony var:export

# Etape 8: Datastructure
symfony composer req "orm:^2"
    config/packages/doctrine.yaml
symfony console make:entity Conference
symfony console make:entity Comment
    comments OneToMany Comment
symfony console make:migration
symfony console doctrine:migrations:migrate

* https://symfony.com/doc/current/doctrine.html
* https://symfonycasts.com/screencast/symfony-doctrine/install
* https://symfony.com/doc/current/doctrine/associations.html
* https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html

# Etape 9: Admin
symfony composer req "admin:^2.3"
    config/packages/easy_admin.yaml
    config/routes/easy_admin.yaml

* https://symfony.com/doc/2.x/bundles/EasyAdminBundle/index.html
* https://symfonycasts.com/screencast/easyadminbundle
* https://symfony.com/doc/current/reference/configuration/framework.html

# Etape 10: Interface (Twig)
symfony composer req twig
symfony composer req "twig/intl-extra:^3" (format_datetime)
    path('conference')
use Doctrine\ORM\Tools\Pagination\Paginator;

* https://twig.symfony.com/doc/2.x/
* https://symfony.com/doc/current/templates.html
* https://symfonycasts.com/screencast/symfony/twig-recipe
* https://symfony.com/doc/current/reference/twig_reference.html
* https://symfony.com/doc/current/controller.html#the-base-controller-classes-services

# Etape 11: Branches
git
symfony env:sync
symfony env:debug --on --off
symfony env:delete --env=sessions-in-redis --no-interaction

* https://www.git-scm.com/book/fr/v2/Les-branches-avec-Git-Les-branches-en-bref
* https://redis.io/documentation

# Etape 12: Listen to Event
    ( Http, Security, Messenger, Workflow ou Mailer )
symfony console make:subscriber TwigEventSubscriber
kernel.controller

* https://symfony.com/doc/current/components/http_kernel.html#the-workflow-of-a-request
* https://symfony.com/doc/current/reference/events.html
* https://symfony.com/doc/current/components/console/events.html
* https://symfony.com/doc/current/event_dispatcher.html#creating-an-event-subscriber

# Etape 13: Doctrine lifecycle
@ORM\HasLifecycleCallbacks()
@ORM\PrePersist
symfony console make:entity Conference
symfony console make:migration
symfony console doctrine:migrations:migrate
symfony composer req string
- EntityListener (service.yaml, tags doctrine.orm.entity_listener)

**Un service** est un objet « global » qui fournit des fonctionnalités
Injection contener (mailer, logger, slugger)

* https://symfony.com/doc/current/doctrine/events.html
* https://symfony.com/doc/current/components/string.html
* https://symfony.com/doc/current/service_container.html
* https://github.com/andreia/symfony-cheat-sheets/blob/master/Symfony4/services_en_42.pdf

# Etape 14: Forms
symfony console make:form CommentFormType Comment
    $this->createForm(CommentFormType::class, $comment);
    {{ form(comment_form) }}

* https://symfonycasts.com/screencast/symfony-forms
* https://symfony.com/doc/current/form/form_customization.html
* https://symfony.com/doc/current/forms.html#validating-forms
* https://symfony.com/doc/current/reference/forms/types.html
* https://github.com/thephpleague/flysystem-bundle/blob/master/docs/1-getting-started.md
* https://symfony.com/doc/current/configuration.html#configuration-parameters
* https://symfony.com/doc/current/validation.html#basic-constraints
* https://github.com/andreia/symfony-cheat-sheets/blob/master/Symfony2/how_symfony2_forms_works_en.pdf

# Etape 15: Security
symfony composer req security
symfony console make:user Admin
    config/packages/security.yml
symfony console make:migration
symfony console doctrine:migrations:migrate -n
symfony console security:encode-password

symfony run psql -c "SQL INSERT MY '<encrypted_pass>'"
symfony console make:auth
symfony console debug:router
symfony console make:registration-form

* https://symfony.com/doc/current/security.html
* https://symfonycasts.com/screencast/symfony-security
* https://symfony.com/doc/current/security/form_login_setup.html
* https://github.com/andreia/symfony-cheat-sheets/blob/master/Symfony4/security_en_44.pdf

# Etape 16: API externe
    akismet
symfony composer req http-client
    src/SpamChecker
symfony console secrets:set AKISMET_KEY
symfony var:set --sensitive AKISMET_KEY=abcdef
APP_ENV=prod symfony console secrets:generate-keys
APP_ENV=prod symfony console secrets:set AKISMET_KEY

symfony var:set --sensitive SYMFONY_DECRYPTION_SECRET=`php -r 'echo base64_encode(include("config/secrets/prod/prod.decrypt.private.php"));'
rm -f config/secrets/prod/prod.decrypt.private.php

* https://symfony.com/doc/current/components/http_client.html
* https://symfony.com/doc/current/configuration/env_var_processors.html
* https://github.com/andreia/symfony-cheat-sheets/blob/master/Symfony4/httpclient_en_43.pdf

# Etape 17: Tests
symfony composer req phpunit --dev
symfony console make:unit-test SpamCheckerTest
symfony php bin/phpunit
symfony composer req browser-kit --dev
symfony console make:functional-test Controller\\ConferenceController
APP_ENV=test symfony console secrets:set AKISMET_KEY

symfony composer req orm-fixtures --dev
symfony console debug:autowiring encoder
APP_ENV=test symfony console doctrine:fixtures:load
symfony php bin/phpunit tests/Controller/ConferenceControllerTest.php
make tests

symfony composer req "dama/doctrine-test-bundle:^6" --dev
    phpunit.xml.dist
    <extension class="DAMA\DoctrineTestBundle\PHPUnit\PHPUnitExtension" />
    <listener class="Symfony\Bridge\PhpUnit\SymfonyTestsListener" />

symfony composer req panther --dev

* https://symfony.com/doc/current/testing/functional_tests_assertions.html
* https://phpunit.de/documentation.html
* https://github.com/fzaninotto/Faker
* https://symfony.com/doc/current/components/css_selector.html
* https://github.com/symfony/panther
* https://www.gnu.org/software/make/manual/make.html

# Etape 18: Async
symfony console make:entity Comment -> add "STATE"
symfony composer req messenger
    CommentMessage, CommentMessageHandler()
config/packages/messenger.yaml
symfony run pg_dump --data-only > dump.sql  (export)
symfony run psql < dump.sql                 (import)
symfony console messenger:consume async -vv
symfony open:local:rabbitmq
symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async
symfony server:status && kill 15774
symfony console messenger:failed:show
symfony logs --worker=messages all

* https://symfonycasts.com/screencast/messenger
* https://fr.wikipedia.org/wiki/Enterprise_service_bus
* https://martinfowler.com/bliki/CQRS.html
* https://symfony.com/doc/current/messenger.html
* https://www.rabbitmq.com/documentation.html

# Etape 19: Workflow
symfony composer req workflow
    config/packages/workflow.yaml
apt-get install -y graphviz
symfony console workflow:dump comment | dot -Tpng -o workflow.png

* https://symfony.com/doc/current/workflow/workflow-and-state-machine.html
* https://symfony.com/doc/current/workflow.html

# Etape 20: Emails
symfony composer req mailer
    config/packages/mailer.yaml
symfony open:local:webmail
symfony env:setting:set email on

* https://symfonycasts.com/screencast/mailer
* https://get.foundation/emails/docs/inky.html
* https://symfony.com/doc/current/configuration/env_var_processors.html
* https://symfony.com/doc/current/mailer.html
* https://symfony.com/doc/current/cloud/services/emails.html

# Etape 21: Mise en cache
$response->setSharedMaxAge(3600);
FrameworkBundle\HttpCache\HttpCache (Reverse-proxy Http)
x-symfony-cache: GET /: miss, store (or GET /: fresh)
ESI: Edge Side Includes (Varnish Page fragment)
    {{ render(path('conference_header')) }}     => 2 request calls
    {{ render_esi(path('conference_header')) }} => 1 request and 1 in cache
config/packages/framework.yaml => esi: true

curl -s -I -X GET https://127.0.0.1:8000/
curl -I -X PURGE -u admin:admin `symfony var:export SYMFONY_PROJECT_DEFAULT_ROUTE_URL`/admin/http-cache/
symfony var:export SYMFONY_PROJECT_DEFAULT_ROUTE_URL

symfony composer req process
symfony console make:command app:step:info
symfony composer req cache
curl -X PURGE -H 'x-purge-token PURGE_NOW' `symfony env:urls --first`conference_header

* https://www.cloudflare.com/
* https://varnish-cache.org/docs/index.html
* https://www.w3.org/TR/esi-lang
* https://www.akamai.com/us/en/support/esi.jsp
* https://symfony.com/doc/current/http_cache/validation.html
* https://symfony.com/doc/current/cloud/cookbooks/cache.html

# Etape 22: Webpack interface
symfony composer req encore
yarn add node-sass sass-loader --dev
    webpack.config.js
yarn add bootstrap jquery popper.js bs-custom-file-input --dev
config/packages/twig.yaml
    twig: form_themes: ['bootstrap_4_layout.html.twig']
symfony run yarn encore dev
    or
symfony run -d yarn encore dev --watch

* https://webpack.js.org/concepts/
* https://symfony.com/doc/current/frontend.html
* https://symfonycasts.com/screencast/webpack-encore

# Etape 23: Redimensionner les images
config/packages/workflow.yaml
symfony composer req "imagine/imagine:^1.2"

# Etape 24: Cron

**Valeur sensible** (mots de passe, jetons API, etc.), stockage de chaîne secrète de Symfony ou un Vault
**Valeur dynamique** et pouvoir la modifier sans redéployer, utilisez une variable d’environnement
**Valeur différente** d’un environnement à l’autre, utilisez un paramètre de conteneur
**Pour le reste**, stockez la valeur dans le code, comme dans une constante de classe.

symfony console make:command app:comment:cleanup
symfony console app:comment:cleanup
symfony var:set MAILTO=ops@example.com (Cloud)
symfony cron comment_cleanup

* https://en.wikipedia.org/wiki/Cron
* https://github.com/symfonycorp/croncape
* https://symfony.com/doc/current/console.html
* https://github.com/andreia/symfony-cheat-sheets/blob/master/Symfony4/console_en_42.pdf

# Etape 25: Notifications
symfony composer req notifier
    Symfony\Component\Notifier\Notification\Notification
    config/packages/notifier.yaml
    admin_recipients:
        - { email: "%env(string:default:default_admin_email:ADMIN_EMAIL)%" }
symfony composer req slack-notifier
symfony console secrets:set SLACK_DSN
APP_ENV=prod symfony console secrets:set SLACK_DSN

* https://symfony.com/doc/current/controller.html#flash-messages

# Etape 26: API
symfony composer req api
Entity: /** @ApiResource(...) */
config/routes/api_platform.yaml
Cross-Origin Resource Sharing (CORS): interdit d’appeler l’API depuis un autre domaine
.env: CORS_ALLOW_ORIGIN
composer require webonyx/graphql-php
    puis accédez à /api/graphql

* https://symfonycasts.com/screencast/api-platform

# Etape 27: SPA
https://preactjs.com/
yarn init -y
yarn add @symfony/webpack-encore @babel/core @babel/preset-env babel-preset-preact \
    preact html-webpack-plugin bootstrap
symfony server:start -d --passthru=index.html
symfony open:local
yarn add preact-router
yarn encore dev
yarn add node-sass sass-loader
API_ENDPOINT=`symfony var:export SYMFONY_PROJECT_DEFAULT_ROUTE_URL --dir=..` yarn encore dev
symfony var:set \
    "CORS_ALLOW_ORIGIN=^`symfony env:urls --first | sed 's#/$##' | sed 's#https://#https://spa.#'`$"
symfony var:set API_ENDPOINT=`symfony env:urls --first`
yarn global add cordova
cordova run android

* https://preactjs.com/
* https://cordova.apache.org/

# Etape 28: Localisation
config/services.yaml => _locales_: 'en|fr'
    @Route("/{_locale<%_locales_%>}/", name="homepage")
symfony composer req twig/string-extra
symfony composer req translation
symfony console translation:update fr --force --domain=messages

* https://symfony.com/doc/current/translation/message_format.html
* https://symfony.com/doc/current/translation/templates.html#translation-filters

# Etape 29: Profiling
curl https://installer.blackfire.io/ | bash
php.ini => extension=blackfire.so
blackfire config --client-id=xxx --client-token=xxx
blackfire curl `symfony var:export SYMFONY_PROJECT_DEFAULT_ROUTE_URL`api
curl -OLsS https://get.blackfire.io/blackfire-player.phar && chmod +x blackfire-player.phar

* https://blackfire.io/book
* https://symfonycasts.com/screencast/blackfire

# Etape 30: Symfony heart
blackfire --debug curl `symfony env:urls --first`en/
blackfire curl `symfony var:export SYMFONY_PROJECT_DEFAULT_ROUTE_URL`en/