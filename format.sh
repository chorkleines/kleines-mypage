#!/bin/bash

ESC=$(printf '\033')

echo "${ESC}[36;1m╭──────────────────────╮${ESC}[m"
echo "${ESC}[36;1m│ Running PHP CS Fixer │${ESC}[m"
echo "${ESC}[36;1m╰──────────────────────╯${ESC}[m"

./api/tools/php-cs-fixer/vendor/bin/php-cs-fixer fix -v

echo ""
echo "${ESC}[36;1m╭─────────────────────────╮${ESC}[m"
echo "${ESC}[36;1m│ Running Blade Formatter │${ESC}[m"
echo "${ESC}[36;1m╰─────────────────────────╯${ESC}[m"
./api/node_modules/blade-formatter/bin/blade-formatter --write ./api/resources/\*\*/\*.blade.php

echo ""
echo "${ESC}[36;1m╭──────────────────╮${ESC}[m"
echo "${ESC}[36;1m│ Running Prettier │${ESC}[m"
echo "${ESC}[36;1m╰──────────────────╯${ESC}[m"
./client/node_modules/prettier/bin/prettier.cjs --write client/**/*.vue
./client/node_modules/prettier/bin/prettier.cjs --write client/**/*.ts
