<?php
    $f = fopen('music3.asm', 'r');
    $g = fopen('music3.mac', 'w');
    fputs($g, "\t.radix 10\n");
    while (!feof($f)) {
        $s = trim(fgets($f));
        $i = strpos($s, ' ');
        if ($i === false) { fputs($g, $s . "\n"); continue; }
        $s1 = substr($s, 0, $i);
        $s2 = substr($s, $i+1); 
        $arr = explode(';', $s2); 
        $s2 = $arr[0];
        $s3 = ''; if (isset($arr[1])) $s3 = '; ' . $arr[1];
        // if (is_numeric($s2)) {
        //     $i2 = intval($s2);
        //     if (($i2 != 4) && (($i2 & 1) == 0)) {
        //
        //    }
        // }        
        if ($s2 == '255*256') $s2 = '0';
        fputs($g, "\t.word\t$s2\t$s3\n");
    }
    fputs($g, "\t.radix 8\n");
    fclose($f);
    fclose($g);
?>