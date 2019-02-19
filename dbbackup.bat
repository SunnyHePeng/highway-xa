rem ******MySQL backup start********
@echo off
forfiles /p "d:\dbbak" /m backup_*.sql -d -30 /c "cmd /c del /f @path"
set "Ymd=%date:~0,4%%date:~5,2%%date:~8,2%0%time:~1,1%%time:~3,2%%time:~6,2%"
"D:\data\mysql5.7.15\bin\mysqldump" --opt --single-transaction=TRUE --user=root --password=asd234ddjA --host=127.0.0.1 --protocol=tcp --port=3306 --default-character-set=utf8 --single-transaction=TRUE --routines --events "road" > "D:\dbbak\backup_%Ymd%.sql"
@echo on
rem ******MySQL backup end********
