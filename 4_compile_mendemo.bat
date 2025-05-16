@echo off

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ./music/conv_music3.php
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/preprocess.php mendemo1.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11 -ysl 32 -yus -l _mendemo1.lst _mendemo1.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _mendemo1.lst ./release/mendemo1.bin bkpack
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/mendemo1.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

php -f ../scripts/preprocess.php mendemo2.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _mendemo2.lst _mendemo2.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _mendemo2.lst ./release/mendemo2.bin bkpack
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/bin2wav.php ./release/mendemo2.bin
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _mendemo1.mac
del _mendemo1.lst
del _mendemo2.mac
del _mendemo2.lst

..\scripts\bkdecmd d ./release/andos.img mendemo1 >NUL
..\scripts\bkdecmd a ./release/andos.img ./release/mendemo1.bin >NUL

..\scripts\bkdecmd d ./release/andos.img mendemo2 >NUL
..\scripts\bkdecmd a ./release/andos.img ./release/mendemo2.bin >NUL

rem start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\mendemo2.bin

echo.