#!/bin/bash
heroku run php bin/console doctrine:schema:create -a joel-beya-blog
heroku run php bin/console doctrine:schema:update --force -a joel-beya-blog
