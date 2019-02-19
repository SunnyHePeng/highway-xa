@ECHO OFF
cd /d D:/data/www/highway-xa-qs
D:/data/php5.6/php.exe artisan schedule:run 1>>NUL 2>&1
