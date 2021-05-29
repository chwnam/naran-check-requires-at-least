<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'NCRAL_Function_Checker' ) ) {
	interface NCRAL_Function_Checker {
		public function scan( $file_name, $prefix );

		public function get_result();

		public function reset();
	}
}
