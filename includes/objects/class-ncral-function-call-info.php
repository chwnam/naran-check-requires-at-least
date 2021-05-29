<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NCRAL_Function_Call_Info' ) ) {
	class NCRAL_Function_Call_Info {
		private $function = '';

		private $wp_core_file = '';

		private $wp_core_line = '';

		private $since = '';

		private $deprecated = '';

		/**
		 * @var array<string, int[]>
		 */
		private $lines = [];

		/**
		 * @return string
		 */
		public function get_function() {
			return $this->function;
		}

		/**
		 * @param string $function
		 *
		 * @return NCRAL_Function_Call_Info
		 */
		public function set_function( $function ) {
			$this->function = $function;
			return $this;
		}

		/**
		 * @return string
		 */
		public function get_wp_core_file() {
			return $this->wp_core_file;
		}

		/**
		 * @param string $wp_core_file
		 *
		 * @return NCRAL_Function_Call_Info
		 */
		public function set_wp_core_file( $wp_core_file ) {
			$this->wp_core_file = $wp_core_file;
			return $this;
		}

		/**
		 * @return string
		 */
		public function get_wp_core_line() {
			return $this->wp_core_line;
		}

		/**
		 * @param string $wp_core_line
		 *
		 * @return NCRAL_Function_Call_Info
		 */
		public function set_wp_core_line( $wp_core_line ) {
			$this->wp_core_line = $wp_core_line;
			return $this;
		}

		/**
		 * @return string
		 */
		public function get_since() {
			return $this->since;
		}

		/**
		 * @param string $since
		 *
		 * @return NCRAL_Function_Call_Info
		 */
		public function set_since( $since ) {
			$this->since = $since;
			return $this;
		}

		/**
		 * @return string
		 */
		public function get_deprecated() {
			return $this->deprecated;
		}

		/**
		 * @param string $deprecated
		 *
		 * @return NCRAL_Function_Call_Info
		 */
		public function set_deprecated( $deprecated ) {
			$this->deprecated = $deprecated;
			return $this;
		}

		/**
		 * @param string $file
		 * @param int    $line
		 *
		 * @return NCRAL_Function_Call_Info
		 */
		public function add_line( $file, $line ) {
			$this->lines[ $file ][] = $line;
			return $this;
		}

		public function get_lines() {
			return $this->lines;
		}

		/**
		 * Add the reference data.
		 *
		 * @param NCRAL_Core_Function_Info $reference
		 *
		 * @return NCRAL_Function_Call_Info
		 */
		public function add_reference( NCRAL_Core_Function_Info $reference ) {
			return $this
				->set_wp_core_file( $reference->get_file() )
				->set_wp_core_line( $reference->get_line() )
				->set_since( $reference->get_since() )
				->set_deprecated( $reference->get_deprecated() );
		}

		/**
		 * Export to an array.
		 *
		 * @return array
		 */
		public function to_array() {
			return [];
		}

		/**
		 * Create an instance from an array.
		 *
		 * @param array $array
		 *
		 * @return static
		 */
		public static function from_array( array $array ) {
			$instance = new static();

			if ( isset( $array['function'] ) ) {
				$instance->set_function( $array['function'] );
			}

			if ( isset( $array['wp_core_file'] ) ) {
				$instance->set_wp_core_file( $array['wp_core_file'] );
			}

			if ( isset( $array['wp_core_line'] ) ) {
				$instance->set_wp_core_line( $array['wp_core_line'] );
			}

			if ( isset( $array['since'] ) ) {
				$instance->set_since( $array['since'] );
			}

			if ( isset( $array['deprecated'] ) ) {
				$instance->set_deprecated( $array['deprecated'] );
			}

			if ( isset( $array['lines'] ) && is_array( $array['lines'] ) ) {
				foreach ( $array['lines'] as $file => $lines ) {
					if ( is_array( $lines ) ) {
						foreach ( $lines as $line ) {
							$instance->add_line( $file, $line );
						}
					}
				}
			}

			return $instance;
		}
	}
}
