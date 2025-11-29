@echo off

set NAME=colors

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php %NAME%.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11 -ysl 32 -yus -l _%NAME%.lst _%NAME%.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _%NAME%.lst ./release/%NAME%.bin bkpack
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/%NAME%.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _%NAME%.mac
del _%NAME%.lst

..\scripts\bkdecmd d ./release/andos_test.dsk %NAME% >NUL
..\scripts\bkdecmd a ./release/andos_test.dsk ./release/%NAME%.bin >NUL

start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\%NAME%.bin
echo.