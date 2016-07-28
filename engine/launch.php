<?php

// Check for valid data collection
function __such_anemon__($x) {
    return is_array($x) || is_object($x);
}

// Check for valid JSON string
function __such_json__($x) {
    if (!is_string($x) || !trim($x)) return false;
    return (
        // Maybe an empty string, array or object
        $x === '""' ||
        $x === '[]' ||
        $x === '{}' ||
        // Maybe an encoded JSON string
        $x[0] === '"' ||
        // Maybe a flat array
        $x[0] === '[' ||
        // Maybe an associative array
        strpos($x, '{"') === 0
    ) && json_decode($x) !== null && json_last_error() !== JSON_ERROR_NONE;
}

// Check for valid serialize string
function __such_serialize__($x) {
    if(!is_string($x) || !trim($x)) return false;
    return $x === 'N;' || strpos($x, 'a:') === 0 || strpos($x, 'b:') === 0 || strpos($x, 'd:') === 0 || strpos($x, 'i:') === 0 || strpos($x, 's:') === 0 || strpos($x, 'O:') === 0;
}

// a: convert object to array
// b:
// c: convert text to camel case
// d:
// e: evaluate string to their proper data type
// f: filter/sanitize string
// g:
// h: convert text to snake case with `-` (hyphen) as separator by default
// i:
// j:
// k:
// l: convert text to lower case
// m:
// n: normalize white-space in string
// o: convert object to array
// p: convert text to pascal case
// q: quantity (length of string, number or anemon)
// r:
// s: convert data type to their string format
// t: convert N to a tab
// u: convert text to upper case
// v: un-escape
// w: convert any data to plain word(s)
// x: escape
// y: output/yield an echo-based function as normal return value
// z: inspect data in a dump

function a($o) {
    if (__such_anemon__($o)) {
        $o = (array) $o;
        foreach ($o as &$oo) {
            $oo = a($oo);
        }
        unset($oo);
    }
    return $o;
}

function b() {}

function c($x) {
    return preg_replace_callback('#([^\p{L}])(\p{Ll})#u', function($m) {
        return u($m[2]);
    }, $x);
}

function d() {}

function e($x) {
    if (is_string($x)) {
        if ($x === "") return $x;
        if (is_numeric($x)) {
            return strpos($x, '.') !== false ? (float) $x : (int) $x;
        } elseif (__such_json__($x) && $v = json_decode($input, true)) {
            return is_array($v) ? e($v) : $v;
        } elseif ($x[0] === '"' && substr($x, -1) === '"' || $x[0] === "'" && substr($x, -1) === "'") {
            return substr(substr($x, 1), 0, -1);
        }
        $xx = [
            'TRUE' => true,
            'FALSE' => false,
            'NULL' => null,
            'true' => true,
            'false' => false,
            'null' => null,
            'yes' => true,
            'no' => false,
            'on' => true,
            'off' => false
        ];
        return $xx[$x] ?? $x;
    } elseif (__such_anemon__($x)) {
        foreach ($x as &$v) {
            $v = e($v);
        }
        unset($v);
    }
    return $x;
}

