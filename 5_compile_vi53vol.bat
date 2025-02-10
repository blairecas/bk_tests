@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php vi53vol.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _vi53vol.lst _vi53vol.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _vi53vol.lst ./release/vi53vol.bin bkpack
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/vi53vol.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _vi53vol.mac
del _vi53vol.lst

..\scripts\bkdecmd d ./release/andos.img vi53vol >NUL
..\scripts\bkdecmd a ./release/andos.img ./release/vi53vol.bin >NUL

start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\vi53vol.bin

echo.