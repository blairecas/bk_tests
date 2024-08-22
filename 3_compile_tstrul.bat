@echo off

echo.
echo ===========================================================================
echo Compiling TSTRUL
echo ===========================================================================
php -f ..\scripts\preprocess.php tstrul.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _tstrul.lst _tstrul.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ..\scripts\lst2bin.php _tstrul.lst ./release/tstrul.bin bbk 2000

start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\tstrul.bin

echo.