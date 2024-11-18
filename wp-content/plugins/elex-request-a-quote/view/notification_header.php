<div class="p-2 pe-4">
	<div class="d-flex gap-4 py-4">
		<div class="elex-rqust-quote-noti-settings-nav">
		<div class="border border-primary rounded-3 p-2">
			<div class="nav nav-pills flex-column text-center gap-3">
				<?php foreach ( $sub_tabs as $subtab ) :
				
				$url = add_query_arg(
					array(
						'page'   => $plugin_page,
						'tab'    => 'notification',
						'subtab' => $subtab['slug'],
					),
					admin_url( 'admin.php' )
				);
					?>
				<a href="<?php echo esc_url( $url ); ?>" class="btn btn-sm nav-link <?php echo $active_subtab === $subtab['slug'] ? 'active' : ''; ?>  ">
					<?php echo esc_html_e( $subtab['title'] ); ?>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
