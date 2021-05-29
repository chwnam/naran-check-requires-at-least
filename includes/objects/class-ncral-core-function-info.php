<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NCRAL_Core_Function_Info' ) ) {
	class NCRAL_Core_Function_Info {
		private $name = '';

		private $file = '';

		private $line = '';

		private $since = '';

		private $deprecated = '';

		/**
		 * @return string
		 */
		public function get_name() {
			return $this->name;
		}

		/**
		 * @param string $name
		 *
		 * @return NCRAL_Core_Function_Info
		 */
		public function set_name( $name ) {
			$this->name = $name;
			return $this;
		}

		/**
		 * @return string
		 */
		public function get_file() {
			return $this->file;
		}

		/**
		 * @param string $file
		 *
		 * @return NCRAL_Core_Function_Info
		 */
		public function set_file( $file ) {
			$this->file = $file;
			return $this;
		}

		/**
		 * @return string
		 */
		public function get_line() {
			return $this->line;
		}

		/**
		 * @param string $line
		 *
		 * @return NCRAL_Core_Function_Info
		 */
		public function set_line( $line ) {
			$this->line = $line;
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
		 * @return NCRAL_Core_Function_Info
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
		 * @return NCRAL_Core_Function_Info
		 */
		public function set_deprecated( $deprecated ) {
			$this->deprecated = $deprecated;
			return $this;
		}

		/**
		 * Export to an array.
		 *
		 * @return array
		 */
		public function to_array() {
			return get_object_vars( $this );
		}

		/**
		 * Crate an instance from an array.
		 *
		 * @param array $array Input array.
		 *
		 * @return static
		 */
		public static function from_array( array $array ) {
			$instance = new static();

			if ( isset( $array['name'] ) ) {
				$instance->set_name( $array['name'] );
			}

			if ( isset( $array['file'] ) ) {
				$instance->set_file( $array['file'] );
			}

			if ( isset( $array['line'] ) ) {
				$instance->set_line( $array['line'] );
			}

			if ( isset( $array['since'] ) ) {
				$instance->set_since( $array['since'] );
			}

			if ( isset( $array['deprecated'] ) ) {
				$instance->set_deprecated( $array['deprecated'] );
			}

			return $instance;
		}
	}
}
