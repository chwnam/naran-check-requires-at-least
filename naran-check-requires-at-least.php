<?php
/*
 * Plugin Name:       Naran Check Requires At Least
 * Plugin URI:        https://github.com/chwnam/naran-check-requires-at-least
 * Description:       Helps finding proper 'Requires at least' header.
 * Author:            changwoo
 * Author URI:        https://blog.changwoo.pe.kr
 * Version:           1.0.0
 * Requires at least: 3.0.0
 * Requires PHP:      5.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const NCRAL_MAIN     = __FILE__;
const NCRAL_VERSION  = '1.0.0';
const NCRAL_PRIORITY = 200;

require_once __DIR__ . '/vendor/autoload.php';

ncral();
