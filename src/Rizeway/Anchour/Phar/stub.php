#!/usr/bin/env php
<?php
Phar::mapPhar('anchour.phar');

if (!defined('ANCHOUR_VERSION')) {
    define('ANCHOUR_VERSION', '1.1.0-phar');
}

require 'phar://anchour.phar/anchour.php';

__HALT_COMPILER();
