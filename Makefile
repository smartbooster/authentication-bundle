
# ====================
# Qualimetry rules

qa: qualimetry
qualimetry: checkstyle lint.php composer.validate metrics

cs: checkstyle
checkstyle:
	vendor/bin/phpcs --extensions=php -n --standard=PSR12 --report=full src tests

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
	vendor/bin/phpstan analyse src --level=6 -c phpstan.neon
