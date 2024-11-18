<form method="POST" enctype="multipart/form-data">
	<?php 
	wp_nonce_field( 'save_settings', 'req_settings_nonce' ); ?>
	<div class="p-2 pe-4">
		<div class="pt-3">
			<div class="bg-warning bg-opacity-10 p-2  elex-rqst-quote-warning">
				<small style="font-weight: 500;">
					<?php 
			esc_html_e(
				'You can configure your email and sms/chat template here,
                Make use of placeholders to
                fill the fields dynamically.',
				'elex-request-a-quote'
			); 
					?>
					<br>
					<?php 
				esc_html_e(
					'Please note that placeholders can be applied only to
                the
                email and chat/sms body.',
					'elex-request-a-quote'
				); 
					?>
					<br>
					<?php 
				esc_html_e(
					'Also note, the @payment_link placeholder can only be
                applied to
                the email body sent after the quote is approved. You can also include HTML tags and
                inline styles in the email body but the same wont be applicable to the chat/sms
                body.',
					'elex-request-a-quote'
				); 
					?>
				</small>
			</div>
		</div>
		<div class="p-3">

			<div class="row">
				<!-- left section -->
				<div class="col-lg-7">

					<div class="row mb-3">
						<div class="col-lg-5 col-md-6">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e( 'Use Predefined Email Templates', 'elex-request-a-quote' ); ?>
								<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
							</h6>
								<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
									title="<?php esc_html_e('Enable this option to use predefined email templates within the plugin. You can configure the email content below.' , 'elex-request-a-quote'); ?>">

									<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
										viewBox="0 0 26 26 ">
										<g id="tt " transform="translate(-384 -226) ">
											<g id="Ellipse_1 " data-name="Ellipse 1 " transform="translate(384 226) "
												fill="#f5f5f5 " stroke="#000 " stroke-width="1 ">
												<circle cx="13 " cy="13 " r="13 " stroke="none " />
												<circle cx="13 " cy="13 " r="12.5 " fill="none " />
											</g>
											<text id="_ " data-name="? " transform="translate(392 247) " font-size="20 "
												font-family="Roboto-Bold, Roboto " font-weight="700 ">
												<tspan x="0 " y="0 ">?</tspan>
											</text>
										</g>
									</svg>
								</div>
							</div>
						</div>
						<div class="col-lg-7 col-md-6">
							<div class="d-flex gap-3 align-items-center">
								<label class="elex-switch-btn ">
									<input
										<?php echo  esc_html_e( true === $settings['predefined_template'] ? 'checked' : '' ); ?>
										name="predefined_template" type="checkbox"
										class="elex-rqust-quote-email-template-input">
									<div class="elex-switch-icon round "></div>
								</label>
							</div>
						</div>
					</div>

					<!-- Email Templates -->
					<div class="  elex-rqst-quote-email-template-container">
						<h5><?php esc_html_e( 'Email Template', 'elex-request-a-quote' ); ?></h5>
						<div class=" position-relative elex-raq-basic-email-template d-flex flex-wrap gap-3 p-2 mb-3 overflow-auto">
							<!-- Squared Logo Template -->
							<div class="elex-rqst-quote-email-template">
								<div class="elex-rqst-quote-email-img-container">
									<img src="<?php echo esc_url( plugins_url( 'assets/images/temp 1.png', dirname( __DIR__ ) ) ); ?>"
										alt="">
									<div class="mb-3 text-center">
										<input value="2"
											<?php echo esc_html_e( ( 2 === (int) $settings['template_id'] ? 'checked' : '' ) ); ?>
											type="radio" id="squared_logo_template_radio" name="template_id">
										<label for="squared_logo_template_radio" class="raq-fs">Squared Logo
											Template</label>
									</div>
								</div>
								<button disabled  class="btn btn-outline-primary elex-rqst-email-preview-open-btn">
									<i class="fa-regular fa-eye"></i>
									<?php esc_html_e( 'Preview Template', 'elex-request-a-quote' ); ?>
								</button>

								
							</div>

							<!-- Rectangle Logo Template -->
							<div class="elex-rqst-quote-email-template">
								<div class="elex-rqst-quote-email-img-container">
									<img src="<?php echo esc_url( plugins_url( 'assets/images/image temp.png', dirname( __DIR__ ) ) ); ?>"
										alt="">
									<div class="mb-3 text-center">
										<input value="3"
											<?php echo esc_html_e( ( 3 === (int) $settings['template_id'] ? 'checked' : '' ) ); ?>
											type="radio" id="rectangle_logo_template_radio" name="template_id">
										<label for="rectangle_logo_template_radio" class="raq-fs">
										<?php 
										esc_html_e(
											'Rectangle Logo
											Template',
											'elex-request-a-quote'
										); 
										?>
										</label>
									</div>
								</div>
								<button disabled  class="btn btn-outline-primary elex-rqst-email-preview-open-btn">
									<i class="fa-regular fa-eye"></i><?php esc_html_e( 'Preview Template', 'elex-request-a-quote' ); ?>
									
								</button>


							</div>

							<!-- Non Logo Template -->
							<div class="elex-rqst-quote-email-template">
								<div class="elex-rqst-quote-email-img-container">
									<img src="<?php echo esc_url( plugins_url( 'assets/images/temp 3.png', dirname( __DIR__ ) ) ); ?>"
										alt="">
									<div class="mb-3 text-center">
										<input value="1"
											<?php echo esc_html_e( ( 1 === (int) $settings['template_id'] ? 'checked' : '' ) ); ?>
											type="radio" id="non_logo_template_radio" name="template_id">
										<label for="non_logo_template_radio" class="raq-fs"><?php esc_html_e( 'Non Logo Template', 'elex-request-a-quote' ); ?></label>
									</div>
								</div>
								<button disabled class="btn btn-outline-primary elex-rqst-email-preview-open-btn">
									<i class="fa-regular fa-eye"></i><?php esc_html_e( 'Preview Template', 'elex-request-a-quote' ); ?>
									
								</button>


								
							</div>

						</div>

						<div class="row mb-3">
							<div class="col-lg-5 col-md-6">
								<h6><?php esc_html_e( 'Company Logo', 'elex-request-a-quote' ); ?>
								<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
							</h6>
							</div>
							<div class="col-lg-7 col-md-6">
								<div class="position-relative">
								<input id="i_file" type="file" accept="image/*" name="company_logo"
											class="invisible company_logo position-absolute h-100 w-100 top-0 start-0 opacity-0">
								 <button disabled type="button" id=""
										class=" <?php isset( $settings['company_logo'] ) && ! empty( $settings['company_logo'] ) ? esc_html_e( 'd-none' ) : ''; ?> upload_logo btn btn-primary mb-1 position-relative"><?php esc_html_e( 'Upload Image', 'elex-request-a-quote' ); ?>
										
									</button>
								

									<div id="elex-dp-import-file-display" class="">
																<div class="gap-2  d-flex align-items-center mb-2">
																	<span class="elex-dp-import-file-display-title text-primary"><?php echo esc_html( isset( $settings['company_logo'] ) ? $settings['company_logo'] : 'sample_image.png' ); ?></span>
																	<div id="i_remove" class=" <?php isset( $settings['company_logo'] ) && ! empty( $settings['company_logo'] ) ? '' : esc_html_e( 'd-none' ); ?> btn btn-sm btn-white rounded-circle primary-hover  elex-ship-upload-file-remove" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-outline-primary" data-bs-placement="bottom" title="" data-bs-original-title="Cancel">
																		<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 320 512">
																			<path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"></path>
																		</svg>
																	</div>
																</div>
															</div>
							

								</div>
								<div class="text-secondary raq-fxs">
									<?php esc_html_e( 'upload png or jpeg file', 'elex-request-a-quote' ); ?></div>
							</div>
						</div>

					</div>

					<!-- terms and condition -->
					<div class="row mb-3">
						<div class="col-lg-5 col-md-6">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e( 'Terms and Condition', 'elex-request-a-quote' ); ?></h6>
								<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
									title="<?php esc_html_e('Enable this option if you wish to include the terms and conditions in the email template.' , 'elex-request-a-quote'); ?>">

									<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
										viewBox="0 0 26 26 ">
										<g id="tt " transform="translate(-384 -226) ">
											<g id="Ellipse_1 " data-name="Ellipse 1 " transform="translate(384 226) "
												fill="#f5f5f5 " stroke="#000 " stroke-width="1 ">
												<circle cx="13 " cy="13 " r="13 " stroke="none " />
												<circle cx="13 " cy="13 " r="12.5 " fill="none " />
											</g>
											<text id="_ " data-name="? " transform="translate(392 247) " font-size="20 "
												font-family="Roboto-Bold, Roboto " font-weight="700 ">
												<tspan x="0 " y="0 ">?</tspan>
											</text>
										</g>
									</svg>
								</div>
							</div>
						</div>
						<div class="col-lg-7 col-md-6">
							<label class="elex-switch-btn">
								<input
								 <?php echo esc_html( isset( $settings['is_terms_enabled'] ) && true === $settings['is_terms_enabled'] ? 'checked' : '' ); ?>
									name="is_terms_enabled" type="checkbox" class="elex-rqst-terms-toggle">
								<div class="elex-switch-icon round"></div>
							</label>
						</div>
					</div>

					<div class="elex-rqst-terms">
						<div class="row align-items-center mb-3">
							<div class="col-lg-5 col-md-6">
								<h6 class="mb-0"><?php esc_html_e( 'Terms and Condition Content', 'elex-request-a-quote' ); ?>
								</h6>
							</div>
							<div class="col-lg-7 col-md-6">
								<input
									value="<?php esc_html_e( ! empty( $settings['terms_conditions'] ) ? $settings['terms_conditions'] : '' ); ?>"
									name="terms_conditions" type="text" class="form-control">
							</div>
						</div>

					</div>

					<!-- Quote Request Email Template: Sent to the Site Admiistrator -->
					<div class="pb-3">
						<h5 class="mb-3">
							<?php esc_html_e( 'Quote Request Email Template: Sent to the Site Administrator', 'elex-request-a-quote' ); ?>
						</h5>

						<!-- email subjects -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Subject', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the subject for the email sent to the administrator when a quote is received.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>

							<div class=" col-lg-7 col-md-6 ">
								<input name="quote_requested_mail_subject_to_admin"
									value="<?php echo esc_html_e( ! empty( $settings['sent_to_admin']['quote_requested_email_template']['subject'] ) ? $settings['sent_to_admin']['quote_requested_email_template']['subject'] : 'Quote Request' ); ?>"
									type="text" class="form-control">
							</div>
						</div>

						<!-- Email Heading -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Heading', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the heading for the email sent to the administrator when a quote is received.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<input name="quote_requested_mail_heading_to_admin"
									value="<?php echo esc_html_e( ! empty( $settings['sent_to_admin']['quote_requested_email_template']['heading'] ) ? $settings['sent_to_admin']['quote_requested_email_template']['heading'] : 'Your Quote Request has been received.' ); ?>"
									type="text" class="form-control">
							</div>
						</div>


						<!-- Email Body -->
						<div class="row mb-3 email-body ">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Body', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the body for the email sent to the administrator when a quote is received.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<textarea name="quote_requested_mail_body_to_admin" class="form-control"
									rows="3"><?php echo wp_kses_post( ! empty( $settings['sent_to_admin']['quote_requested_email_template']['body'] ) ? $settings['sent_to_admin']['quote_requested_email_template']['body'] : '' ); ?></textarea>
							</div>
						</div>

					</div>


					


					<!-- Quote Requested Email Template: Sent to the Customer -->
					<div class="pb-3">
						<h5 class="mb-3">
							<?php esc_html_e( 'Quote Requested Email Template: Sent to the Customer', 'elex-request-a-quote' ); ?>
						</h5>

						<!-- email subject -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Subject', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the subject for the email sent to the customer when a quote is requested.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<input type="text" name="quote_requested_mail_sub_to_cust"
									value=" <?php echo esc_html_e( ! empty( $settings['sent_to_customer']['quote_requested_email_template']['subject'] ) ? $settings['sent_to_customer']['quote_requested_email_template']['subject'] : 'Quote Received' ); ?>"
									class="form-control">
							</div>
						</div>

						<!-- Email Heading -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Heading', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the heading for the email sent to the customer when a quote is requested.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<input name="quote_requested_mail_heading_to_cust"
									value=" <?php echo esc_html_e( ! empty( $settings['sent_to_customer']['quote_requested_email_template']['heading'] ) ? $settings['sent_to_customer']['quote_requested_email_template']['heading'] : 'Your Quote Request has been received.' ); ?>"
									type="text" class="form-control">
							</div>
						</div>


						<!-- Email Body -->
						<div class="row mb-3 email-body ">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Body', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the body for the email sent to the customer when a quote is requested.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<textarea name="quote_requested_mail_body_to_cust" class="form-control"
									rows="3"><?php echo wp_kses_post( ! empty( $settings['sent_to_customer']['quote_requested_email_template']['body'] ) ? $settings['sent_to_customer']['quote_requested_email_template']['body'] : '' ); ?></textarea>
							</div>
						</div>
					</div>

					<!-- Quote Approved Email Template: Sent to the Customer -->
					<div class="pb-3">
						<h5 class="mb-3">
							<?php esc_html_e( 'Quote Approved Email Template: Sent to the Customer', 'elex-request-a-quote' ); ?>
						</h5>

						<!-- email subject -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Subject', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the subject for the email sent to the customer when a quote is accepted by the administrator.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<input type="text" name="quote_approved_mail_subject_to_cust"
									value="<?php echo esc_html_e( ! empty( $settings['sent_to_customer']['quote_approved_email_template']['subject'] ) ? $settings['sent_to_customer']['quote_approved_email_template']['subject'] : 'Quote Approved' ); ?>"
									class="form-control">
							</div>
						</div>

						<!-- Email Heading -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Heading', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the heading for the email sent to the customer after the quote request is accepted by the administrator.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<input type="text" name="quote_approved_mail_heading_to_cust"
									value="<?php echo esc_html_e( ! empty( $settings['sent_to_customer']['quote_approved_email_template']['heading'] ) ? $settings['sent_to_customer']['quote_approved_email_template']['heading'] : 'Your Quote Request has been approved.' ); ?>"
									class="form-control">
							</div>
						</div>


						<!-- Email Body -->
						<div class="row mb-3 email-body">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Body', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the body for the email sent to the customer after the quote request is accepted by the administrator.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<textarea name="quote_approved_mail_body_to_cust" class="form-control"
									rows="3"><?php echo wp_kses_post( ! empty( $settings['sent_to_customer']['quote_approved_email_template']['body'] ) ? $settings['sent_to_customer']['quote_approved_email_template']['body'] : '' ); ?></textarea>
							</div>
						</div>
					</div>


					<!-- Quote Rejected Email Template: Sent to the Customer -->
					<div class="pb-3 ">
						<h5 class="mb-3">
							<?php esc_html_e( 'Quote Rejected Email Template: Sent to the Customer', 'elex-request-a-quote' ); ?>
						</h5>

						<!-- email subject -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Subject', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the subject for the email sent to the customer when a quote is rejected by the administrator.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<input name="quote_rejected_mail_subject_to_cust"
									value="<?php echo esc_html_e( ! empty( $settings['sent_to_customer']['quote_rejected_email_template']['subject'] ) ? $settings['sent_to_customer']['quote_rejected_email_template']['subject'] : 'Quote Rejected' ); ?>"
									type="text" class="form-control">
							</div>
						</div>

						<!-- Email Heading -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Heading', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the heading for the email sent to the customer after the quote request is rejected by the administrator.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<input name="quote_rejected_mail_heading_to_cust"
									value="<?php echo esc_html_e( ! empty( $settings['sent_to_customer']['quote_rejected_email_template']['heading'] ) ? $settings['sent_to_customer']['quote_rejected_email_template']['heading'] : 'Your Quote Request has been rejected.' ); ?>"
									type="text" class="form-control">
							</div>
						</div>


						<!-- Email Body -->
						<div class="row mb-3 email-body">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Body', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the body for the email sent to the customer after the quote request is rejected by the administrator.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<textarea name="quote_rejected_mail_body_to_admin" class="form-control"
									rows="3"><?php echo wp_kses_post( ! empty( $settings['sent_to_customer']['quote_rejected_email_template']['body'] ) ? $settings['sent_to_customer']['quote_rejected_email_template']['body'] : '' ); ?></textarea>
							</div>
						</div>
					</div>



					<!-- Reminder Email Template: Sent to the Customer -->
					<div class="pb-3 ">
						<h5 class="mb-3">
							<?php esc_html_e( 'Reminder Email Template: Sent to the Customer', 'elex-request-a-quote' ); ?>
							<span class = "elex_raq_go_premium_color">
									<?php
									esc_html_e('[Premium]', 'elex-request-a-quote');
									?>
								</span>
						</h5>

						<!-- email subject -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Subject', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the subject for the email sent to the customer when there is no action from the customer.', 'elex-request-a-quote'); ?>">

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							
							<div class=" col-lg-7 col-md-6 ">
								<input name="" value="" disabled
									type="text" class="form-control">
							</div>
						</div>

						<!-- Email Heading -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Heading', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the heading for the reminder email sent to the customer.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<input name="" value="" disabled type="text" class="form-control">
							</div>
						</div>


						<!-- Email Body -->
						<div class="row mb-3 email-body">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Body', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the email body for the reminder email sent to the customer.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<textarea  disabled name="" class="form-control"
									rows="3"></textarea>
							</div>
						</div>
					</div>

					<!-- Negotitation Email Template: Sent to the Customer -->
					<div class="pb-3 ">
						<h5 class="mb-3">
							<?php esc_html_e( 'Negotiation Email Template: Sent to the Customer', 'elex-request-a-quote' ); ?>
							<span class = "elex_raq_go_premium_color">
									<?php
									esc_html_e('[Premium]', 'elex-request-a-quote');
									?>
								</span>
						</h5>

						<!-- email subject -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Subject', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the subject for the negotiation email sent to the customer.', 'elex-request-a-quote'); ?>">

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							
							<div class=" col-lg-7 col-md-6 ">
								<input name="" value="" disabled
									type="text" class="form-control">
							</div>
						</div>

						<!-- Email Heading -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Heading', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the heading for the negotiation email sent to the customer.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<input name="" value="" disabled type="text" class="form-control">
							</div>
						</div>


						<!-- Email Body -->
						<div class="row mb-3 email-body">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Email Body', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the email body for the negotiation email sent to the customer.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<textarea disabled name="" class="form-control"
									rows="3"></textarea>
							</div>
						</div>
					</div>

					<!-- Quote Requested SMS / Google Chat Template: Sent to the Site Administrator -->
					<div class="pb-3">
						<h5 class="mb-3">
							<?php esc_html_e( 'Quote Requested SMS / Google Chat Template: Sent to the Site Administrator', 'elex-request-a-quote' ); ?>
						</h5>

						<!-- SMS And Google Chat Body -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-5 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'SMS And Google Chat Body', 'elex-request-a-quote' ); ?>
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the content for the SMS/google chat notification sent to the administrator on receiving a quote request. you can enter text or fill the fields dynamically using placeholders from the above table. HTML tags are not accepted in this field.', 'elex-request-a-quote'); ?>"
										>

										<svg xmlns="http://www.w3.org/2000/svg " width="26 " height="26 "
											viewBox="0 0 26 26 ">
											<g id="tt " transform="translate(-384 -226) ">
												<g id="Ellipse_1 " data-name="Ellipse 1 "
													transform="translate(384 226) " fill="#f5f5f5 " stroke="#000 "
													stroke-width="1 ">
													<circle cx="13 " cy="13 " r="13 " stroke="none " />
													<circle cx="13 " cy="13 " r="12.5 " fill="none " />
												</g>
												<text id="_ " data-name="? " transform="translate(392 247) "
													font-size="20 " font-family="Roboto-Bold, Roboto "
													font-weight="700 ">
													<tspan x="0 " y="0 ">?</tspan>
												</text>
											</g>
										</svg>
									</div>
								</div>
							</div>
							<div class=" col-lg-7 col-md-6 ">
								<textarea
									name="quote_requested_sms_chat_body_to_admin" type="text" class="form-control"><?php echo wp_kses_post( ! empty( $settings['sent_to_admin']['quote_requested_sms_chat_template']['body'] ) ? $settings['sent_to_admin']['quote_requested_sms_chat_template']['body'] : '' ); ?> </textarea>
							</div>
						</div>
					</div>

					<button name="submit" type="submit"
						class="btn btn-primary"><?php esc_html_e( 'Save Changes', 'elex-request-a-quote' ); ?></button>
				</div>


				<!-- right section -->
				<div class="col-lg-5">
					<div class="border border-primary p-2 position-sticky top-0">
						<table class="table mb-2">
							<thead style="background: #E7F4FF;">
								<tr>
									<th scope="col"><?php esc_html_e( 'Title', 'elex-request-a-quote' ); ?></th>
									<th scope="col"><?php esc_html_e( 'Placeholder', 'elex-request-a-quote' ); ?></th>
								</tr>
							</thead>
							<tbody class="border-top-0">
								<tr>
									<td><?php esc_html_e( 'Core Fields', 'elex-request-a-quote' ); ?></td>
									<td></td>
								</tr>

								<tr>
									<td><?php esc_html_e( 'Billing First Name', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@billing_first_name'); ?></td>
								</tr>

								<tr>
									<td><?php esc_html_e( 'Billing Last Name', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@billing_last_name' ); ?></td>
								</tr>

								<tr>
									<td><?php esc_html_e( 'Billing Company', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@billing_company' ); ?></td>
								</tr>

								<tr>
									<td><?php esc_html_e( 'Billing Country', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@billing_country' ); ?></td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Billing Address 1', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@billing_address_1' ); ?></td>
								</tr>

								<tr>
									<td><?php esc_html_e( 'Billing Address 2', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@billing_address_2'); ?></td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Billing City', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@billing_city' ); ?></td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Billing State', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@billing_state' ); ?></td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Billing Postcode', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@billing_postcode' ); ?></td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Billing Phone', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@billing_phone' ); ?></td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Billing Email', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@billing_email'); ?></td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Order Items', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@order_items' ); ?></td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Payment Link', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@payment_link' ); ?></td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Customer Note', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@customer_note' ); ?></td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Order ID', 'elex-request-a-quote' ); ?></td>
									<td><?php esc_html( '@order_id'); ?></td>
								</tr>
								<tr>
									<td><?php esc_html_e( 'Custom Fields', 'elex-request-a-quote' ); ?></td>
									<td></td>
								</tr>
								<?php
								foreach ( $form_fields as $key => $val ) {
									if ( 'default' === $val['connected_to'] ) { 
										?>
										<tr><td><?php echo esc_html( $val['name'] ); ?></td><td><?php echo '@' . esc_html( $val['slug'] ); ?></td></tr>
									<?php 
									} 
								} 
								?>
							</tbody>
						</table>
					</div>


				</div>

			</div>

		</div>



	</div>
</form>

