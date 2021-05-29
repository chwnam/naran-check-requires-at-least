<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'ncral_is_parser_token' ) ) {
	/**
	 * @param array|string $token
	 *
	 * @return bool
	 */
	function ncral_is_parser_token( $token ) {
		return is_array( $token ) && 3 === count( $token );
	}
}


if ( ! function_exists( 'ncral_is_token_of' ) ) {
	/**
	 * @param int|string|array $compare To compare.
	 * @param array|string     $token
	 */
	function ncral_is_token_of( $compare, $token ) {
		if ( ncral_is_parser_token( $token ) ) {
			$tok = $token[0];
		} else {
			$tok = $token;
		}
		return is_array( $compare ) ? in_array( $tok, $compare, true ) : $tok === $compare;
	}
}


if ( ! function_exists( 'ncral_trim_version' ) ) {
	/**
	 * @param string $input Maybe version string. Not a strict, complex semantic version number like X-Y-Z-beta.W,
	 *                      because this should be an official release version number.
	 *                      Simply care about some easy cases.
	 *
	 * @return string
	 */
	function ncral_trim_version( $input ) {
		$output = '';

		if ( $input ) {
			if ( preg_match( '/^(\d+\.\d+(.\d+)?)/', $input, $match ) ) {
				$output = $match[1];
			} elseif ( preg_match( '/MU \((.+)\)/', $input, $match ) ) {
				$output = $match[1];
			}
		}

		return $output;
	}
}


if ( ! function_exists( 'ncral_strip_prefix' ) ) {
	/**
	 * @param string $string
	 * @param string $prefix
	 *
	 * @return string
	 */
	function ncral_strip_prefix( $string, $prefix ) {
		$prefix_len = strlen( $prefix );
		$string_len = strlen( $string );

		return $string_len > $prefix_len ? substr( $string, $prefix_len ) : $string;
	}
}
