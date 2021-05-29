<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'ncral' ) ) {
	/**
	 * @return NCRAL_Main
	 */
	function ncral() {
		return NCRAL_Main::get_instance();
	}
}
