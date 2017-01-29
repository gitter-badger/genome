<?php

HTML::plug('a', function($content = "", $href = null, $target = null, $attr = [], $dent = 0) {
    $attr_o = [
        'href' => $href,
        'target' => $target === true ? '_blank' : ($target === false ? null : $target)
    ];
    $attr = Anemon::extend($attr_o, $attr);
    $attr['href'] = URL::long(str_replace('&amp;', '&', $attr['href']));
    return HTML::unite('a', $content, $attr, $dent);
});

HTML::plug('img', function($src = null, $alt = null, $attr = [], $dent = 0) {
    $attr_o = [
        'src' => $src,
        'alt' => !isset($alt) ? "" : $alt
    ];
    $attr = Anemon::extend($attr_o, $attr);
    $attr['src'] = URL::long(str_replace('&amp;', '&', $attr['src']));
    return HTML::unite('img', false, $attr, $dent);
});

foreach (['br', 'hr'] as $unit) {
    HTML::plug($unit, function($i = 1, $attr = [], $dent = 0) use($unit) {
        return HTML::dent($dent) . str_repeat(HTML::unite($unit, false, $attr), $i);
    });
}

foreach (['ol', 'ul'] as $unit) {
    HTML::plug($unit, function($list = [], $attr = [], $dent = 0) use($unit) {
        $html = HTML::begin($unit, $attr, $dent) . N;
        foreach ($list as $k => $v) {
            if (is_array($v)) {
                $html .= HTML::begin('li', [], $dent + 1) . $k . N;
                $html .= call_user_func('HTML::' . $unit, $v, $attr, $dent + 2) . N;
                $html .= HTML::end('li', $dent + 1) . N;
            } else {
                $html .= HTML::unit('li', $v, [], $dent + 1) . N;
            }
        }
        return $html . HTML::end($unit, $dent);
    });
}