function f($x, $s = '-', $low = false, $X = 'a-zA-Z\d', $mod = 1) {
    $s_x = preg_quote($s, '#');
    $X .= $s_x;
    $from = explode(',', '¹,²,³,°,æ,ǽ,À,Á,Â,Ã,Å,Ǻ,Ă,Ǎ,Æ,Ǽ,à,á,â,ã,å,ǻ,ă,ǎ,ª,@,Ĉ,Ċ,ĉ,ċ,©,Ð,Đ,ð,đ,È,É,Ê,Ë,Ĕ,Ė,è,é,ê,ë,ĕ,ė,ƒ,Ĝ,Ġ,ĝ,ġ,Ĥ,Ħ,ĥ,ħ,Ì,Í,Î,Ï,Ĩ,Ĭ,Ǐ,Į,Ĳ,ì,í,î,ï,ĩ,ĭ,ǐ,į,ĳ,Ĵ,ĵ,Ĺ,Ľ,Ŀ,ĺ,ľ,ŀ,Ñ,ñ,ŉ,Ò,Ô,Õ,Ō,Ŏ,Ǒ,Ő,Ơ,Ø,Ǿ,Œ,ò,ô,õ,ō,ŏ,ǒ,ő,ơ,ø,ǿ,º,œ,Ŕ,Ŗ,ŕ,ŗ,Ŝ,Ș,ŝ,ș,ſ,Ţ,Ț,Ŧ,Þ,ţ,ț,ŧ,þ,Ù,Ú,Û,Ũ,Ŭ,Ű,Ų,Ư,Ǔ,Ǖ,Ǘ,Ǚ,Ǜ,ù,ú,û,ũ,ŭ,ű,ų,ư,ǔ,ǖ,ǘ,ǚ,ǜ,Ŵ,ŵ,Ý,Ÿ,Ŷ,ý,ÿ,ŷ,Ъ,Ь,А,Б,Ц,Ч,Д,Е,Ё,Э,Ф,Г,Х,И,Й,Я,Ю,К,Л,М,Н,О,П,Р,С,Ш,Щ,Т,У,В,Ы,З,Ж,ъ,ь,а,б,ц,ч,д,е,ё,э,ф,г,х,и,й,я,ю,к,л,м,н,о,п,р,с,ш,щ,т,у,в,ы,з,ж,Ä,Ö,Ü,ß,ä,ö,ü,Ç,Ğ,İ,Ş,ç,ğ,ı,ş,Ā,Ē,Ģ,Ī,Ķ,Ļ,Ņ,Ū,ā,ē,ģ,ī,ķ,ļ,ņ,ū,Ґ,І,Ї,Є,ґ,і,ї,є,Č,Ď,Ě,Ň,Ř,Š,Ť,Ů,Ž,č,ď,ě,ň,ř,š,ť,ů,ž,Ą,Ć,Ę,Ł,Ń,Ó,Ś,Ź,Ż,ą,ć,ę,ł,ń,ó,ś,ź,ż,Α,Β,Γ,Δ,Ε,Ζ,Η,Θ,Ι,Κ,Λ,Μ,Ν,Ξ,Ο,Π,Ρ,Σ,Τ,Υ,Φ,Χ,Ψ,Ω,Ϊ,Ϋ,ά,έ,ή,ί,ΰ,α,β,γ,δ,ε,ζ,η,θ,ι,κ,λ,μ,ν,ξ,ο,π,ρ,ς,σ,τ,υ,φ,χ,ψ,ω,ϊ,ϋ,ό,ύ,ώ,ϐ,ϑ,ϒ,أ,ب,ت,ث,ج,ح,خ,د,ذ,ر,ز,س,ش,ص,ض,ط,ظ,ع,غ,ف,ق,ك,ل,م,ن,ه,و,ي,ạ,ả,ầ,ấ,ậ,ẩ,ẫ,ằ,ắ,ặ,ẳ,ẵ,ẹ,ẻ,ẽ,ề,ế,ệ,ể,ễ,ị,ỉ,ọ,ỏ,ồ,ố,ộ,ổ,ỗ,ờ,ớ,ợ,ở,ỡ,ụ,ủ,ừ,ứ,ự,ử,ữ,ỳ,ỵ,ỷ,ỹ,Ạ,Ả,Ầ,Ấ,Ậ,Ẩ,Ẫ,Ằ,Ắ,Ặ,Ẳ,Ẵ,Ẹ,Ẻ,Ẽ,Ề,Ế,Ệ,Ể,Ễ,Ị,Ỉ,Ọ,Ỏ,Ồ,Ố,Ộ,Ổ,Ỗ,Ờ,Ớ,Ợ,Ở,Ỡ,Ụ,Ủ,Ừ,Ứ,Ự,Ử,Ữ,Ỳ,Ỵ,Ỷ,Ỹ');
    $to = explode(',', '1,2,3,0,ae,ae,A,A,A,A,A,A,A,A,AE,AE,a,a,a,a,a,a,a,a,a,at,C,C,c,c,c,Dj,D,dj,d,E,E,E,E,E,E,e,e,e,e,e,e,f,G,G,g,g,H,H,h,h,I,I,I,I,I,I,I,I,IJ,i,i,i,i,i,i,i,i,ij,J,j,L,L,L,l,l,l,N,n,n,O,O,O,O,O,O,O,O,O,O,OE,o,o,o,o,o,o,o,o,o,o,o,oe,R,R,r,r,S,S,s,s,s,T,T,T,TH,t,t,t,th,U,U,U,U,U,U,U,U,U,U,U,U,U,u,u,u,u,u,u,u,u,u,u,u,u,u,W,w,Y,Y,Y,y,y,y,,,A,B,C,Ch,D,E,E,E,F,G,H,I,J,Ja,Ju,K,L,M,N,O,P,R,S,Sh,Shch,T,U,V,Y,Z,Zh,,,a,b,c,ch,d,e,e,e,f,g,h,i,j,ja,ju,k,l,m,n,o,p,r,s,sh,shch,t,u,v,y,z,zh,AE,OE,UE,ss,ae,oe,ue,C,G,I,S,c,g,i,s,A,E,G,I,K,L,N,U,a,e,g,i,k,l,n,u,G,I,Ji,Ye,g,i,ji,ye,C,D,E,N,R,S,T,U,Z,c,d,e,n,r,s,t,u,z,A,C,E,L,N,O,S,Z,Z,a,c,e,l,n,o,s,z,z,A,B,G,D,E,Z,E,Th,I,K,L,M,N,X,O,P,R,S,T,Y,Ph,Ch,Ps,O,I,Y,a,e,e,i,Y,a,b,g,d,e,z,e,th,i,k,l,m,n,x,o,p,r,s,s,t,y,ph,ch,ps,o,i,y,o,y,o,b,th,Y,a,b,t,th,g,h,kh,d,th,r,z,s,sh,s,d,t,th,aa,gh,f,k,k,l,m,n,h,o,y,a,a,a,a,a,a,a,a,a,a,a,a,e,e,e,e,e,e,e,e,i,i,o,o,o,o,o,o,o,o,o,o,o,o,u,u,u,u,u,u,u,y,y,y,y,A,A,A,A,A,A,A,A,A,A,A,A,E,E,E,E,E,E,E,E,I,I,O,O,O,O,O,O,O,O,O,O,O,O,U,U,U,U,U,U,U,Y,Y,Y,Y');
    $xx = preg_replace(
        [
            '#<.*?>|&(?:[a-z\d]+|\#\d+|\#x[a-f\d]+);#i',
            '#[^' . $X . ']#',
            '#' . $s_x . '+|^' . $s_x . '|' . $s_x . '$#'
        ], $s,
    $mod === 1 ? strtr($x, array_combine($from, $to)) : $x);
    return !empty($xx) ? ($low ? (function_exists('mb_strtolower') ? mb_strtolower($xx) : strtolower($xx)) : $xx) : $s . $s;
}

