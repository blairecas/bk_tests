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
    if ($d < 0) return 0x10000 + intval($d / $da);
    return intval($d / $da);
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


    // ytable, upper 9 bits -> vaddr 40000 based
    $n = 0;
    $scrwid = 0100;
    $vaddr = 040000;
    $vend = 0100000 - $scrwid;
    fputs($f, "YTable:\n");
    for ($i=0; $i<256; $i++)
    {
        $w = ((0x10000 + ($i<<7)) & 0xFF80);
        $d = WordToDeg($w);
        if ($d >= +2.0) $w = $vend; else $w = $vaddr + intval((($d+2.0)/4.0)*256)*$scrwid;
        PutWord($w);
    }
    for ($i=-256; $i<0; $i++)
    {
        $w = ((0x10000 + ($i<<7)) & 0xFF80);
        $d = WordToDeg($w);
        if ($d < -2.0) $w = $vaddr; else $w = $vaddr + intval((($d+2.0)/4.0)*256)*$scrwid;
        PutWord($w);
    }
    fputs($f, "\n\n");


    // xtable, upper 9 bits -> [byte in line, pixel mask]
    $n = 0;
    fputs($f, "XTable:\n");
    for ($i=0; $i<256; $i++)
    {
        $w = ((0x10000 + ($i<<7)) & 0xFF80);
        $d = WordToDeg($w);
        $px = intval((($d+2.0)/4.0)*256);
        if ($d >= +2.0) $w = $scrwid-1; else $w = $px>>2;
        PutWord($w);
    }
    for ($i=-256; $i<0; $i++)
    {
        $w = ((0x10000 + ($i<<7)) & 0xFF80);
        $d = WordToDeg($w);
        $px = intval((($d+2.0)/4.0)*256);
        if ($d <= -2.0) $w = 0; else $w = $px>>2;
        PutWord($w);
    }
    fputs($f, "\n\n");
    

    // pixel bit mask 9 bits
    $n = 0;
    fputs($f, "BMask:\n");
    for ($i=0; $i<256; $i++)
    {
        $w = ((0x10000 + ($i<<7)) & 0xFF80);
        $d = WordToDeg($w);
        $px = intval((($d+2.0)/4.0)*256);
        // $bmask = ((0b1111110011111111 << (($px & 3)*2)) >> 8) & 0xFF;
        $bmask = 0b01 << (($px & 3)*2);
        PutWord($bmask);
    }
    for ($i=-256; $i<0; $i++)
    {
        $w = ((0x10000 + ($i<<7)) & 0xFF80);
        $d = WordToDeg($w);
        $px = intval((($d+2.0)/4.0)*256);
        // $bmask = ((0b1111110011111111 << (($px & 3)*2)) >> 8) & 0xFF;
        $bmask = 0b01 << (($px & 3)*2);
        PutWord($bmask);
    }
    fputs($f, "\n\n");


    fclose($f);
