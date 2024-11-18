<div class="elex-rqst-quote-wrap">
	<!-- content -->
	<div class="elex-rqst-quote-content d-flex">
		<!-- main content -->
		<div class="elex-rqst-quote-main">
			<img src="<?php echo esc_url( plugins_url( 'assets/images/top banner.png', dirname( __FILE__ ) ) ); ?>" alt="" class="w-100">
			<div class="p-2 pe-4">
				<?php require __DIR__ . '/header.php'; ?>
				<?php
				/**
				 * Append active tab to the url
				 *
				 * @since 1.0.0
				 */
				 do_action( 'req_settings_tab_' . $active_tab );
				?>
			</div>
		</div>
	</div>
</div>
