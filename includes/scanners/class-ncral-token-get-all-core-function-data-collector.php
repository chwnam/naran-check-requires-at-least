<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NCRAL_Token_Get_All_Core_Function_Data_Collector' ) ) {
	class NCRAL_Token_Get_All_Core_Function_Data_Collector implements NCRAL_Core_Function_Data_Collector {
		private $file_name;

		private $instructions;

		private $debug;

		private $doc_comment;

		private $functions;

		public function __construct( $debug = false ) {
			$this->debug = $debug;

			$this->instructions = [
				T_CLASS       => [ $this, 'consume_class' ],
				T_FUNCTION    => [ $this, 'extract_function' ],
				T_DOC_COMMENT => [ $this, 'grab_doc_comment' ],
				T_WHITESPACE  => [ $this, 'noop' ],
				'default'     => [ $this, 'release_doc_comment' ],
			];
		}

		/**
		 * @param string $file_name Input file name.
		 * @param string $prefix    Used for stripping out file_name.
		 */
		public function scan( $file_name, $prefix ) {
			$this->file_name   = ncral_strip_prefix( $file_name, $prefix );
			$this->doc_comment = '';
			$this->functions   = [];

			$tga = new NCRAL_Token_Get_All(
				$file_name,
				[
					'debug'        => $this->debug,
					'instructions' => $this->instructions,
				]
			);

			$tga->start();
		}

		public function get_result() {
			return $this->functions;
		}

		public function consume_class( &$tokens ) {
			$brace_stack = [];

			while ( ( $token = next( $tokens ) ) ) {
				if ( ncral_is_token_of( T_STRING, $token ) ) {
					break;
				}
			}

			// 사실 익명 함수에 대한 대응은 잘 되어 있지 않다.
			// 만약 익명 함수의 생성자 인자로 익명 함수 같은 것이 들어가면 문제가 생길 것.
			// 그러나 현재 워드프레스 코어 코드에서 그러한 부분은 발견되지 않으므로 무시한다.

			// Consume all class implementation codes.
			while ( ( $token = next( $tokens ) ) ) {
				if ( ncral_is_token_of( '{', $token ) ) {
					array_push( $brace_stack, $token );
				} elseif ( ncral_is_token_of( '}', $token ) ) {
					array_pop( $brace_stack );
					if ( empty( $brace_stack ) ) {
						break;
					}
				} elseif ( ncral_is_token_of( [ T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES ], $token ) ) {
					array_push( $brace_stack, '{' );
				}
			}
		}

		public function extract_function( &$tokens ) {
			while ( ( $token = next( $tokens ) ) ) {
				if ( ncral_is_token_of( T_STRING, $token ) ) {
					$info = $this->create_core_function_info( $token );
					$this->parse_doc_comment( $info );
					$this->release_doc_comment();
					$this->functions[] = $info;
					break;
				} elseif ( ncral_is_token_of( '(', $token ) ) {
					// The function is anonymous. Bail it.
					break;
				}
			}
		}

		public function grab_doc_comment( &$tokens ) {
			$token = current( $tokens );

			$this->doc_comment = $token[1];
		}

		public function release_doc_comment() {
			$this->doc_comment = '';
		}

		public function noop() {
			// Do nothing.
		}

		/**
		 * @param array $token
		 *
		 * @return NCRAL_Core_Function_Info
		 */
		private function create_core_function_info( array $token ) {
			return ( new NCRAL_Core_Function_Info() )
				->set_name( $token[1] )
				->set_file( $this->file_name )
				->set_line( $token[2] );
		}

		/**
		 * @param NCRAL_Core_Function_Info $info
		 */
		private function parse_doc_comment( NCRAL_Core_Function_Info $info ) {
			if ( ! $this->doc_comment ) {
				return;
			}

			$lines      = array_filter( array_map( 'trim', explode( "\n", $this->doc_comment ) ) );
			$since      = [];
			$deprecated = [];

			foreach ( $lines as $line ) {
				if ( preg_match( '/@([A-Za-z_\-]+)\s*(.*)/i', $line, $match ) ) {
					switch ( $match[1] ) {
						case 'since':
							$since[] = ncral_trim_version( $match[2] );
							break;

						case 'deprecated':
							$deprecated[] = ncral_trim_version( $match[2] );
							break;
					}
				}
			}

			if ( $since ) {
				sort( $since );
				$info->set_since( empty( $since[0] ) ? 'unknown' : $since[0] );
			}

			if ( $deprecated ) {
				sort( $deprecated );
				$info->set_deprecated( empty( $deprecated[0] ) ? 'unknown' : $deprecated[0] );
			}
		}
	}
}
