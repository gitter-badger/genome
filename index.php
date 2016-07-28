<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', rtrim(__DIR__, DS));
define('GROUND', rtrim($_SERVER['DOCUMENT_ROOT'], DS));

define('SESSION', null);

define('ENGINE', ROOT . DS . 'engine');
define('LOG', ROOT . DS . 'log');
define('PLUG', ROOT . DS . 'plug');
define('LOT', ROOT . DS . 'lot');
define('ASSET', LOT . DS . 'assets');
define('SCRAP', LOT . DS . 'scraps');

define('I', '  '); // Default indent
define('N', "\n"); // Line break
define('R', "\r"); // Return
define('S',  ' '); // Space
define('T', "\t"); // Tab
define('V', "\v"); // Vertical space
define('X', "\x1A"); // Placeholder text

define('ES', '>');
define('S_B', N . '====' . N); // Block separator
define('S_I', ':' . S); // Inline separator

define('CELL_BEGIN', ""); // Begin HTML output
define('CELL_END', N); // End HTML output

// Common HTML tag(s) allowed to be written in the form field
define('WISE_CELL_I', 'a,abbr,b,br,cite,code,del,dfn,em,i,ins,kbd,mark,q,span,strong,sub,sup,time,u,var');
define('WISE_CELL_B', 'address,blockquote,caption,dd,div,dl,dt,figcaption,figure,hr,h1,h2,h3,h4,h5,h6,li,ol,p,pre,table,tbody,tfoot,td,th,tr,ul');
define('WISE_CELL', WISE_CELL_I . WISE_CELL_B);

require ENGINE . DS . 'launch.php';