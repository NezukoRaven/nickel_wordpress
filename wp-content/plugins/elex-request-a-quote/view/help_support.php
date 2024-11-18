<div class="elex-rqst-quote-wrap">
	<!-- content -->
	<div class="elex-rqst-quote-content d-flex">
		<!-- main content -->
		<div class="elex-rqst-quote-main">
			<div class="p-2 pe-4">
			<img src="<?php echo esc_url( plugins_url( 'assets/images/top banner.png', dirname( __FILE__ ) ) ); ?>" alt="" class="w-100">
				<?php require __DIR__ . '/help_support_header.php'; ?>
				<?php
				 do_action( 'req_settings_tab_' . $active_tab );
				?>
			</div>
		</div>
	</div>
</div>
