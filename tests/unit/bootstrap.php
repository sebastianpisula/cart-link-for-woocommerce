<?php
/**
 * PHPUnit bootstrap file
 */

require_once __DIR__ . '/../../vendor/autoload.php';

error_reporting( E_ALL & ~E_DEPRECATED );

WP_Mock::setUsePatchwork( true );
WP_Mock::bootstrap();