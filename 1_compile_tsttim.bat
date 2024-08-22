@echo off

echo.
echo ===========================================================================
echo Compiling TSTTIM
echo ===========================================================================
php -f ..\scripts\preprocess.php tsttim.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _tsttim.lst _tsttim.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ..\scripts\lst2bin.php _tsttim.lst ./release/tsttim.bin bbk 2000

echo.
echo ===========================================================================
echo Running TSTTIM
echo ===========================================================================
start ..\..\bkemu\BK_x64.exe /C BK-0011M /B .\release\tsttim.bin

echo.