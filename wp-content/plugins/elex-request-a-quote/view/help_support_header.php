<h5 class="my-3 "><b><?php esc_html_e( 'Help & Support', 'elex-request-a-quote' ); ?></b></h5>
<div class="bg-primary bg-opacity-10 mb-3 elex-rqust-quote-help-support-navbar">
<ul>
<?php 
foreach ( $sub_tabs as $tab_data ) :
			
			$url = add_query_arg(
				array(
					'page' => $plugin_page,
					'tab'  => $tab_data['slug'],
				),
				admin_url( 'admin.php' )
			);
	?>
		<li class="mb-0 <?php echo $active_tab === $tab_data['slug'] ? 'active' : ''; ?>"><a href="<?php echo esc_url( $url ); ?>" class="nav-link  rounded-0 rounded-top text-nowrap">
					<?php echo esc_html_e( $tab_data['title'] ); ?>
				</a></li>
				<?php endforeach; ?>
	</ul>
</div>
