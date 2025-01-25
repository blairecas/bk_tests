@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php kos.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _kos.lst _kos.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _kos.lst ./release/kos.bin bbk 1000
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/kos.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _kos.mac
del _kos.lst

..\scripts\bkdecmd d ./release/andos.img kos >NUL
..\scripts\bkdecmd a ./release/andos.img ./release/kos.bin >NUL

start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\kos.bin

echo.