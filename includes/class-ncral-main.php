<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NCRAL_Main' ) ) {
	/**
	 * Class NCRAL_Main
	 *
	 * @property-read NCRAL_Module_Admin     $admin
	 * @property-read NCRAL_Module_Register  $register
	 */
	final class NCRAL_Main {
		use NCRAL_Submodule_Impl;

		private static $instance = null;

		/**
		 * Get the instance.
		 *
		 * @return NCRAL_Main|null
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		private function __construct() {
			$this->submodules = [
				'admin'     => new NCRAL_Module_Admin(),
				'register'  => new NCRAL_Module_Register(),
			];

			register_activation_hook( NCRAL_MAIN, [ $this, 'activation_callback' ] );
			register_deactivation_hook( NCRAL_MAIN, [ $this, 'deactivation_callback' ] );
			register_uninstall_hook( NCRAL_MAIN, [ __CLASS__, 'uninstall_callback' ] );
		}

		public function __sleep() {
			wp_die( __METHOD__ . ' is not supported.' );
		}

		public function __wakeup() {
			wp_die( __METHOD__ . ' is not supported.' );
		}

		/**
		 * Activation hook.
		 */
		public function activation_callback() {
			do_action( 'ncral_activation' );
		}

		/**
		 * Deactivation hook.
		 */
		public function deactivation_callback() {
			do_action( 'ncral_deactivation' );
		}

		/**
		 * Uninstall hook.
		 */
		public static function uninstall_callback() {
			ncral();
			do_action( 'ncral_uninstall' );
		}
	}
}
