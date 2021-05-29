<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! trait_exists( 'CLASSNAME' ) ) {
	trait NCRAL_Template_Impl {
		protected function template( $tmpl_name, array $context = [], $__echo__ = true ) {
			$tmpl_name = trim( $tmpl_name, '\\/' );

			$paths = [
				STYLESHEETPATH . "/ncral/{$tmpl_name}",
				TEMPLATEPATH . "/ncral/{$tmpl_name}",
				dirname( NCRAL_MAIN ) . "/templates/{$tmpl_name}",
			];

			$__located__ = false;

			foreach ( apply_filters( 'ncral_template_paths', $paths ) as $path ) {
				if ( file_exists( $path ) && is_readable( $path ) ) {
					$__located__ = $path;
					break;
				}
			}

			if ( ! $__located__ ) {
				return '';
			}

			if ( ! $__echo__ ) {
				ob_start();
			}

			if ( ! empty( $context ) ) {
				extract( $context, EXTR_SKIP );
			}

			unset( $array, $tmpl_name, $path, $paths );

			/** @noinspection PhpIncludeInspection */
			include $__located__;

			return $__echo__ ? '' : ob_get_clean();
		}
	}
}