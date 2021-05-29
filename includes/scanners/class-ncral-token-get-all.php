<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NCRAL_Token_Get_All' ) ) {
	class NCRAL_Token_Get_All {
		/**
		 * Input file name.
		 *
		 * @var string
		 */
		private $file_name;

		private $debug;

		private $instructions;

		/**
		 * NCRAL_Token_Get_All constructor.
		 *
		 * @param string $file_name Input file name.
		 * @param array  $args      Option arguments.
		 */
		public function __construct( $file_name, $args = [] ) {
			$this->file_name = $file_name;

			$args = wp_parse_args(
				$args,
				[
					/**
					 * Debugging mode.
					 *
					 * @bool
					 */
					'debug'        => false,

					/**
					 * Instructions for tokens.
					 *
					 * @array <int|string, callable>
					 * @link  https://www.php.net/manual/en/tokens.php
					 */
					'instructions' => [],
				]
			);

			$this->debug           = boolval( $args['debug'] );
			$this->instructions    = (array) $args['instructions'];
		}

		public function start() {
			if ( file_exists( $this->file_name ) && is_readable( $this->file_name ) ) {
				$tokens = token_get_all( file_get_contents( $this->file_name ) );
			} else {
				$tokens = [];
			}

			while ( ( $token = current( $tokens ) ) ) {
				$this->trace_token( $token );
				$t = ncral_is_parser_token( $token ) ? $token[0] : $token;
				if ( $t && isset( $this->instructions[ $t ] ) ) {
					call_user_func_array( $this->instructions[ $t ], [ &$tokens, $this ] );
				} elseif ( isset( $this->instructions['default'] ) ) {
					call_user_func_array( $this->instructions['default'], [ &$tokens, $this ] );
				}
				next( $tokens );
			}
		}

		public function trace_token( $token ) {
			if ( ! $this->is_debug() ) {
				return;
			}
			if ( ncral_is_parser_token( $token ) ) {
				$this->log( "LINE: {$token[2]} " . token_name( $token[0] ) . ": {$token[1]}" );
			} elseif ( is_string( $token ) ) {
				$this->log( $token );
			}
		}

		public function is_debug() {
			return $this->debug;
		}

		private function log( $text ) {
			if ( 'cli' === php_sapi_name() ) {
				if ( defined( 'NCRAL_UNIT_TEST' ) ) {
					echo PHP_EOL . $text;
				} else {
					echo $text . PHP_EOL;
				}
			} else {
				error_log( $text );
			}
		}
	}
}
