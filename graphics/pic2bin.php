<?php

function GetArray ($img, $tdx, $tdy)
{
    $arr = Array();
    $arr['last'] = 0;
    $arr['tiles'] = Array();
    $arr['masks'] = Array();
    $tiles_dx = imagesx($img) / $tdx;
    $tiles_dy = imagesy($img) / $tdy;
    for ($i=0; $i<$tiles_dy; $i++) 
    {
        for ($j=0; $j<$tiles_dx; $j++) 
        {
            $tile = Array();
            $mask = Array();
            for ($y=$i*$tdy; $y<$i*$tdy+$tdy; $y++) 
            {
                for ($x4=$j*$tdx; $x4<$j*$tdx+$tdx; $x4+=4) 
                {
                    $res = 0;
                    $mas = 0;
                    for ($x=$x4; $x<$x4+4; $x++) 
                    {
                        $res = ($res >> 2) & 0xFF;
                        $mas = ($mas >> 2) & 0xFF;
                        $rgb_index = imagecolorat($img, $x, $y);
                        $rgba = imagecolorsforindex($img, $rgb_index);
                        $r=$rgba['red']; $g=$rgba['green']; $b=$rgba['blue']; $a=$rgba['alpha'];
                        if ($a > 100) { $b=0; $g=0; $r=0; } else $mas = $mas | 0b11000000; 
                        if ($b > 127) $res = $res | 0b01000000;
                        if ($g > 127) $res = $res | 0b10000000;
                        if ($r > 127) $res = $res | 0b11000000;
                    }
                    array_push($tile, $res);
                    array_push($mask, $mas);
                    if ($res != 0x00) $arr['last'] = count($arr['tiles']);
                }
            }
            array_push($arr['tiles'], $tile);
            array_push($arr['masks'], $mask);
        }
    }
    return $arr;
}


function ImgWriteData ($fn)
{
    global $f;
    $img = imagecreatefrompng(pathinfo(__FILE__, PATHINFO_DIRNAME)."/".$fn);
    $tdx = imagesx($img);
    $tdy = imagesy($img);
    $arr = GetArray($img, $tdx, $tdy);
    for ($t=0; $t<=$arr['last']; $t++)
    {
        $tile = $arr['tiles'][$t];
    	for ($i=0, $l=count($tile); $i<$l; $i++)
        {
            fwrite($f, chr($tile[$i]), 1);
        }
    }
}

    $f = fopen(pathinfo(__FILE__, PATHINFO_DIRNAME)."/PIC006.BIN", "w"); 
    fwrite($f, chr(0x00), 1);
    fwrite($f, chr(0x40), 1);
    fwrite($f, chr(0x00), 1);
    fwrite($f, chr(0x40), 1);
    ImgWriteData("bk001.png");
