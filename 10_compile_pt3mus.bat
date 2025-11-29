@echo off

set NAME=pt3mus

echo.
echo ===========================================================================
echo Compiling
echo ===========================================================================
php -f ../scripts/preprocess.php %NAME%.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11.exe -ysl 32 -yus -l _%NAME%.lst _%NAME%.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f ../scripts/lst2bin.php _%NAME%.lst ./release/%NAME%.bin bkpack
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _%NAME%.mac
rem del _%NAME%.lst

..\scripts\bkdecmd d ./release/andos_pt3mus.img %NAME% >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./release/%NAME%.bin >NUL

..\scripts\bkdecmd d ./release/andos_pt3mus.img PIC001 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./graphics/PIC001.BIN >NUL
..\scripts\bkdecmd d ./release/andos_pt3mus.img MUS001.PT3 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./music/MUS001.PT3 >NUL

..\scripts\bkdecmd d ./release/andos_pt3mus.img PIC002 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./graphics/PIC002.BIN >NUL
..\scripts\bkdecmd d ./release/andos_pt3mus.img MUS002.PT3 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./music/MUS002.PT3 >NUL

..\scripts\bkdecmd d ./release/andos_pt3mus.img PIC003 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./graphics/PIC003.BIN >NUL
..\scripts\bkdecmd d ./release/andos_pt3mus.img MUS003.PT3 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./music/MUS003.PT3 >NUL

..\scripts\bkdecmd d ./release/andos_pt3mus.img PIC004 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./graphics/PIC004.BIN >NUL
..\scripts\bkdecmd d ./release/andos_pt3mus.img MUS004.PT3 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./music/MUS004.PT3 >NUL

..\scripts\bkdecmd d ./release/andos_pt3mus.img PIC005 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./graphics/PIC005.BIN >NUL
..\scripts\bkdecmd d ./release/andos_pt3mus.img MUS005.PT3 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./music/MUS005.PT3 >NUL

..\scripts\bkdecmd d ./release/andos_pt3mus.img PIC006 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./graphics/PIC006.BIN >NUL
..\scripts\bkdecmd d ./release/andos_pt3mus.img MUS006.PT3 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./music/MUS006.PT3 >NUL

..\scripts\bkdecmd d ./release/andos_pt3mus.img PIC007 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./graphics/PIC007.BIN >NUL
..\scripts\bkdecmd d ./release/andos_pt3mus.img MUS007.PT3 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./music/MUS007.PT3 >NUL

..\scripts\bkdecmd d ./release/andos_pt3mus.img PIC008 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./graphics/PIC008.BIN >NUL
..\scripts\bkdecmd d ./release/andos_pt3mus.img MUS008.PT3 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./music/MUS008.PT3 >NUL

..\scripts\bkdecmd d ./release/andos_pt3mus.img PIC009 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./graphics/PIC009.BIN >NUL
..\scripts\bkdecmd d ./release/andos_pt3mus.img MUS009.PT3 >NUL
..\scripts\bkdecmd a ./release/andos_pt3mus.img ./music/MUS009.PT3 >NUL

echo.