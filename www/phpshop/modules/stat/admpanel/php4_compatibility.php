<?php
if (!function_exists('iconv')) {
    function iconv($inchar, $outchar, $sInput )//supports only cp1251 to UTF-8 convertation
    {
        $sOutput = "";

        for ( $i = 0; $i < strlen( $sInput ); $i++ ) {
            $iAscii = ord( $sInput[$i] );

            if ( $iAscii >= 192 && $iAscii <= 255 )
                $sOutput .=  "&#".( 1040 + ( $iAscii - 192 ) ).";";
            else if ( $iAscii == 168 )
                $sOutput .= "&#".( 1025 ).";";
            else if ( $iAscii == 184 )
                $sOutput .= "&#".( 1105 ).";";
            else
                $sOutput .= $sInput[$i];
        }

        return $sOutput;
    }
}

if (!function_exists('is_unicode')) {
    function is_unicode($str) {
        for ($i=0; $i<strlen($str); $i++) {
            if (ord($str[$i])>191) {
                if (ord($str[($i+1)])<128 || ord($str[($i+1)])>191) {
                    return false;
                }
                else {
                    $i++;
                }
            }
        }
        return true;
    }
}

?>
