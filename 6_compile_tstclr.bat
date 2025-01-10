@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php tstclr.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _tstclr.lst _tstclr.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _tstclr.lst ./release/tstclr.bin bbk 2000
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/tstclr.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _tstclr.mac
del _tstclr.lst

start ..\..\bkemu\BK_x64.exe /C BK-0010-01 /B .\release\tstclr.bin

echo.