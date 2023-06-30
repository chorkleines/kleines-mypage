#!/bin/bash

ESC=$(printf '\033')

echo "${ESC}[36;1m╭──────────────────────╮${ESC}[m"
echo "${ESC}[36;1m│ Running PHP CS Fixer │${ESC}[m"
echo "${ESC}[36;1m╰──────────────────────╯${ESC}[m"

./tools/php-cs-fixer/vendor/bin/php-cs-fixer fix -v

echo ""
echo "${ESC}[36;1m╭─────────────────────────╮${ESC}[m"
echo "${ESC}[36;1m│ Running Blade Formatter │${ESC}[m"
echo "${ESC}[36;1m╰─────────────────────────╯${ESC}[m"
./node_modules/blade-formatter/bin/blade-formatter --write ./resources/\*\*/\*.blade.php

echo ""
echo "${ESC}[36;1m╭──────────────────╮${ESC}[m"
echo "${ESC}[36;1m│ Running Prettier │${ESC}[m"
echo "${ESC}[36;1m╰──────────────────╯${ESC}[m"
./node_modules/prettier/bin-prettier.js --write resources/css/*.css
./node_modules/prettier/bin-prettier.js --write resources/js/*.ts
./node_modules/prettier/bin-prettier.js --write resources/js/*.vue resources/js/**/*.vue
./node_modules/prettier/bin-prettier.js --write resources/sass/*.scss
