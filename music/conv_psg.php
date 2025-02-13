<?php

    $freq = Array(0, 0, 0);
    $vol = Array(0, 0, 0);
    $p_freq = Array(-1, -1, -1);
    $p_vol = Array(-1, -1, -1);
    $waitfreq = Array(0x8000, 0x8000, 0x8000);
    $waitvol = Array(0x80, 0x80, 0x80);
    $mixer = 0;
    $nint = 0;
    $p_nint = 0;
    $out_freq = Array(Array(), Array(), Array());
    $out_vol = Array(Array(), Array(), Array());


function replacePrevFreq ($i)
{
    global  $freq, $p_freq, $waitfreq, $out_freq, $nint;
    if ($freq[$i] >= 0x8000) { echo "\nERROR: freq value >= 0x8000!\n"; exit(1); }
    if ($freq[$i] != $p_freq[$i]) {
        if ($waitfreq[$i] != 0x8000) {
            if ($waitfreq[$i] > 0xFFFF) { echo "\nERROR: freq wait number > 0xFFFF!\n"; exit(1); }
            array_push($out_freq[$i], $waitfreq[$i]);
        }
        array_push($out_freq[$i], $freq[$i]);
        $waitfreq[$i] = 0x7FFF+$nint;
        $p_freq[$i] = $freq[$i];
    } else {
        $waitfreq[$i] += $nint;
    }
}


function replacePrevVol ($i)
{
    global  $vol, $p_vol, $waitvol, $out_vol, $nint;
    if ($vol[$i] >= 0x80) { echo "\nERROR: vol value >= 0x80!\n"; exit(1); }
    if ($vol[$i] != $p_vol[$i]) {
        if ($waitvol[$i] != 0x80) {
            while ($waitvol[$i] > 0xFF) { array_push($out_vol[$i], 0xFF); $waitvol[$i] -= 0x7F; }
            array_push($out_vol[$i], $waitvol[$i]);
        }
        array_push($out_vol[$i], $vol[$i]);
        $waitvol[$i] = 0x7F+$nint;
        $p_vol[$i] = $vol[$i];
    } else {
        $waitvol[$i] += $nint;
    }
}


    $f = fopen("test3.psg", "r");
    fseek($f, 0x10, SEEK_SET);
    while (!feof($f))
    {
        $b = ord(fread($f, 1)); // echo $b."+ ";
        // 1/50s wait
        if ($b == 0xFF || $b == 0xFE) {
            $nint = 1; if ($b == 0xFE) $nint = ord(fread($f, 1));
            // check mixer and turn off channel if we have noise
            if (($mixer & 0b001001) != (0b001000)) $vol[0] = 0;
            if (($mixer & 0b010010) != (0b010000)) $vol[1] = 0;
            if (($mixer & 0b100100) != (0b100000)) $vol[2] = 0;
            // replace prev
            replacePrevFreq(0);
            replacePrevFreq(1);
            replacePrevFreq(2);
            replacePrevVol(0);
            replacePrevVol(1);
            replacePrevVol(2);
            continue;
        } 
        else if ($b == 0xFD) { 
            echo "\n0xFD - exit...\n"; 
            exit(1); 
        } 
        else if ($b > 15) {
            echo "\nERROR: reg number = $b, at 0x".dechex(ftell($f))."\n";
            exit(1);
        }
        // usual reg
        $r = $b;
        $v = ord(fread($f, 1)); // echo $v."- ";
        switch ($r) {
            case  0: $freq[0] = ($freq[0] & 0xFF00) | $v; break;
            case  1: $freq[0] = ($freq[0] & 0x00FF) | ($v << 8); break;
            case  2: $freq[1] = ($freq[1] & 0xFF00) | $v; break;
            case  3: $freq[1] = ($freq[1] & 0x00FF) | ($v << 8); break;
            case  4: $freq[2] = ($freq[2] & 0xFF00) | $v; break;
            case  5: $freq[2] = ($freq[2] & 0x00FF) | ($v << 8); break;
            case  7: $mixer = $v; break;
            case  8: $vol[0] = $v & 0x0F; break;
            case  9: $vol[1] = $v & 0x0F; break;
            case 10: $vol[2] = $v & 0x0F; break;
        }
    }
    fclose($f);

    // output
    $wcount = count($out_freq[0]) + count($out_freq[1]) + count($out_freq[2]);
    $bcount = count($out_vol[0]) + count($out_vol[1]) + count($out_vol[2]);
    $tcount = $wcount + $bcount;
    echo "Entries $tcount, Words $wcount, Bytes $bcount, Total bytes ".($wcount*2+$bcount)."\n";