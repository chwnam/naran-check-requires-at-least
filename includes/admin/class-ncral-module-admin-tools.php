<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NCRAL_Module_Admin_Tools' ) ) {
	class NCRAL_Module_Admin_Tools implements NCRAL_Admin_Module {
		use NCRAL_Hook_Impl;
		use NCRAL_Template_Impl;

		public function __construct() {
			$this
				->action( 'admin_enqueue_scripts', 'admin_scripts' )
				->action( 'admin_menu', 'add_menu' );
		}

		public function admin_scripts( $hook ) {
			if ( 'tools_page_ncral' === $hook ) {
				wp_enqueue_script( 'ncral-admin-tools' );
				wp_enqueue_style( 'ncral-admin-tools' );
			}
		}

		public function add_menu() {
			add_submenu_page(
				'tools.php',
				'Naran check requires at least',
				'NCRAL',
				'manage_options',
				'ncral',
				[ $this, 'output_menu' ]
			);
		}

		public function output_menu() {
			$reference  = ncral_get_core_reference();
			$ref_ver    = ncral_from_array( $reference, 'version', false );
			$ref_count  = count( ncral_from_array( $reference, 'data', [] ) );
			$ref_status = $ref_ver && $ref_count;

			$len     = strlen( WP_PLUGIN_DIR );
			$plugins = [];
			foreach ( wp_get_active_and_valid_plugins() as $plugin ) {
				$plugin_data = get_plugin_data( $plugin, false, true );
				$name        = ncral_from_array( $plugin_data, 'Name' );
				$rel         = substr( $plugin, $len + 1 );

				$plugins[ $rel ] = $name;
			}

			$plugin = ncral_from_array( $_GET, 'ncral_plugin' );
			if ( $plugin ) {
				$call_info = ncral_inspect_plugin( $plugin );
			} else {
				$call_info = [];
			}

			$min_version = array_reduce(
				$call_info,
				function ( $accum, NCRAL_Function_Call_Info $item ) {
					if ( empty( $accum ) ) {
						return $item->get_since();
					} else {
						return version_compare( $accum, $item->get_since(), '>' ) ? $accum : $item->get_since();
					}
				},
				''
			);

			$version_group = [];
			foreach ( $call_info as $info ) {
				$version_group[ $info->get_since() ][] = $info;
			}
			uksort(
				$version_group,
				function ( $a, $b ) {
					if ( version_compare( $a, $b, '=' ) ) {
						return 0;
					} else {
						return version_compare( $a, $b, '>' ) ? - 1 : 1;
					}
				}
			);

			$deprecated_functions = array_filter(
				$call_info,
				function ( NCRAL_Function_Call_Info $item ) {
					return ! empty( $item->get_deprecated() );
				}
			);

			$this->template(
				'admin-settings.php',
				[
					'ref_status'           => $ref_status,
					'ref_ver'              => $ref_ver,
					'ref_count'            => $ref_count,
					'plugins'              => $plugins,
					'plugin'               => $plugin,
					'min_version'          => $min_version,
					'version_group'        => $version_group,
					'deprecated_functions' => $deprecated_functions,
					'total_count'          => count( $call_info ),
				]
			);
		}
	}
}
