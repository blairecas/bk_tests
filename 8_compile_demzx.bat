@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php demzx.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _demzx.lst _demzx.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _demzx.lst ./release/demzx.bin bbk 2000
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/demzx.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _demzx.mac
rem del _demzx.lst

..\scripts\bkdecmd d ./release/andos.img demzx >NUL
..\scripts\bkdecmd a ./release/andos.img ./release/demzx.bin >NUL

start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\demzx.bin

echo.