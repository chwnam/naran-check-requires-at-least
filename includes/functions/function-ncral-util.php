<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'ncral_scan_directory' ) ) {
	/**
	 * @param $directory
	 *
	 * @return Generator
	 */
	function ncral_scan_directory( $directory ) {
		$iterator = new RegexIterator(
			new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $directory ) ),
			'/\.php$/',
			RecursiveRegexIterator::MATCH
		);

		foreach ( $iterator as $iter ) {
			yield $iter->getPathname();
		}
	}
}


if ( ! function_exists( 'ncral_from_array' ) ) {
	function ncral_from_array( $array_or_object, $key, $default = '' ) {
		if ( is_array( $array_or_object ) ) {
			return isset( $array_or_object[ $key ] ) ? $array_or_object[ $key ] : $default;
		} elseif ( is_object( $array_or_object ) ) {
			return isset( $array_or_object->{$key} ) ? $array_or_object->{$key} : $default;
		} else {
			return $default;
		}
	}
}


if ( ! function_exists( 'ncral_str_starts_with' ) ) {
	/**
	 * @param string $haystack
	 * @param string $needle
	 *
	 * @return bool
	 */
	function ncral_str_starts_with( $haystack, $needle ) {
		return $needle === '' || strpos( $haystack, $needle ) === 0;
	}
}


if ( ! function_exists( 'ncral_get_core_function_data' ) ) {
	/**
	 * Get WordPress core function information.
	 *
	 * @return NCRAL_Core_Function_Info[]
	 */
	function ncral_get_core_function_data( $debug = false ) {
		$class = apply_filters( 'ncral_core_function_data_collector', 'NCRAL_Token_Get_All_Core_Function_Data_Collector' );

		/** @var NCRAL_Core_Function_Data_Collector $collector */
		$collector = new $class( $debug );
		$result    = [];

		foreach ( ncral_scan_directory( ABSPATH ) as $path ) {
			if ( ! ncral_str_starts_with( $path, WP_CONTENT_DIR ) ) {
				$collector->scan( $path, ABSPATH );
				$result[] = $collector->get_result();
			}
		}

		return array_merge( ...$result );
	}
}


if ( ! function_exists( 'ncral_get_core_reference' ) ) {
	/**
	 * Get WordPress core functions information and save as site option.
	 *
	 * @param bool $force_recreate
	 *
	 * @return array
	 */
	function ncral_get_core_reference( $force_recreate = false ) {
		$core_reference = get_site_option( 'ncral_core_reference' );
		$wp_version     = get_bloginfo( 'version' );
		$version        = ncral_from_array( $core_reference, 'version', false );;

		if ( false === $core_reference || $force_recreate || ! $version || version_compare( $wp_version, $version, '!=' ) ) {
			$data = [];
			foreach ( ncral_get_core_function_data() as $item ) {
				$data[ $item->get_name() ] = $item->to_array();
			}
			ksort( $data );

			$core_reference = [
				'version' => $wp_version,
				'data'    => $data,
			];

			add_site_option( 'ncral_core_reference', $core_reference );
		}

		return $core_reference;
	}
}


if ( ! function_exists( 'ncral_destroy_core_reference' ) ) {
	function ncral_destroy_core_reference() {
		delete_site_option( 'ncral_core_reference' );
	}
}


if ( ! function_exists( 'ncral_inspect_plugin' ) ) {
	/**
	 * @param $plugin
	 *
	 * @return NCRAL_Function_Call_Info[]
	 */
	function ncral_inspect_plugin( $plugin ) {
		$class = apply_filters( 'ncral_function_checker', 'NCRAL_Token_Get_All_Function_Checker' );

		$reference = ncral_get_core_reference();
		$database  = ncral_from_array( $reference, 'data' );

		/** @var NCRAL_Function_Checker $checker */
		$checker = new $class( $database );

		$plugin_full_path = WP_PLUGIN_DIR . '/' . $plugin;
		$prefix           = dirname( $plugin_full_path );

		if ( dirname( $plugin ) === WP_PLUGIN_DIR ) {
			$checker->scan( $plugin_full_path, $prefix );
		} else {
			foreach ( ncral_scan_directory( dirname( $plugin_full_path ) ) as $file ) {
				$checker->scan( $file, $prefix );
			}
		}

		return $checker->get_result();
	}
}
