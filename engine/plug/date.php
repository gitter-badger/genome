<?php

Hook::set('shield.before', function($out = "") {
    $key = str_replace('-', '_', Config::get('language'));
    if (!Date::get($key)) {
        Date::set($key, function($output) {
            return $output['en_us'];
        });
    }
    return $out;
}, 20);