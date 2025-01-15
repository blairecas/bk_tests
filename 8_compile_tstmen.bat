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

php -f ../scripts/preprocess.php tstmen2.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _tstmen2.lst _tstmen2.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _tstmen2.lst ./release/tstmen2.bin bbk 2000
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/tstmen2.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _tstmen.mac
del _tstmen.lst
del _tstmen2.mac
del _tstmen2.lst

start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\tstmen2.bin

echo.