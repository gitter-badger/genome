<?php

return [
    'union' => [
        // 0 => [
        //     0 => ['\[\[', '\]\]', '\/'],
        //     1 => ['\=', '\"', '\"', '\s'],
        //     2 => ['\[\[\!', '\!\]\]']
        // ],
        1 => [
            0 => ['[[', ']]', '/'],
            1 => ['=', '"', '"', ' '],
            2 => ['[[!', '!]]'],
            3 => ['`', '`']
        ]
    ]
];