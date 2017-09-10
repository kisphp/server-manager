.PHONY: i t c

i:
	composer install -n -o -a

t:
	vendor/bin/phpunit --coverage-clover=coverage.xml

c:
	mkdir _fix
	echo '{}' > _fix/composer.json
	cd _fix && composer require friendsofphp/php-cs-fixer
	_fix/vendor/bin/php-cs-fixer fix -v
	rm -rf _fix
