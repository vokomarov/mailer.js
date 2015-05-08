<?php

/**
 * Verification captcha
 * @param $s string - captcha code
 * @return int - match result for captcha code
 */
function captureData($s){
    if (strlen($s) == 4 && preg_match('/\d{4}/',$s)) {
        $d4 = (abs(($s[0]+$s[1]-$s[2]+10)*($s[0]/$s[1]+$s[2]))*date('j')*date('n'))%10;
        if ($d4 == $s[3]) $capOk = 1;
        else $capOk = 0;
    } else $capOk = 0;
    return	$capOk;
}

/**
 * @param $response array - array
 */

function sendResponse($response){
    echo json_encode($response);
    exit;
}