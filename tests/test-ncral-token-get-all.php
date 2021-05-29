<?php

class NCRAL_Token_Get_All_Test extends WP_UnitTestCase {
	public function test_core_function_data_collector_scan() {
		$collector = new NCRAL_Token_Get_All_Core_Function_Data_Collector();
		$collector->scan( __DIR__ . '/files/token-get-all-test.php', __DIR__ . '/files/' );

		/** @var NCRAL_Core_Function_Info[] $result */
		$result = $collector->get_result();

		$this->assertCount( 3, $result );

		$this->assertEquals( 'func_foo', $result[0]->get_name() );
		$this->assertEquals( 'token-get-all-test.php', $result[0]->get_file() );
		$this->assertEquals( 34, $result[0]->get_line() );
		$this->assertEquals( '1.1.0', $result[0]->get_since() );
		$this->assertEquals( '', $result[0]->get_deprecated() );

		$this->assertEquals( 'func_bar', $result[1]->get_name() );
		$this->assertEquals( 'token-get-all-test.php', $result[1]->get_file() );
		$this->assertEquals( 66, $result[1]->get_line() );
		$this->assertEquals( '1.0.0', $result[1]->get_since() );
		$this->assertEquals( '1.1.1', $result[1]->get_deprecated() );

		$this->assertEquals( 'func_baz', $result[2]->get_name() );
		$this->assertEquals( 'token-get-all-test.php', $result[2]->get_file() );
		$this->assertEquals( 75, $result[2]->get_line() );
		$this->assertEquals( '1.1.0', $result[2]->get_since() );
		$this->assertEquals( 'unknown', $result[2]->get_deprecated() );
	}

	public function test_function_checker_scan() {
		$collector = new NCRAL_Token_Get_All_Core_Function_Data_Collector();
		$collector->scan( __DIR__ . '/files/token-get-all-test.php', __DIR__ . '/files/' );
		$database = $collector->get_result();

		$checker = new NCRAL_Token_Get_All_Function_Checker( $database );
		$checker->scan( __DIR__ . '/files/token-get-all-test.php', __DIR__ . '/files/' );

		/** @var NCRAL_Function_Call_Info[] $result */
		$result = array_values( $checker->get_result() );

		$this->assertIsArray( $result );
		$this->assertCount( 3, $result );

		// Order: func_bar, func_baz, func_foo.
		$this->assertEquals( 'func_bar', $result[0]->get_function() );
		$this->assertEquals( 'token-get-all-test.php', $result[0]->get_wp_core_file() );
		$this->assertEquals( 66, $result[0]->get_wp_core_line() );
		$this->assertEquals( '1.0.0', $result[0]->get_since() );
		$this->assertEquals( '1.1.1', $result[0]->get_deprecated() );
		$this->assertEquals( [ 'token-get-all-test.php' => [ 24, 49 ] ], $result[0]->get_lines() );

		$this->assertEquals( 'func_baz', $result[1]->get_function() );
		$this->assertEquals( 'token-get-all-test.php', $result[1]->get_wp_core_file() );
		$this->assertEquals( 75, $result[1]->get_wp_core_line() );
		$this->assertEquals( '1.1.0', $result[1]->get_since() );
		$this->assertEquals( 'unknown', $result[1]->get_deprecated() );
		$this->assertEquals( [ 'token-get-all-test.php' => [ 54, 55 ] ], $result[1]->get_lines() );

		$this->assertEquals( 'func_foo', $result[2]->get_function() );
		$this->assertEquals( 'token-get-all-test.php', $result[2]->get_wp_core_file() );
		$this->assertEquals( 34, $result[2]->get_wp_core_line() );
		$this->assertEquals( '1.1.0', $result[2]->get_since() );
		$this->assertEquals( '', $result[2]->get_deprecated() );
		$this->assertEquals( [ 'token-get-all-test.php' => [ 18, 53 ] ], $result[2]->get_lines() );
	}
}
