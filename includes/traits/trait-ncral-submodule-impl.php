<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! trait_exists( 'NCRAL_Submodule_Impl' ) ) {
	trait NCRAL_Submodule_Impl {
		protected $submodules = [];

		public function __set( $name, $value ) {
			wp_die( __METHOD__ . ' is not supported.' );
		}

		/**
		 * @param string $name
		 *
		 * @return NCRAL_Module|null
		 */
		public function __get( $name ) {
			if ( isset( $this->submodules[ $name ] ) ) {
				if ( is_callable( $this->submodules[ $name ] ) ) {
					$this->submodules[ $name ] = call_user_func( $this->submodules[ $name ] );
				}
				return $this->submodules[ $name ];
			} else {
				return null;
			}
		}

		/**
		 * @param $name
		 *
		 * @return bool
		 */
		public function __isset( $name ) {
			return isset( $this->submodules[ $name ] );
		}
	}
}