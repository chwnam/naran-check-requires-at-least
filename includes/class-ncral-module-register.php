<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NCRAL_Module_Register' ) ) {
	class NCRAL_Module_Register implements NCRAL_Module {
		use NCRAL_Hook_Impl;

		public function __construct() {
			$this->action( 'init', 'register' );
		}

		public function register() {
			if ( is_admin() ) {
				wp_register_script(
					'ncral-admin-tools',
					plugins_url( 'assets/admin-tools.js', NCRAL_MAIN ),
					[ 'jquery' ],
					NCRAL_VERSION
				);

				wp_register_style(
					'ncral-admin-tools',
					plugins_url( 'assets/admin-tools.css', NCRAL_MAIN ),
					[ 'common' ],
					NCRAL_VERSION
				);
			}
		}
	}
}
