<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NCRAL_Token_Get_All_Function_Checker' ) ) {
	/**
	 * Class NCRAL_Token_Get_All_Function_Checker
	 */
	class NCRAL_Token_Get_All_Function_Checker implements NCRAL_Function_Checker {
		private $file_name;

		private $instructions;

		private $debug;

		private $database;

		private $function_calls;

		/**
		 * NCRAL_Token_Get_All_Function_Checker constructor.
		 *
		 * @param array|string $database Raw array, or JSON file.
		 */
		public function __construct( $database, $debug = false ) {
			$this->load_database( $database );
			$this->reset();
			$this->debug = $debug;

			$this->instructions = [
				T_FUNCTION => [ $this, 'consume_function_definition' ],
				T_STRING   => [ $this, 'detect_function_call' ],
			];
		}

		/**
		 * Scan function call.
		 *
		 * @param string $file_name Input file.
		 * @param string $prefix    Path prefix.
		 */
		public function scan( $file_name, $prefix ) {
			$this->file_name = ncral_strip_prefix( $file_name, $prefix );

			$tga = new NCRAL_Token_Get_All(
				$file_name,
				[
					'debug'        => $this->debug,
					'instructions' => $this->instructions,
				]
			);

			$tga->start();
		}

		public function reset() {
			$this->file_name      = '';
			$this->function_calls = [];
		}

		public function get_result() {
			ksort( $this->function_calls );

			return $this->function_calls;
		}

		public function consume_function_definition( &$tokens ) {
			while ( ( $token = next( $tokens ) ) ) {
				if ( ncral_is_token_of( '(', $token ) ) {
					return;
				}
			}
		}

		public function detect_function_call( &$tokens ) {
			$token = current( $tokens );

			$function_name    = $token[1];
			$function_line    = $token[2];
			$is_function_call = false;

			while ( ( $token = next( $tokens ) ) ) {
				if ( ncral_is_parser_token( $token ) ) {
					if ( T_WHITESPACE === $token[0] ) {
						continue;
					} else {
						break;
					}
				} elseif ( ncral_is_token_of( '(', $token ) ) {
					$is_function_call = true;
					break;
				}
			}

			if ( $is_function_call && isset( $this->database[ $function_name ] ) ) {
				if ( ! isset( $this->function_calls[ $function_name ] ) ) {
					$this->function_calls[ $function_name ] = ( new NCRAL_Function_Call_Info() )
						->set_function( $function_name )
						->add_line( $this->file_name, $function_line )
						->add_reference( $this->database[ $function_name ] );
				} else {
					$this->function_calls[ $function_name ]->add_line( $this->file_name, $function_line );
				}

				// Advance to semicolon.
				while ( ( $token = next( $tokens ) ) ) {
					if ( ncral_is_token_of( ';', $token ) ) {
						break;
					}
				}
			}
		}

		/**
		 * @param array|string $database Input database.
		 *                               Array: raw records. Use it as-is.
		 *                               String: json file path.
		 */
		private function load_database( $database ) {
			if ( is_array( $database ) ) {
				$this->database = $database;
			} elseif ( is_string( $database ) && file_exists( $database ) && is_readable( $database ) ) {
				$this->database = json_decode( file_get_contents( $database ), true );
			}

			$this->database = array_combine(
				array_map( // keys
					function ( $item ) {
						if ( $item instanceof NCRAL_Core_Function_Info ) {
							return $item->get_name();
						} elseif ( is_array( $item ) ) {
							return ncral_from_array( $item, 'name' );
						} else {
							wp_die( 'Invalid database.' );
						}
					},
					$this->database
				),
				array_map( // values
					function ( $item ) {
						if ( $item instanceof NCRAL_Core_Function_Info ) {
							return $item;
						} elseif ( is_array( $item ) ) {
							return NCRAL_Core_Function_Info::from_array( $item );
						} else {
							wp_die( 'Invalid database.' );
						}
					},
					array_values( $this->database )
				)
			);

			if ( empty( $this->database ) ) {
				wp_die( 'The database is invalid.' );
			}
		}
	}
}
