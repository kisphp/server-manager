.PHONY: i t

i:
	composer install -n -o -a

t:
	vendor/bin/phpunit --coverage-clover=coverage.xml
	mkdir _fix
	cd _fix
	echo '{}' > composer.json
	composer require friendsofphp/php-cs-fixer
	cd ../
	_fix/vendor/bin/php-cs-fixer fix -v --dry-run
