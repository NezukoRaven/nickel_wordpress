<?php
use Elex\RequestAQuote\HelpAndSupport\HelpAndSupportController;
?>
<div class="eacmcm p-3">
	<div class="p-1 fw-bold">
		<p><?php esc_html_e( 'Before raising the ticket, we recommend you to go through our detailed', 'elex-request-a-quote' ); ?> <a href="https://elextensions.com/knowledge-base/how-to-set-up-elex-woocommerce-request-a-quote-plugin/" target="_blank"><?php esc_html_e( 'documentation.', 'elex-request-a-quote' ); ?></a></p>
		<p><?php esc_html_e( 'Or', 'elex-request-a-quote' ); ?></p>
		<p class="mb-0"><?php esc_html_e( 'To get in touch with one of our helpdesk representatives, please raise a support ticket on our website.', 'elex-request-a-quote' ); ?></p>
		<div class="text-danger fw-normal"><small><?php esc_html_e( '* Please don`t forget to attach your System Info File with the request for better support.', 'elex-request-a-quote' ); ?></small></div>

		<!-- <button class="btn btn-primary py-3 my-3">Raise a Ticket</button> -->

		<a href='https://support.elextensions.com/' target="_blank"><button type="button" class="btn btn-primary py-2 my-3" id="elex_support"><?php echo esc_html_e( 'Raise a ticket', 'elex-request-a-quote' ); ?></button></a>
		<div class="d-flex gap-3">

			<form action="<?php echo esc_url( admin_url( 'admin.php?page=helpandsupport&tab=ticket' ) ); ?>" method="post" enctype="multipart/form-data">
				<?php wp_nonce_field( 'action', 'system_info_nonce' ); ?>
				<input type="hidden" name="action" value="raq_download_system_info" />

				<div>
					<textarea hidden readonly="readonly" onclick="this.focus();this.select()" id="ssi-textarea" name="send-system-info-textarea-raq" title="<?php esc_html_e( 'To copy the System Info, click below then press Ctrl + C (PC) or Cmd + C (Mac).', 'elex-request-a-quote' ); ?>">
							<?php
							// $system_info = new Request_a_Quote();
							// echo esc_html($system_info->display());
							echo esc_html( HelpAndSupportController::display() );
							?>
							</textarea>
				</div>

				<p class="submit">
					<input type="submit" class="btn btn-outline-primary" value="<?php esc_html_e( 'Download System Info', 'elex-request-a-quote' ); ?>" />
				</p>
			</form>

		</div>

	</div>

</div>
