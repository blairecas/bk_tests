@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php tsttim.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _tsttim.lst _tsttim.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _tsttim.lst ./release/tsttim.bin bbk 2000
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/tsttim.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _tsttim.lst
del _tsttim.mac

start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\tsttim.bin

echo.