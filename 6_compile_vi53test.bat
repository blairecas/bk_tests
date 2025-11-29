@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php vi53test.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _vi53test.lst _vi53test.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _vi53test.lst ./release/vi53test.bin bkpack
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/vi53test.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _vi53test.mac
del _vi53test.lst

..\scripts\bkdecmd d ./release/andos_test.dsk vi53test >NUL
..\scripts\bkdecmd a ./release/andos_test.dsk ./release/vi53test.bin >NUL

rem start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\vi53test.bin

echo.