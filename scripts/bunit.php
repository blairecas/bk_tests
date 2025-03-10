<?php

    $da = M_PI / 0x8000;
    echo "(word) 1 -> ".decoct(DegToWord(1.0))."\n";
    echo "(word) PI/2 -> ".decoct(DegToWord(M_PI/2.0))."\n";
    echo "(word) R = 2*PI/256 -> ".decoct(DegToWord(2.0*M_PI/256.0))."\n";
    echo "(word) 0.01 -> ".decoct(DegToWord(0.01))."\n";

// 0x8000 is negative
function WordToDeg ( $w )
{
    global $da;
    $w = $w & 0xFFFF;
    if ($w >= 0x8000) return -(0x10000 - $w) * $da;
    return $w * $da;
}

function DegToWord ( $d )
{
    global $da;
    if ($d < 0) return (0x10000 + intval($d / $da)) & 0xFFFF;
    return intval($d / $da) & 0xFFFF;
}

function PutWord ( $w )
{
    global $f, $n;
    if ($n == 0) fputs($f, "\t.word\t");
    fputs($f, decoct($w)); 
    if ($n < 7) fputs($f, ", "); else fputs($f, "\n"); 
    $n = (++$n) & 7;
}

function PutByte ( $b )
{
    global $f, $n;
    if ($n == 0) fputs($f, "\t.byte\t");
    fputs($f, decoct($b)); 
    if ($n < 7) fputs($f, ", "); else fputs($f, "\n"); 
    $n = (++$n) & 7;
}

    ///////////////////////////////////////////////////////////////////////////

    $f = fopen(pathinfo(__FILE__,PATHINFO_DIRNAME)."/bunit.txt", "w");

    // use 10 bits for cosine index (signed)
    $n = 0;
    for ($i=-512; $i<512; $i++)
    {
        $w = ((0x10000 + ($i<<6)) & 0xFFC0);
        $w = DegToWord(cos(WordToDeg($w)));
        PutWord($w);
        if ($i == -1) fputs($f, "CosTable:\n");
    }
    fputs($f, "\n\n");


    $scrwid = 0100;
    $vaddr = 040000;
    $vend = 0100000 - $scrwid;

    // ytable, upper 9 bits -> vaddr 40000 based
    $n = 0; fputs($f, "YTable:\n");
    for ($i=0; $i<512; $i++)
    {
	$j=$i; if ($i>=256) $j=$i-512;
        $w = ((0x10000 + ($j<<7)) & 0xFF80);
        $d = WordToDeg($w);
        if ($d >= +2.0) $w = $vend; 
	else if ($d <= -2.0) $w = $vaddr;
	else $w = $vaddr + intval((($d+2.0)/4.0)*256.0)*$scrwid;
        PutWord($w);
    }
    fputs($f, "\n\n");


    // xtable, upper 9 bits -> [byte in line, pixel mask]
    $n = 0; fputs($f, "XTable:\n");
    for ($i=0; $i<512; $i++)
    {
	$j=$i; if ($i>=256) $j=$i-512;
        $w = ((0x10000 + ($j<<7)) & 0xFF80);
        $d = WordToDeg($w); $px = intval((($d+2.0)/4.0)*256.0);
        if ($d >= +2.0) $w = $scrwid-1; 
        else if ($d <= -2.0) $w = 0; 
        else $w = $px>>2;
        PutWord($w);
    }
    fputs($f, "\n\n");
    

    // pixel bit mask 9 bits
    $n = 0; fputs($f, "BMask:\n");
    for ($i=0; $i<512; $i++)
    {
	$j=$i; if ($i>=256) $j=$i-512;
        $w = ((0x10000 + ($j<<7)) & 0xFF80);
        $d = WordToDeg($w); $px = intval((($d+2.0)/4.0)*256.0);
        $bmask = 0b11 << (($px & 3)*2);
        PutWord($bmask);
    }
    fputs($f, "\n\n");


    // picture
    $img = imagecreatefrompng(pathinfo(__FILE__, PATHINFO_DIRNAME)."/colors44.png");
    $arr = Array();
    for ($y=0; $y<imagesy($img); $y++) {
        for ($x4=0; $x4<imagesx($img); $x4+=4) {
            $res = 0; 
            for ($x=$x4; $x<$x4+4; $x++) {
                $res = ($res >> 2) & 0xFF;
                $rgb_index = imagecolorat($img, $x, $y);
                $rgba = imagecolorsforindex($img, $rgb_index);
                $r=$rgba['red']; $g=$rgba['green']; $b=$rgba['blue'];
		if ($b > 127) $res = $res | 0b01000000;
		if ($g > 127) $res = $res | 0b10000000;
		if ($r > 127) $res = $res | 0b11000000;
            }
            array_push($arr, $res);
        }
    }
    $n = 0; fputs($f, "Colr44:\n");
    for ($i=0; $i<count($arr); $i++) {	PutByte($arr[$i]); }
    fputs($f, "\n\n");


    ////////////////////////////////////////////////////////////////////
    fclose($f);
