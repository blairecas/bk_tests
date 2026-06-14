<?php

    $img = imagecreatefrompng('./mario_sprites.png');
    $width = imagesx($img);
    $height = imagesy($img);
    echo "Image: $width x $height\n";
    $tiles_dx = intval($width / 16);
    $tiles_dy = intval($height / 16);
    echo "Tiles: $tiles_dx x $tiles_dy\n";
    
    // sprites array
    $tilesArray = Array();
    
    for ($tiley=0; $tiley<$tiles_dy; $tiley++)
    {
        for ($tilex=0; $tilex<$tiles_dx; $tilex++)
        {
	    $tile = Array();
	    for ($y=0; $y<16; $y++)
            {
		for ($i=0; $i<2; $i++)
		{
                    $data = 0;
		    $mask = 0;
		    for ($x=0; $x<8; $x++)
		    {
		        $data = ($data >> 2) & 0xFFFF;
		        $mask = ($mask >> 2) & 0xFFFF;
		        $py = $tiley*16 + $y;
		        $px = $tilex*16 + $i*8 + $x;
		        $rgb_index = imagecolorat($img, $px, $py);
		        $rgba = imagecolorsforindex($img, $rgb_index);
			$a = $rgba['alpha'];
		        $r = $rgba['red'];
		        $g = $rgba['green'];
		        $b = $rgba['blue'];
			if ($a > 64) {
			    $mask = $mask | 0b0000000000000000;
			} else {
			    $mask = $mask | 0b1100000000000000;
			    if ($r > 127) $data = $data | 0b1100000000000000;
			    if ($g > 127) $data = $data | 0b1000000000000000;
			    if ($b > 127) $data = $data | 0b0100000000000000;
			}
		    }
		    array_push($tile, $mask);
		    array_push($tile, $data);
		}
            }
            $found = array_push($tilesArray, $tile) - 1;
        }
    }
    
    echo "Tiles count: ".count($tilesArray)."\n";
    
    ////////////////////////////////////////////////////////////////////////////
    
    echo "Writing data ...\n";
    $f = fopen("mario_sprites.mac", "w");

    for ($t=0; $t<count($tilesArray); $t++)
    {
	$tile = $tilesArray[$t];
	fputs($f, "SpriteData".str_pad("".$t, 3, "0", STR_PAD_LEFT).":\n");
	fputs($f, "\t.byte\t");
	$i=0; 
	while ($i<64)
	{
	    $mask = $tile[$i++];
	    $data = $tile[$i++];
	    $mask1 = $mask & 0xFF;
	    $mask2 = ($mask >> 8) & 0xFF;
	    $data1 = $data & 0xFF;
	    $data2 = ($data >> 8) & 0xFF;
	    fputs($f, decoct($mask1) . "," . decoct($data1) . ", ");
	    fputs($f, decoct($mask2) . "," . decoct($data2));
	    if ($i<63) fputs($f, ", "); else fputs($f, "\n");
	}
    }
    fputs($f, "\n\n");

    fclose($f);
    
?>