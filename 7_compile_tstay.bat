@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php tstay.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _tstay.lst _tstay.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _tstay.lst ./release/tstay.bin bbk 2000
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/tstay.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _tstay.mac
del _tstay.lst

start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\tstay.bin

echo.