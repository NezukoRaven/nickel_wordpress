<div class="flex-fill">
	<form method="POST">
		<?php wp_nonce_field( 'save_settings', 'req_settings_nonce' ); ?>


		<div class="row ">
			<div class="col-lg-12">
				<!--Google chat Notification-->
				<div class="row align-items-center elex-rqust-quote-check-sec ">
					<div class="col-12 ">
						<div class="row mb-3">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center">
									<h6 class="mb-0"><?php esc_html_e( 'Google Chat Notification', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
									
									title="<?php esc_html_e('Enable to receive Google Chat notification when the customer makes a quote request.', 'elex-request-a-quote'); ?>"
									>
										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 " viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 " transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 " stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) " font-size="20 " font-family="Roboto-Bold, Roboto " font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<div class="d-flex gap-3 align-items-center">
									<label class="elex-switch-btn ">
										<input type="checkbox" id="is_chat_enabled" name="enabled" type="checkbox" <?php echo esc_html_e( isset( $settings['enabled'] ) && true === $settings['enabled'] ? 'checked' : '' ); ?> class="elex-rqust-quote-check-sec-input-chat">
										<div class="elex-switch-icon round "></div>
									</label>

								</div>

							</div>
						</div>


						<div class="elex-rqust-quote-check-content-chat">
							<!-- Webhook URL -->
							<div class="row mb-3 align-items-center">
								<div class=" col-lg-5 col-md-6 ">
									<div class="d-flex justify-content-between align-items-center gap-2">
										<h6 class="mb-0"><?php esc_html_e( 'Webhook URL', 'elex-request-a-quote' ); ?></h6>
										<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
										title="<?php esc_html_e('Enter the Google Chat Webhook URL. You can find this URL on your google chat window.', 'elex-request-a-quote'); ?>"
										>

											<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 " viewBox="0 0 26 26 ">
												<g id="tt " transform="translate(-384 -226) ">
													<g id="Ellipse_1 " data-name="Ellipse 1 " transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 " stroke-width="1 ">
														<circle cx="13 " cy="13 " r="13 " stroke="none " />
														<circle cx="13 " cy="13 " r="12.5 " fill="none " />
													</g>
													<text id="_ " data-name="? " transform="translate(392 247) " font-size="20 " font-family="Roboto-Bold, Roboto " font-weight="700 ">
														<tspan x="0 " y="0 ">?</tspan>
													</text>
												</g>
											</svg>
										</div>
									</div>
								</div>
								<div class=" col-lg-7 col-md-6 ">
									<div class="row">
										<div class="col-xl-6 col-lg-9 col-md-12">
											<input name="webhook_url" type="text" class="form-control" value="<?php echo esc_html_e( isset( $settings['webhook_url'] ) && ! empty( $settings['webhook_url'] ) ? $settings['webhook_url'] : '' ); ?>">
										</div>
									</div>


								</div>
							</div>
						</div>
					</div>
				</div>

				<button type="submit" name="submit" class="btn btn-primary"><?php esc_html_e( 'Save Changes', 'elex-request-a-quote' ); ?></button>
			</div>
		</div>
	</form>
</div>
