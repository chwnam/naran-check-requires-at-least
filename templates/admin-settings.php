<?php
/**
 * Template: admin-settings
 *
 * Context:
 *
 * @var bool                                      $ref_status           레퍼런스 상태 문자열.
 * @var string                                    $ref_ver              레퍼런스기 조사한 코어 버전.
 * @var string|int                                $ref_count            레퍼런스의 데이터 수.
 * @var array<string, string>                     $plugins              현재 사이트의 플러그인 목록.
 * @var string                                    $plugin               선택한 플러그인.
 * @var string                                    $min_version          선택한 플러그인을 지원하는 최소 코어 버전.
 * @var array<string, NCRAL_Function_Call_Info[]> $version_group        버전별 함수 호출 내역.
 * @var NCRAL_Function_Call_Info[]                $deprecated_functions 폐기된 함수 목록.
 * @var int                                       $total_count          함수 호출 총 갯수.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$render_function_info = function ( NCRAL_Function_Call_Info $info ) {
	$buffer = [];
	foreach ( $info->get_lines() as $file => $lines ) {
		$buffer[] = '<li>' . esc_html( trim( $file, '\//' ) . ':' . implode( ', ', $lines ) ) . '</li>';
	}
	sort( $buffer );
	?>
    <div class="function-info" style="display: none;">
        <p>Function: <strong><code><?php echo esc_html( $info->get_function() ); ?></code></strong></p>
        <p>Defined: <?php echo esc_html( $info->get_wp_core_file() . ':' . $info->get_wp_core_line() ); ?></p>
        <p>Called:</p>
        <ul><?php echo implode( PHP_EOL, $buffer ); ?></ul>
    </div>
	<?php
};

?>

    <div class="wrap ncral">
        <h1>Naran Check Requires At Least</h1>
        <hr class="wp-header-end">

        <h2>Core function reference status</h2>
        <div class="reference-status">
            <ul>
                <li>
                    <span class="label">Status:</span> <?php echo $ref_status ? 'OK' : 'Missing'; ?>
                </li>
                <li>
                    <span class="label">Version:</span> <?php echo esc_html( $ref_ver ); ?>
                </li>
                <li>
                    <span class="label">Count:</span> <?php echo intval( $ref_count ); ?>
                </li>
            </ul>
        </div>

		<?php if ( $ref_status ) : ?>
            <hr>
            <h2>Inspection</h2>
            <div class="inspection">
                <section>
                    <form action="" method="get">
                        <input type="hidden" name="page"
                               value="<?php echo esc_attr( ncral_from_array( $_GET, 'page' ) ); ?>">
                        <label for="select-a-plugin" class="screen-reader-text">Select a plugin</label>
                        <select id="select-a-plugin" name="ncral_plugin" autocomplete="off">
							<?php foreach ( $plugins as $path => $name ) : ?>
                                <option value="<?php echo esc_attr( $path ); ?>"
									<?php selected( $path, $plugin ); ?>>
									<?php echo esc_html( $name ); ?>
                                </option>
							<?php endforeach; ?>
                        </select>
                        <button type="submit" class="button button-secondary">Inspect</button>
                    </form>
                </section>
            </div>
		<?php endif; ?>

		<?php if ( ! $total_count && $plugin ) : ?>
            <hr>
            <h2>Results</h2>
            <div class="results">
                <p>No functions are used currently in this function.</p>
            </div>
		<?php endif; ?>

		<?php if ( $ref_status && $plugin && $min_version && $total_count ) : ?>
            <hr>
            <h2>Results</h2>
            <div class="results">
				<?php $plugin_name = ncral_from_array( $plugins, $plugin ); ?>
                <h3>Minimum WP Core version</h3>
                <section>
                    <p><strong>"<?php echo esc_html( $plugin_name ); ?>"</strong>
                        requires WordPress version at least
                        <strong><?php echo esc_html( $min_version ); ?></strong>.
                        You may consider to add the header below.
                    </p>
                    <pre>/**
 * Requires at least: <?php echo esc_html( $min_version ) . PHP_EOL; ?>
 */</pre>
                </section>

                <h3>
					<?php echo _n( 'Used Function', 'Used Functions', $total_count ); ?>
                    <span class="header-inline">[<a href="#" id="expand-used-functions">Expand All</a>]</span>
                    <span class="header-inline">[<a href="#" id="collapse-used-functions">Collapse All</a>]</span>
                </h3>
                <section class="function-info-wrap shrink">
					<?php foreach ( $version_group as $version => $group ): ?>
                        <h4>Version <?php echo esc_html( $version ? $version : '---' ); ?></h4>
						<?php foreach ( $group as $item ): ?>
							<?php $render_function_info( $item ); ?>
						<?php endforeach; ?>
					<?php endforeach; ?>
                </section>
                <div class="">Collapsed: all <?php echo count( $version_group ); ?> versions.</div>

                <h3>
					<?php echo _n( 'Deprecated Function', 'Deprecated Functions', count( $deprecated_functions ) ); ?>
                </h3>
                <section>
					<?php if ( ! empty( $deprecated_functions ) ) : ?>
						<?php foreach ( $deprecated_functions as $item ): ?>
							<?php $render_function_info( $item ); ?>
						<?php endforeach; ?>
					<?php else: ?>
                        <p>No deprecate functions are used.</p>
					<?php endif; ?>
                </section>
            </div>
            <a href="<?php echo esc_url( remove_query_arg( 'ncral_plugin' ) ); ?>">Clear the results</a>
		<?php endif; ?>
    </div>

<?php
