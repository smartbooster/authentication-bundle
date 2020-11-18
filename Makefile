# ====================
# Development
ssh:
	docker run --rm -it --user=dev -v $(PWD):/var/www smartbooster/php-fpm:builder bash

# ====================
# Qualimetry rules

qa: qualimetry
qualimetry: checkstyle lint.php composer.validate phpstan metrics

# https://cs.symfony.com/
cs: checkstyle
checkstyle:
	php-cs-fixer fix --dry-run --format checkstyle

lint.php:
	find src -type f -name "*.php" -exec php -l {} \;

composer.validate:
	composer validate composer.json

cb: code-beautifier
code-beautifier:
	vendor/bin/phpcbf --extensions=php --standard=PSR12 src tests

cpd:
	vendor/bin/phpcpd --fuzzy src

metrics:
	vendor/bin/phpmetrics --report-html=build/phpmetrics.html src

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon

#================================
#           TEST

phpunit:
	vendor/bin/phpunit tests
