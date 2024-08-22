@echo off

echo.
echo ===========================================================================
echo Compiling KOS
echo ===========================================================================
php -f ..\scripts\preprocess.php kos.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _kos.lst _kos.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ..\scripts\lst2bin.php _kos.lst ./release/kos.bin bbk 2000

echo.
echo ===========================================================================
echo Running KOS
echo ===========================================================================
start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\kos.bin

echo.