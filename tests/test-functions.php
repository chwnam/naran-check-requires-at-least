<?php

class NCRAL_Function_Test extends WP_UnitTestCase {
	public function test_ncral_scan_directory() {
		$files = [];

		foreach ( ncral_scan_directory( __DIR__ ) as $iter ) {
			/** @var string $iter */
			$files[] = ncral_strip_prefix( $iter, trailingslashit( __DIR__ ) );
		}

		sort( $files );

		$this->assertEquals(
			[
				'bootstrap.php',
				'files/token-get-all-test.php',
				'test-functions.php',
				'test-ncral-token-get-all.php',
				'test-sample.php',
			],
			$files
		);
	}

	public function test_ncral_get_core_function_data() {
		$output = ncral_get_core_function_data();

		$this->assertIsArray( $output );
		$this->assertTrue( count( $output ) > 0 );

		foreach ( $output as $item ) {
			$this->assertInstanceOf( NCRAL_Core_Function_Info::class, $item );
		}
	}
}
