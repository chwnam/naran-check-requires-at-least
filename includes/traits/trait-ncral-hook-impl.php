<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! trait_exists( 'NCRAL_Hook_Impl' ) ) {
	trait NCRAL_Hook_Impl {
		protected function action( $hook, $callback, $priority = null, $accepted_args = 1 ) {
			$callback = $this->parse_callback( $callback );
			$priority = $this->parse_priority( $priority );
			if ( is_array( $hook ) ) {
				foreach ( $hook as $h ) {
					add_action( $h, $callback, $priority, $accepted_args );
				}
			} else {
				add_action( $hook, $callback, $priority, $accepted_args );
			}
			return $this;
		}

		protected function filter( $hook, $callback, $priority = null, $accepted_args = 1 ) {
			$callback = $this->parse_callback( $callback );
			$priority = $this->parse_priority( $priority );
			if ( is_array( $hook ) ) {
				foreach ( $hook as $h ) {
					add_filter( $h, $callback, $priority, $accepted_args );
				}
			} else {
				add_filter( $hook, $callback, $priority, $accepted_args );
			}
			return $this;
		}

		private function parse_callback( $callback ) {
			return method_exists( $this, $callback ) ? [ $this, $callback ] : $callback;
		}

		private function parse_priority( $priority ) {
			return is_null( $priority ) ? NCRAL_PRIORITY : $priority;
		}
	}
}
