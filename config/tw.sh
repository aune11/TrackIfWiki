#!/bin/bash
cd public
php -S 0.0.0.0:$1 -c /web/config/php.ini
echo "TW is now serving on http://127.0.0.1:$1"
