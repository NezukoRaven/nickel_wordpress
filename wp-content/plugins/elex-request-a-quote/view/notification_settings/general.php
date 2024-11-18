<?php
$email_address = '';
if ( ! empty( $settings['email_address'] ) ) {
	$email_address = implode( ', ', $settings['email_address'] );
}
?>
<div class="flex-fill">
	<form method="POST">
		<?php wp_nonce_field( 'save_settings', 'req_settings_nonce' ); ?>
		<div class="row ">
			<div class="col-lg-12">
				<!-- Notifications Email Address -->
				<div class="row mb-3 align-items-center">
					<div class="col-lg-5 col-md-6">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Notifications Email Address', 'elex-request-a-quote' ); ?></h6>
							
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
							title="<?php esc_html_e('Enter the email addresses to receive a notification when the customer places a quote request. By default, the administrator email id is taken to get a notification. Multiple email ids should be separated by commas.', 'elex-request-a-quote'); ?>"
							>
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)" fill="#f5f5f5" stroke="#000" stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20" font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
							</div>
						</div>
					</div>
					<div class="col-lg-5 col-md-6">
						<input name="email_address" value="<?php echo esc_html_e( $email_address ); ?> " type="text" class="form-control" placeholder="Eliza.Gordon@Mail.Com">
					</div>
				</div>

				<!-- Notifications order status -->
				<div class="row mb-3 align-items-center">
					<div class="col-lg-5 col-md-6">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Select Order Statuses To Notify Customers', 'elex-request-a-quote' ); ?></h6>
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
							title="<?php esc_html_e('Select the order statuses for which customers should receive an email notification.', 'elex-request-a-quote'); ?>"
							>


								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)" fill="#f5f5f5" stroke="#000" stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20" font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
							</div>
						</div>
					</div>
					<div class="col-lg-5 col-md-6">

						<select name="order_status[]" style="width:100% !important" class="req_order_status  form-select border-2 border-secondary " multiple="true">
							<option value="quote-requested" <?php echo in_array( 'quote-requested', $settings['order_status'], true ) ? 'selected' : ''; ?>>
								<?php
								esc_html_e(
									'Quote Requested
                                        ',
									'elex-request-a-quote'
								);
								?>
							</option>
							<option value="quote-rejected" <?php echo  in_array( 'quote-rejected', $settings['order_status'], true ) ? 'selected' : ''; ?>><?php esc_html_e( 'Quote Rejected', 'elex-request-a-quote' ); ?></option>
							<option value="quote-approved" <?php echo in_array( 'quote-approved', $settings['order_status'], true ) ? 'selected' : ''; ?>>
								<?php
								esc_html_e(
									'Quote Approved
                                        ',
									'elex-request-a-quote'
								);
								?>
							</option>

						</select>
					</div>
				</div>

				<!-- Debug Log -->
				<div class="row mb-3 align-items-center">
					<div class="col-lg-5 col-md-6">
						<div class="d-flex justify-content-between align-items-center">
						<h6 class="mb-0"><?php esc_html_e( 'Debug Log', 'elex-request-a-quote' ); ?></h6>
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
							title="<?php esc_html_e("Find email, sms and google chat logs here (wp-content\uploads\wc-logs).", 'elex-request-a-quote'); ?>"
							>
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)" fill="#f5f5f5" stroke="#000" stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20" font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
							</div>
						</div>
					</div>
					<div class="col-lg-5 col-md-6">
						<label class="elex-switch-btn">
							<input class="debug_enabled" type="checkbox" name="debug_log" <?php echo  esc_html_e( ! empty( $settings['debug_log'] ) ? 'checked' : '' ); ?>>
							<div class="elex-switch-icon round"></div>
						</label>
					</div>
				</div>

				<button type="submit" name="submit" class="btn btn-primary"><?php esc_html_e( 'Save Changes', 'elex-request-a-quote' ); ?></button>
			</div>
		</div>
	</form>
</div>
