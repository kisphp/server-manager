#!/usr/bin/env bash

if [[ ! -d _fix ]];then
    mkdir _fix
    echo '{}' > _fix/composer.json
    cd _fix
    composer require friendsofphp/php-cs-fixer
    cd ..
fi
_fix/vendor/bin/php-cs-fixer fix -v
#rm -rf _fix
