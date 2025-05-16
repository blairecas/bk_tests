@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php colors.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11 -ysl 32 -yus -l _colors.lst _colors.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _colors.lst ./release/colors.bin bkpack
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/colors.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _colors.mac
del _colors.lst

..\scripts\bkdecmd d ./release/andos.img colors >NUL
..\scripts\bkdecmd a ./release/andos.img ./release/colors.bin >NUL

rem start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\colors.bin

echo.