
# ====================
# Docker
up:
	docker-compose up

down:
	docker-compose down

ps:
	docker-compose ps

ssh:
	docker exec -it --user=dev authenticationbundle-docker-php bash

# ====================
# Qualimetry rules

.PHONY: qa qualimetry
qa: qualimetry
qualimetry: checkstyle lint.php composer.validate phpstan metrics

.PHONY: cs checkstyle
cs: checkstyle
checkstyle:
	vendor/bin/phpcs --extensions=php -n --standard=PSR12 --report=full src tests

.PHONY: lint.php
lint.php:
	find src -type f -name "*.php" -exec php -l {} \;

.PHONY: composer.validate
composer.validate:
	composer validate composer.json

.PHONY: cb code-beautifier
cb: code-beautifier
code-beautifier:
	vendor/bin/phpcbf --extensions=php --standard=PSR12 src tests

.PHONY: cpd
cpd:
	vendor/bin/phpcpd --fuzzy src

.PHONY: metrics
metrics:
	vendor/bin/phpmetrics --report-html=build/phpmetrics.html src

.PHONY: phpstan
phpstan:
	vendor/bin/phpstan analyse src --level=6 -c phpstan.neon

#================================
#           TEST

.PHONY: phpunit
phpunit:
	vendor/bin/phpunit tests
