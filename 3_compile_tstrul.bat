@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php tstrul.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _tstrul.lst _tstrul.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _tstrul.lst ./release/tstrul.bin bbk 2000
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/tstrul.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _tstrul.mac
del _tstrul.lst

start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\tstrul.bin

echo.