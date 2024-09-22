@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php bunive.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _bunive.lst _bunive.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _bunive.lst ./release/bunive.bin bbk 2000
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/bunive.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _bunive.mac
del _bunive.lst

start ..\..\bkemu\BK_x64.exe /C BK-0010-01 /B .\release\bunive.bin

echo.