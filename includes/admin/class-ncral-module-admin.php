<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NCRAL_Module_Admin' ) ) {
	/**
	 * Class NCRAL_Module_Admin
	 *
	 * @property-read NCRAL_Module_Admin_Tools $settings
	 */
	class NCRAL_Module_Admin implements NCRAL_Module {
		use NCRAL_Submodule_Impl;

		public function __construct() {
			$this->submodules = [
				'settings' => new NCRAL_Module_Admin_Tools(),
			];
		}
	}
}
