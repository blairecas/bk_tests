@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php tstmen.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _tstmen.lst _tstmen.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _tstmen.lst ./release/tstmen.bin bbk 2000
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/tstmen.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _tstmen.mac
del _tstmen.lst

start ..\..\bkemu\BK_x64.exe /C BK-0010-01 /B .\release\tstmen.bin

echo.