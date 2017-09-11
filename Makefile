.PHONY: i t c

i:
	composer install -n -o -a

t:
	vendor/bin/phpunit --coverage-clover=coverage.xml

c:
	./fix.sh
