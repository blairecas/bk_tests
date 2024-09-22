@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php warp.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _warp.lst _warp.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _warp.lst ./release/warp.bin bbk 2000
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/warp.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _warp.mac
del _warp.lst

start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\warp.bin

echo.