function g() {}

function h($x, $s = '-') {
    return f(preg_replace_callback('#(.)(\p{Lu})#u', function($m) use($s) {
        return $m[1] . $s . l($m[2]);
    }, $x), $s, true);
}

function i() {}
function j() {}
function k() {}

function l($x) {
    return function_exists('mb_strtolower') ? mb_strtolower($x) : strtolower($x);
}

function m() {}

function n($x, $t = '    ') {
    // Tab to 4 space(s), line-break to `\n`
    return str_replace(["\t", "\r\n", "\r"], [$t, "\n", "\n"], $x);
}

function o($a, $safe = true) {
    if (__such_anemon__($a)) {
        $a = (array) $a;
        $a = $safe && count($a) && array_keys($a) !== range(0, count($a) - 1) ? (object) $a : $a;
        foreach ($a as &$aa) {
            $aa = o($aa, $safe);
        }
        unset($aa);
    }
    return $a;
}

function p($x) {
    return preg_replace_callback('#(^|[^\p{L}])(\p{Ll})#u', function($m) {
        return u($m[2]);
    }, $x);
}

function q($x, $deep = false) {
    if (is_int($x) || is_float($x)) {
        return $x;
    } else if (is_string($x)) {
        return function_exists('mb_strlen') ? mb_strlen($x) : strlen($x);
    } else if (__such_anemon__($x)) {
        return count(a($x), $deep ? COUNT_RECURSIVE : COUNT_NORMAL);
    }
    return count($x);
}

function r() {}

function s($x) {
    if (__such_anemon__($x)) {
        foreach ($x as &$v) {
            $v = s($v);
        }
        unset($v);
        return $x;
    } elseif ($x === true) {
        return 'true';
    } elseif ($x === false) {
        return 'false';
    } elseif ($x === null) {
        return 'null';
    }
    return (string) $x;
}

function t($x, $t = '    ') {
    return str_replace($t, "\t", $x);
}

function u($x) {
    return function_exists('mb_strtoupper') ? mb_strtoupper($x) : strtoupper($x);
}

function v($x) {
    return stripslashes($x);
}

// w.h: list of tag name in HTML separated by comma to be excluded from `strip_tags()`
// w.n: @keep line-break in the output or replace them with a space? (default is !@keep)
function w($x, $c = "", $n = true) {
    // Should be a HTML input
    if(strpos($x, '<') !== false || strpos($x, ' ') !== false) {
        $c = '<' . str_replace(',', '><', $c) . '>';
        return preg_replace($n ? '#\s+#' : '# +#', ' ', trim(strip_tags($x, $c)));
    }
    // 1. Replace `+` to ` `
    // 2. Replace `-` to ` `
    // 3. Replace `-----` to ` - `
    // 4. Replace `---` to `-`
    return preg_replace(
        [
            '#^(\.|_{2})|(\.|_{2})$#', // remove `.` and `__` prefix/suffix in a file name
            '#-{5}#',
            '#-{3}#',
            '#-#',
            '#\s+#',
            '#' . X . '#'
        ],
        [
            "",
            ' ' . X . ' ',
            X,
            ' ',
            ' ',
            '-'
        ],
    urldecode($x));
}

function x($x, $c = "'", $d = '-+*/()[]{}^$.?|\\') {
    return addcslashes($x, $d . $c);
}

function y($x, $a = []) {
    if (is_callable($x)) {
        ob_start();
        call_user_func_array($x, $a);
        return ob_get_clean();
    }
    return $x;
}

ini_set('highlight.comment', '#FF8000');
ini_set('highlight.default', '#0000BB');
ini_set('highlight.html', '#000000');
ini_set('highlight.keyword', '#007700');
ini_set('highlight.string', '#DD0000');

function z(...$a) {
    foreach ($a as $b) {
        $s = var_export($b, true);
        $s = str_ireplace(['array (', 'TRUE', 'FALSE', 'NULL'], ['array(', 'true', 'false', 'null'], $s);
        $s = preg_replace('#[,;](\s*[\)\}])#', '$1', $s);
        echo '<pre style="word-wrap:break-word;white-space:pre-wrap;">';
        highlight_string("<?php\n\n" . $s . "\n\n?>");
        echo '</pre>';
    }
}