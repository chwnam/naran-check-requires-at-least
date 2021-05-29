<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'NCRAL_Core_Function_Data_Collector' ) ) {
	interface NCRAL_Core_Function_Data_Collector {
		public function scan( $file_name, $prefix );

		/**
		 * @return NCRAL_Core_Function_Info[]
		 */
		public function get_result();
	}
}
