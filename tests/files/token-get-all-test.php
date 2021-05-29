<?php

/**
 * Class Token_Get_All_Test_1
 */
class Token_Get_All_Test_1 {
	public function test_function_01() {
		$foo = 'a';
		$bar = 'foo';
		$baz = 'bar';

		$a    = 1;
		$$foo += 1; // $a += 1;

		echo "${bar}\n"; // T_STRING_VARNAME
		echo "{${$baz}}\n";

		$f = func_foo();
		call_user_func( $f );
	}

	public function test_function_02() {
		$foo = function () {
			func_bar();
		};
	}
}

/**
 * @return Closure
 * @since  1.1.0
 *
 */
function func_foo() {
	$func = function () {
		return 'foo';
	};

	$bar = $func;

	return $func;
}

/**
 * Class Token_Get_All_Test_2
 */
class Token_Get_All_Test_2 {
	public function test_function_01() {
		func_bar();
	}

	public function test_function_02() {
		call_user_func( func_foo() );
		func_baz();
		func_baz();
	}
}

/**
 * @since      1.1.0 baz
 * @since      1.0.2 bar
 * @since      1.0.1 foo
 * @since      1.0.0
 * @deprecated 1.1.1
 */
function func_bar() {
}

/**
 * @since 1.1.0 introduced
 * @since 1.1.1 foo
 * @since 1.1.2 bar
 * @deprecated
 */
function func_baz() {
}
