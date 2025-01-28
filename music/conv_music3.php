<?php

// notes freq in Hz
$notes = Array(
    // index 0 - silence
    1000000,
	// C0
	16.35, 17.32, 18.35, 19.45, 20.60, 21.83, 23.12, 24.50, 25.96, 27.50, 29.14, 30.87,
	// C1
	32.70, 34.65, 36.71, 38.89, 41.20, 43.65, 46.25, 49.00, 51.91, 55.00, 58.27, 61.74,
	// C2
	65.41, 69.30, 73.42, 77.78, 82.41, 87.31, 92.50, 98.00, 103.8, 110.0, 116.5, 123.5,
	// C3
	130.8, 138.6, 146.8, 155.6, 164.8, 174.6, 185.0, 196.0, 207.7, 220.0, 233.1, 247.0,
	// C4
	261.6, 277.2, 293.7, 311.1, 329.6, 349.2, 370.0, 392.0, 415.3, 440.0, 466.2, 493.9,
	// C5
	523.3, 554.4, 587.3, 622.3, 659.3, 698.5, 740.0, 784.0, 830.6, 880.0, 932.3, 987.7,
	// C6
	1047,  1109,  1175,  1245,  1319,  1397,  1480,  1568,  1661,  1760,  1865,  1976,
	// C7
	2093,  2217,  2349,  2489,  2637,  2794,  2960,  3136,  3322,  3520,  3729,  3951
);

function getClosest ($search, $arr) 
{
    $closest_v = null;
    $closest_i = 0;
    foreach ($arr as $i => $v) {
        if ($closest_v === null || abs($search - $closest_v) > abs($v - $search)) {
            $closest_v = $v;
            $closest_i = $i;
        }
    }
    return $closest_i;
}

function getNote ($d)
{
    global $notes;
    $f = 2000000 / $d; // freq for divisor for 2MHz (VI53 in UT-88)
    if (($f < 16) || ($f > 3951)) { echo "ERROR: note out of range! $d\n"; exit(1); }
    return getClosest($f, $notes);
}

    $f = fopen(pathinfo(__FILE__, PATHINFO_DIRNAME).'/music3.asm', 'r');
    $g = fopen(pathinfo(__FILE__, PATHINFO_DIRNAME).'/music3.mac', 'w');
    fputs($g, "\t.radix 10\n\n");

    // write notes
    fputs($g, "NoteDivs:\n\t.word\t4");
    for ($i=0; $i<count($notes)-1; $i++)
    {
        $d = intval(1000000 / $notes[$i+1]);
        if (($i % 12) == 0) fputs($g, "\n\t.word\t");
        fputs($g, str_pad($d, 5, '0', STR_PAD_LEFT));
        if (($i % 12) != 11) fputs($g, ", ");
    }
    fputs($g, "\n\n");

    // convert music to notes and write it
    while (!feof($f)) {
        $s = trim(fgets($f));
	    // TEMP not needed
	    if (strpos($s, '.byte 1;') !== false) continue;
	    // split string
        $i = strpos($s, ' ');
	    // it's label
        if ($i === false) { 
            // set .even before label PATx
            if (strpos($s, 'PAT') !== false) fputs($g, "\t.even\n");
            fputs($g, $s . "\n"); 
            continue; 
        }
        // strip .word/.byte and comments
        $s1 = substr($s, 0, $i);
        $s2 = substr($s, $i+1); 
        $arr = explode(';', $s2); 
        $s2 = $arr[0];
	    // its label addr
	    if (!is_numeric($s2)) {
            if ($s2 == '255*256') $s2 = '0';
	        fputs($g, "\t.word\t$s2\n"); continue; 
	    }
        // now it's note/pause/etc
        $d = intval($s2);
        // 255 - pattern ends
        if ($d == 255) {
            fputs($g, "\t.byte\t255\n");
            continue;
        }
        // 4 - stop playing next tick (convert to index 0)
        if ($d == 4) {
            fputs($g, "\t.byte\t0\n"); 
            continue;
        }
        // 00 bits - normal note
        if (($d & 3) == 0) {
            $n = getNote($d);
            fputs($g, "\t.byte\t$n\n");
            continue;
        }
        // least bit set - it's pause
        if (($d & 1) == 1) {
            $d = $d >> 1;
            $d--;
            if ($d > 0x7E) { echo "ERROR! pause too big"; exit(1); }
            $n = 0x80 + $d;
            fputs($g, "\t.byte\t$n ; pause ".($d+1)."\n");
            continue;
        }
        // else - pause 1 tick and note
        if (($d & 3) == 2) {
            $n = getNote($d & 0b1111111111111100);
            fputs($g, "\t.byte\t".(0x80)." ; pause 1\n");
            fputs($g, "\t.byte\t$n\n");
            continue;
        }
        echo "WTF?! $s1 $s2"; exit(1);
    }

    fputs($g, "\t.even\n\t.radix 8\n");
    fclose($f);
    fclose($g);
?>