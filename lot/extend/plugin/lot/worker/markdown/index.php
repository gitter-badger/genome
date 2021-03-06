<?php

function fn_markdown($input = "", $lot = []) {
    if (!isset($lot['type']) || $lot['type'] !== 'Markdown') {
        return $input;
    }
    $x = new ParsedownExtraPlugin;
    foreach (Plugin::state(__DIR__) as $k => $v) {
        $x->{$k} = $v;
    }
    return $x->text($input);
}

function fn_markdown_span($input, $lot = []) {
    if (!isset($lot)) return $input;
    return t(fn_markdown($input, $lot), '<p>', '</p>');
}

From::plug('markdown', function($input) {
    return fn_markdown($input, ['type' => 'Markdown']);
});

To::plug('markdown', function($input) {
    return $input; // TODO
});

Hook::set('page.title', 'fn_markdown_span', 2);
Hook::set(['page.description', 'page.content'], 'fn_markdown', 2);