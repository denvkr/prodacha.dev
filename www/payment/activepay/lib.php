<?php

function custom_hmac($algo, $data, $key, $raw_output = false) {
    $algo = strtolower($algo);
    $pack = 'H'.strlen($algo('test'));
    $size = 64;
    $opad = str_repeat(chr(0x5C), $size);
    $ipad = str_repeat(chr(0x36), $size);

    if (strlen($key) > $size) {
        $key = str_pad(pack($pack, $algo($key)), $size, chr(0x00));
    } else {
        $key = str_pad($key, $size, chr(0x00));
    }

    for ($i = 0; $i < strlen($key) - 1; $i++) {
        $opad[$i] = $opad[$i] ^ $key[$i];
        $ipad[$i] = $ipad[$i] ^ $key[$i];
    }

    $output = $algo($opad.pack($pack, $algo($ipad.$data)));

    return ($raw_output) ? pack($pack, $output) : $output;
}


function build_query_string($data) {
    $query_string = "";
    ksort($data);
    foreach ($data as $item => $value) {
        if ($query_string != "") {
            $query_string = $query_string."&";
        }
        $query_string = $query_string.rawurlencode($item)."=".rawurlencode($value);
    }
    return $query_string;
}

function sign($method, $url_domain, $url_uri, $secret_key, $data) {
    $url = "http://$url_domain$url_uri";
    $query_string = build_query_string($data);
    $string_to_sign = "$method\n$url_domain\n$url_uri\n$query_string";
    $hmac_sha1_hash = custom_hmac("sha1", $string_to_sign, $secret_key, true);
    return base64_encode($hmac_sha1_hash);
}
?>
