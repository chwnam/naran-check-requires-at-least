<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'NCRAL_Admin_Module' ) ) {
	interface NCRAL_Admin_Module extends NCRAL_Module {
	}
}
