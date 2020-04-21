#!/bin/bash

symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load