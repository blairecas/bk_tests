@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php pt3test.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _pt3test.lst _pt3test.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _pt3test.lst ./release/pt3test.bin bkpack
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/pt3test.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _pt3test.mac
del _pt3test.lst

..\scripts\bkdecmd d ./release/andos.img pt3test >NUL
..\scripts\bkdecmd a ./release/andos.img ./release/pt3test.bin >NUL

start ..\..\bkemu\BK_x64.exe /C BK-0010M /B .\release\pt3test.bin

echo.