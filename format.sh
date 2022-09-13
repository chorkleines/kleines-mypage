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
./node_modules/blade-formatter/bin/blade-formatter --write ./resources/**/*.blade.php
