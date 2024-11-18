<form method="POST">
	<?php wp_nonce_field( 'save_settings', 'req_settings_nonce' );
	global $wp_roles;
	$all_roles                 = $wp_roles->role_names;
	$all_roles['unregistered'] = 'Unregistered';
	?>
	<div class="p-3">
		<div class="row">
			<div class="col-12">

				<!-- Quote Expires in -->
				<div class="row mb-3 align-items-center">
						<div class="col-lg-4 col-md-6">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0">
								<?php esc_html_e( 'Quote Expires in', 'elex-request-a-quote' ); ?>
								<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
								</h6>
								
								<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
								title="<?php esc_html_e('Set the number of days after the quote request date for the quote to expire. Leave blank if the quote should not expire.', 'elex-request-a-quote'); ?>"
								>
								<svg xmlns="http://www.w3.org/2000/svg" style="min-width: 26px;" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)" fill="#f5f5f5" stroke="#000"
											stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20"
											font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
								</div>
							</div>
						</div>

						<div class="col-lg-4 col-md-6">
							<div class="input-group mb-3 border rounded">
								<input disabled 

								name="" 
								value=""
								type="number" class="disbled_field form-control border-0" placeholder="" 
									aria-describedby="basic-addon2">
								<div class="input-group-append ">
									<span class="input-group-text px-4 border-0 rounded-0 rounded-end"><?php esc_html_e( 'Days', 'elex-request-a-quote' ); ?></span>
								</div>
							
							</div>
						</div>
				</div>

					<!-- Accepted Payment Gateways -->
					<div class="row mb-3 align-items-center">
					<div class=" col-lg-4 col-md-6 ">
						<div class="d-flex justify-content-between align-items-center gap-2">
							<h6 class="mb-0">
								<?php esc_html_e('Accepted Payment Gateways', 'elex-request-a-quote'); ?>
								<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
							</h6>
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
								title="<?php esc_html_e('Select the store payment methods to display during checkout. Leave blank to show all payment methods.', 'elex-request-a-quote'); ?>"
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
					<div class=" col-lg-8 col-md-6 ">
						<div class="row">
							<div class="col-xl-6 col-lg-9 col-md-12">
								<select disabled 
								name=""
									style="width:100% !important"
									class="disbled_field allowed_paments  form-select"
								>
									
								</select>


							</div>
						</div>


					</div>
				</div>
				<!-- Quote Button on Shop Page -->
				<div class="row align-items-center mb-3">
					<div class="col-lg-4 col-md-6">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Quote Button on Shop Page', 'elex-request-a-quote' ); ?></h6>
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
								title="<?php esc_html_e( 'Toggle On or Off to display the Add to Quote button on the shop page' , 'elex-request-a-quote' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)"
											fill="#f5f5f5" stroke="#000" stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20"
											font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<label class="elex-switch-btn">
							<input class="button_on_shop_page" name="general[button_on_shop_page]" type="checkbox"
								onchange=""
								<?php echo isset( $settings['general']['button_on_shop_page'] ) && ! empty( $settings['general']['button_on_shop_page'] ) ? 'checked' : ''; ?>>
							<div class="elex-switch-icon round"></div>
						</label>
					</div>
				</div>

			<!-- Quote Button on Product Page -->
			<div class="row mb-3 align-items-center">
					<div class="col-lg-4 col-md-6">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Quote Button on Product Page', 'elex-request-a-quote' ); ?>
							</h6>
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
							title="<?php esc_html_e('Toggle On or Off the display of the Add to Quote button on the product page', 'elex-request-a-quote'); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)"
											fill="#f5f5f5" stroke="#000" stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20"
											font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<label class="elex-switch-btn">
							<input type="checkbox" onchange="" class="button_on_product_page" name="general[button_on_product_page]"
								<?php echo isset( $settings['general']['button_on_product_page'] ) && ! empty( $settings['general']['button_on_product_page'] ) ? 'checked' : ''; ?>>
							<div class="elex-switch-icon round"></div>
						</label>
					</div>
				</div>

				<!-- "show Quote Button -->
				<div class="row d-none inline_below_add_to_cart mb-3">
					<div class="col-lg-4 col-md-6 ">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Add to Quote Button Position on Product Page', 'elex-request-a-quote' ); ?><span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span></h6>
							<div class="d-flex justify-content-between align-items-center">
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
							title="<?php esc_html_e('Button position may vary depending on the theme.', 'elex-request-a-quote'); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)"
											fill="#f5f5f5" stroke="#000" stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20"
											font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
							</div>
						</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 ">
						<div class="form-group mb-3 d-flex gap-3 align-items-center">
							<input disabled value="inline"  type="radio" class="" name="">
							<div class="d-flex gap-2 align-items-center">
							
								<h6 class="mb-0"><?php esc_html_e( 'Inline with Add to Cart', 'elex-request-a-quote' ); ?></h6>
							</div>
						</div>
						<div class="form-group mb-3 d-flex gap-3 align-items-center">
							<input readonly value="below_add_to_cart" checked type="radio" class="" name="">
							<div class="d-flex gap-2 align-items-center">

								<h6 class="mb-0"><?php esc_html_e( 'Below Add to Cart', 'elex-request-a-quote' ); ?></h6>
							</div>
						</div>
					</div>
				</div>

					<!-- Ask for Quote Button on Cart Page -->
					<div class="row mb-3 align-items-center">
					<div class="col-lg-4 col-md-6">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Quote Button on Cart Page', 'elex-request-a-quote' ); ?>
							<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
							</h6>
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
							title="<?php esc_html_e('Enable or disable the "Add to Quote" button on the Cart page. When enabled, this button will appear on the Cart page. Customers can click the "Add to Quote" button to automatically add the products in their cart to a quote list page.', 'elex-request-a-quote'); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)"
											fill="#f5f5f5" stroke="#000" stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20"
											font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<label class="elex-switch-btn">
							<input disabled type="checkbox" onchange="" class="" name=""
								<?php echo isset( $settings['general']['ask_for_quote'] ) && ! empty( $settings['general']['ask_for_quote'] ) ? 'checked' : ''; ?>>
							<div class="elex-switch-icon round"></div>
						</label>
					</div>
				</div>


				<!-- quote button configuration -->
				<h5 class="fw-bold"><?php esc_html_e( 'Quote Button Configurations', 'elex-request-a-quote' ); ?></h5>

				<!-- Open Quote Form -->
				<div class="row align-items-center">
					<div class="col-lg-4 col-md-6 mb-3">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Open Quote Form', 'elex-request-a-quote' ); ?></h6>

							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
								title="<?php esc_html_e('Select how you want to show the quote list to your user.', 'elex-request-a-quote' ); ?>" >
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)"
											fill="#f5f5f5" stroke="#000" stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20"
											font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 mb-3">
						<div class="form-group">
							<select name="general[open_quote_form]" class="form-select"
								id="elex-rqst-quote-form-select">
								<option value="new_page"
									<?php echo isset( $settings['general']['open_quote_form'] ) && 'new_page' === $settings['general']['open_quote_form'] ? 'selected' : ''; ?>>
									<?php esc_html_e( 'In a New Page', 'elex-request-a-quote' ); ?></option>
								<option value="light_box"
									<?php echo isset( $settings['general']['open_quote_form'] ) && 'light_box' === $settings['general']['open_quote_form'] ? 'selected' : ''; ?>>
									<?php esc_html_e( 'In a Light Box', 'elex-request-a-quote' ); ?></option>
							</select>
						</div>
					</div>

					<div class="col-12">
						<!-- Add to Quote Success Alert Message -->
						<div class="row ">

							<div class="col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center">
									<h6 class="mb-0">
										<?php esc_html_e( 'Add to Quote Success Alert Message', 'elex-request-a-quote' ); ?>
									</h6>
									</g>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Enter the alert message to display on successful addition of the product to the quote list.', 'elex-request-a-quote'); ?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
											viewBox="0 0 26 26">
											<g id="tt" transform="translate(-384 -226)">
												<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)"
													fill="#f5f5f5" stroke="#000" stroke-width="1">
													<circle cx="13" cy="13" r="13" stroke="none" />
													<circle cx="13" cy="13" r="12.5" fill="none" />
												</g>
												<text id="_" data-name="?" transform="translate(392 247)" font-size="20"
													font-family="Roboto-Bold, Roboto" font-weight="700">
													<tspan x="0" y="0">?</tspan>
												</text>
										</svg>
									</div>


								</div>
							</div>
							<div class="col-lg-4 col-md-6 mb-3">
								<div class="form-group">
									<input
									   maxlength = "100"
										placeholder="<?php esc_html_e( 'Product successfully added to the Quote List', 'elex-request-a-quote' ); ?>"
										type="text"  name="general[add_to_quote_success_message]" class="form-control"
										onchange=""
										value="<?php echo isset( $settings['general']['add_to_quote_success_message'] ) ? esc_html_e( $settings['general']['add_to_quote_success_message'] ) : ''; ?>">
								</div>
							</div>
						</div>
					</div>


					<div class="col-lg-4 col-md-6 mb-3">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Add to Quote Button Behaviour', 'elex-request-a-quote' ); ?>
							<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
						</h6>

							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
							title="<?php esc_html_e( 'Select "Redirect to the Quote List Page" to enable automatic redirection to the Quote List Page upon clicking the "Add to Quote" button.', 'elex-request-a-quote' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)"
											fill="#f5f5f5" stroke="#000" stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20"
											font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 mb-3">
						<div class="form-group">
							<select  name="" class="form-select"
								id="elex-rqst-quote-form-select">
								<option disabled value="redirect"
									<?php echo isset( $settings['general']['button_behaviour'] ) && 'redirect' === $settings['general']['button_behaviour'] ? 'selected' : ''; ?>>
									<?php esc_html_e( 'Redirect to the Quote List Page', 'elex-request-a-quote' ); ?></option>
								<option   value="same_page"
									<?php echo isset( $settings['general']['button_behaviour'] ) && 'same_page' === $settings['general']['button_behaviour'] ? 'selected' : ''; ?>>
									<?php esc_html_e( 'Stay on the Shop/Product page', 'elex-request-a-quote' ); ?></option>
							
							</select>
						</div>
					</div>

				</div>

				<!-- quote button Customization -->
				<h5 class="fw-bold mb-3"><?php esc_html_e( 'Quote Button Customization', 'elex-request-a-quote' ); ?></h5>
				<div class="row align-items-center">
					<div class="col-lg-4 col-md-6 mb-3 ">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Quote Button Label', 'elex-request-a-quote' ); ?></h6>
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
								title="<?php esc_html_e('Enter a custom label text for the request a quote button', 'elex-request-a-quote'); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)"
											fill="#f5f5f5" stroke="#000" stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20"
											font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 mb-3 ">
						<div class="form-group ">
							<input type="text " name="general[button_label]" class="form-control "
								value="<?php echo ( isset( $settings['general']['button_label'] ) ? esc_html_e( $settings['general']['button_label'] ) : '' ); ?>">
						</div>
					</div>

				</div>

				<!-- Quote Default Button Color -->
				<div class="row align-items-center">
					<div class="col-lg-4 col-md-6 mb-3 ">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Default Button Color', 'elex-request-a-quote' ); ?></h6>
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
								title="<?php esc_html_e('Choose the default color for the buttons.', 'elex-request-a-quote'); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
									<g id="tt" transform="translate(-384 -226)">
										<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)"
											fill="#f5f5f5" stroke="#000" stroke-width="1">
											<circle cx="13" cy="13" r="13" stroke="none" />
											<circle cx="13" cy="13" r="12.5" fill="none" />
										</g>
										<text id="_" data-name="?" transform="translate(392 247)" font-size="20"
											font-family="Roboto-Bold, Roboto" font-weight="700">
											<tspan x="0" y="0">?</tspan>
										</text>
									</g>
								</svg>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 mb-3 ">
						<div class="d-flex gap-2 ">
							<input type="text" name="general[button_default_color]"
								value="<?php esc_html_e( $settings['general']['button_default_color'] ); ?>"
								id="add_quote_button_hex" class="form-control ">
							<input type="color"
								value="<?php echo esc_html_e( $settings['general']['button_default_color'] ); ?>"
								id="add_quote_button_color" class="fs-5 form-control form-control-color p-0 border-0 ">
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>

	<!-- filter Products -->
	<div>
		<div class="px-3 ">
			<h5 class="fw-bold"><?php esc_html_e( 'Filter Products', 'elex-request-a-quote' ); ?></h5>
		</div>

		<div class="bg-warning bg-opacity-10 p-2  mb-3 elex-rqst-quote-warning">
			<small style="font-weight: 500;">
				<?php
				esc_html_e(
					'The quote button will be added to all the products by
                default. If you want to include
                or exclude certain products, use the filter options given below.',
					'elex-request-a-quote'
				);
				?>
			</small>
			
		</div>
		<div class=" px-3 ">

			<!-- Limit Quote Button to Certain Products -->
			<div class="row align-items-center elex-rqust-quote-check-sec ">
				<div class="col-12 ">
					<div class="row mb-3">
						<div class=" col-lg-4 col-md-6 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e( 'Include Products', 'elex-request-a-quote' ); ?></h6>
								
							</div>
							
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
									<input id="limit_product_is_enabled" onchange="" type="checkbox"
										name="general[limit_button_on_certain_products][enabled]" value=""
										<?php echo ( isset( $settings['general']['limit_button_on_certain_products']['enabled'] ) && ( true === $settings['general']['limit_button_on_certain_products']['enabled'] ) ) ? 'checked' : ''; ?>
										class="elex-rqust-quote-check-sec-input">
									<div class="elex-switch-icon round "></div>

								</label>
								<div class="text-secondary ">
									<small>
										<?php
										esc_html_e(
											'Select the products for which request a quote settings should
                                        be applied. Rest of the products will be excluded
                                        automatically.',
											'elex-request-a-quote'
										);
										?>
									</small>
								</div>
							</div>

						</div>
					</div>


					<!-- show sub input when input checked -->
					<div class="elex-rqust-quote-check-content-limit-product">
						<!-- Include Products By Category -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0">
										<?php esc_html_e( 'Include Products By Category', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Select the products categories for which you want to display the request a quote button', 'elex-request-a-quote'); ?>">

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
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">


										<select
											name="general[limit_button_on_certain_products][include_products_by_category][]"
											style="width:100% !important"
											class="products_by_cat  include_prod_by_cat form-select border-2 border-secondary "
											multiple="true">

											<?php
											foreach ( $include_products_by_category as $product ) {
												?>
											<option value="<?php echo esc_html_e( $product['id'] ); ?>" selected>
												<?php echo esc_html_e( $product['name'] ); ?>
											</option>
											<?php
											}
											?>
										</select>

									</div>
								</div>


							</div>
						</div>

						<!-- Include product by Name -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Include Products By Name', 'elex-request-a-quote' ); ?>
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Select the products names for which you want to display the request a quote button', 'elex-request-a-quote'); ?>">
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
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">
										<select
											name="general[limit_button_on_certain_products][include_products_by_name][]"
											style="width:100% !important"
											class="products_by_name include_prod_by_name  form-select border-2 border-secondary "
											multiple="true">
											<?php

											foreach ( $include_products_by_name as $product ) {
												?>
											<option value="<?php echo esc_html_e( $product['id'] ); ?>" selected>
												<?php echo esc_html_e( $product['name'] ); ?>
											</option>
											<?php
											}
											?>

										</select>

									</div>
								</div>


							</div>
						</div>

						<!-- Include product by tags -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Include Products By Tags', 'elex-request-a-quote' ); ?>
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Select the products tags for which you want to display the request a quote button', 'elex-request-a-quote'); ?>">

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
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">

										<select
											name="general[limit_button_on_certain_products][include_products_by_tag][]"
											style="width:100% !important"
											class="products_by_tag include_prod_by_tag  form-select border-2 border-secondary "
											multiple="true">
											<?php

											foreach ( $include_products_by_tag as $product ) {
												?>
											<option value="<?php echo esc_html_e( $product['id'] ); ?>" selected>
												<?php echo esc_html_e( $product['name'] ); ?>
											</option>
											<?php
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			<!-- Exclude Product -->
			<div class="row align-items-center elex-rqust-quote-check-sec">
				<div class="col-12 ">
					<div class="row mb-3">
						<div class=" col-lg-4 col-md-6 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e( 'Exclude Product', 'elex-request-a-quote' ); ?>
								<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
							</h6>
								

							</div>
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
									<input disabled id="exclude_product_enabled" type="checkbox"
										name="general[exclude_products][enabled]"
										value="" class="elex-rqust-quote-check-sec-input-exclude-product">
									<div class="elex-switch-icon round "></div>
								</label>
								<div class="text-secondary ">
									<small>
										<?php
										esc_html_e(
											'Select the products for which request a quote settings should
                                        not be applied.',
											'elex-request-a-quote'
										);
										?>
									</small>
								</div>
							</div>

						</div>
					</div>


					<!-- show sub input when input checked -->
					<div class="elex-rqust-quote-check-content-exclude-product">
						<!-- Exclude Products By Category -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0">
										<?php esc_html_e( 'Exclude Products By Category', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e("Select the products categories for which you don't want to display the request a quote button", 'elex-request-a-quote'); ?>">

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
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">

										<select readonly name="general[exclude_products][by_category][]"
											style="width:100% !important"
											class="products_by_cat exclude_prod_by_cat  form-select border-2 border-secondary "
											multiple="true">
										</select>

									</div>
								</div>


							</div>
						</div>

						<!-- Exclude product by Name -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Exclude Products By Name', 'elex-request-a-quote' ); ?>
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e("Select the individual product names for which you don't want to display the request a quote button", 'elex-request-a-quote'); ?>">
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
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">
										<select name="general[exclude_products][by_name][]"
											style="width:100% !important"
											class="products_by_name exclude_prod_by_name form-select border-2 border-secondary "
											multiple="true">
										</select>
									</div>
								</div>


							</div>
						</div>

						<!-- Exclude product by tags -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Exclude Products By Tags', 'elex-request-a-quote' ); ?>
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e("Select the products tags for which you don't want to display the request a quote button", 'elex-request-a-quote'); ?>">


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
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">

										<select readonly name="general[exclude_products][by_tag][]" style="width:100% !important"
											class="products_by_tag  exclude_prod_by_tag form-select border-2 border-secondary "
											multiple="true">
											
										</select>

									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			<!-- Role Based Filter -->
			<div class="row align-items-center elex-rqust-quote-check-sec ">
				<div class="col-12 ">
					<div class="row mb-3">
						<div class=" col-lg-4 col-md-6 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0">
									<span><?php esc_html_e( 'Role Based Filter', 'elex-request-a-quote' ); ?></span>
								<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
							
							</h6>
								
							</div>
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
									<input disabled id="role_based_enabled" type="checkbox"
										name="general[role_based_filter][enabled]" value=" "
										class="elex-rqust-quote-check-sec-input-role-based">
									<div class="elex-switch-icon round "></div>
								</label>
								<div class="text-secondary ">
									<small>
										<?php
										esc_html_e(
											'Select the roles for which request a quote settings should be
                                        applied/not.',
											'elex-request-a-quote'
										);
										?>
									</small>
								</div>
							</div>

						</div>
					</div>



					<!-- show sub input when input checked -->
					<div class="elex-rqust-quote-check-content-role-based">
						<!-- Include Roles -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Include Roles', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e('Select the user roles for which you want to display the request a quote button', 'elex-request-a-quote'); ?>">

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
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">
										<select readonly name="general[role_based_filter][include_roles][]"
											style="width:100% !important"
											class="include_roles  form-select border-2 border-secondary "
											multiple="true">
											
										</select>


									</div>
								</div>


							</div>
						</div>

						<!-- Exclude Roles -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e( 'Exclude Roles', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
										title="<?php esc_html_e("Select the user roles for which you don't want to display the request a quote button", 'elex-request-a-quote'); ?>">

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
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">
										<select name="general[role_based_filter][exclude_roles][]"
											style="width:100% !important"
											class="exclude_roles   form-select border-2 border-secondary "
											multiple="true">
											
										</select>

									</div>
								</div>


							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Disable Quote Request for Unregistered Users -->
			<div class="row align-items-center mb-3 ">
				<div class="col-12 ">
					<div class="row ">
						<div class=" col-lg-4 col-md-6 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0">
									<?php esc_html_e( 'Disable Quote Request for Unregistered Users', 'elex-request-a-quote' ); ?>
									<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
								</h6>
							</div>
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
									<input disabled type="checkbox" name="general[disable_quote_for_guest]" value=""
										class="disable_quote_for_guest">
									<div class="elex-switch-icon round "></div>
								</label>
								<div class="text-secondary ">
									<small>
										<?php
										esc_html_e(
											'By enabling this option guest users can be redirected to the
                                        My Account page for registration.',
											'elex-request-a-quote'
										);
										?>
									</small>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<!-- Include/Exclude products based on stock -->
			<div class="row align-items-center mb-3 ">
				<div class="col-12 ">
					<div class="row ">
						<div class=" col-lg-4 col-md-6 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0">
									<?php esc_html_e( 'Include/Exclude products based on stock', 'elex-request-a-quote' ); ?>
									<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
								</h6>
								<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
									title="<?php esc_html_e('Select an option to determine how you want to display the add to quote button based on the product availablity', 'elex-request-a-quote'); ?>">

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
						<div class="col-lg-4 col-md-6 ">
							<div class="form-group ">
								<select name="general[include_exclude_based_on_stock]" id=" " class="form-select ">

									<option value="show_for_all_products"
										<?php echo ( 'show_for_all_products' === $settings['general']['include_exclude_based_on_stock'] ) ? 'selected' : ''; ?>>
										<?php esc_html_e( 'Show Add to Quote Button for all the products', 'elex-request-a-quote' ); ?>
									</option>
									<option
										<?php echo ( 'hide_for_out_of_stock' === $settings['general']['include_exclude_based_on_stock'] ) ? 'selected' : ''; ?>
										value="hide_for_out_of_stock">
										<?php esc_html_e( 'Hide Add to Quote button for Out of Stock products', 'elex-request-a-quote' ); ?>
									</option>
									<option
										<?php echo ( 'show_for_out_of_stock_only' === $settings['general']['include_exclude_based_on_stock'] ) ? 'selected' : ''; ?>
										value="show_for_out_of_stock_only">
										<?php
										esc_html_e(
											'Show Add to Quote button only for Out of Stock
                                        products',
											'elex-request-a-quote'
										);
										?>
									</option>
								</select>
							</div>

						</div>
					</div>
				</div>
			</div>


			<div class="row py-3">
				<div class="px-3 ">
					<h5 class="fw-bold"><?php esc_html_e( 'Third Party API key', 'elex-request-a-quote' ); ?></h5>
				</div>

				<div class="bg-warning bg-opacity-10 p-2  mb-3 elex-rqst-quote-warning">
						<small>

							<?php 
					esc_html_e(
						'In case you want to get quote requests/orders via third party plugins, Turn On the below
					functionality & get the API Key.',
						'elex-request-a-quote'
					); 
							?>
						</small>
					</div>
				<div class="col-12">
				   

					<div>

						<!-- API key toggle Button -->
						<div class="row align-items-start mb-3">
							<div class="col-lg-4 col-md-6">
								<div class="d-flex justify-content-between align-items-center">
									<h6 class="mb-0"><?php esc_html_e( 'Third Party Usage', 'elex-request-a-quote' ); ?></h6>
									
								</div>
							</div>


							<div class="col-lg-4 col-md-6">
								<label class="elex-switch-btn">
									<input name="enabled" type="checkbox" class="elex-reqst-rest-api-check"
										<?php echo isset( $rest_api ) && true === ( $rest_api ) ? 'checked' : ''; ?>>
									<div class="elex-switch-icon round"></div>
								</label>
								<div class="text-secondary mb-0">
									<small><?php esc_html_e( 'Turn this ON, to get the API Key.', 'elex-request-a-quote' ); ?></small>
								</div>
							</div>
						</div>
						<div class="elex-reqst-rest-api">
							<div class="row align-items-start mb-3">
								<div class="col-lg-4 col-md-6">
									<h6><?php esc_html_e( 'Rest API Key', 'elex-request-a-quote' ); ?></h6>
								</div>
								<div class="col-lg-4 col-md-6">
									<div class="input-group border">
										<input id="api_key"  value="<?php echo esc_html( $api_key ); ?>"
											name="api_key" type="text" class="form-control border-0">
										<div class=" rest_api_key input-group-append d-flex align-items-center">
											<button  type="button" class="copy_api_key p-2 mx-2 btn  border-0 blue-hover lh-1 rounded-circle"
												data-bs-custom-class="tooltip-outline-primary" data-bs-toggle="tooltip"
												data-bs-placement="bottom" title="<?php esc_html_e('Copy API Key', 'elex-request-a-quote'); ?>">
												<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
													viewBox="0 0 512 512" fill="grey">
													<path
														d="M64 464H288c8.8 0 16-7.2 16-16V384h48v64c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V224c0-35.3 28.7-64 64-64h64v48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16zM224 304H448c8.8 0 16-7.2 16-16V64c0-8.8-7.2-16-16-16H224c-8.8 0-16 7.2-16 16V288c0 8.8 7.2 16 16 16zm-64-16V64c0-35.3 28.7-64 64-64H448c35.3 0 64 28.7 64 64V288c0 35.3-28.7 64-64 64H224c-35.3 0-64-28.7-64-64z" />
												</svg>
											</button>

										</div>

									</div>
								</div>
								<div class="col-lg-4">
									<button id="generate_api_key" class="btn btn-primary ">Generate API
										key</button>

								</div>


							</div>
						</div>

					</div>
				</div>

			</div>
		</div>
		<div class="px-3">
			<button name="submit" type="submit"
				class="general_setting_save_chages btn btn-primary"><?php esc_html_e( 'Save Changes', 'elex-request-a-quote' ); ?></button>
		</div>
</form>
<div class="elex-raq-toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999">
			<div id="rest_api_key_message" class="toast" role="alert" aria-live="assertive" aria-atomic="true">

				<div class="toast-body text-center d-flex justify-content-between align-items-center">
					<h6 class="my-2">
						<svg xmlns="http://www.w3.org/2000/svg" width="22.415" height="22.026" viewBox="0 0 22.415 22.026">
							<g id="Icon_feather-check-circle" data-name="Icon feather-check-circle" transform="translate(-1.998 -1.979)">
								<path id="Path_646" data-name="Path 646" d="M23,12.076V13a10,10,0,1,1-5.93-9.139" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
								<path id="Path_647" data-name="Path 647" d="M26.5,6l-10,10.009-3-3" transform="translate(-3.5 -1.003)" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
							</g>
						</svg>
						<span class="ms-2"><?php echo esc_html_e( 'Copied Successfully!' , 'elex-request-a-quote' ); ?></span>
					</h6>
					<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			</div>
		</div>
		<div class="elex-raq-toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999">
			<div id="rest_api_key_error_message" class="toast" role="alert" aria-live="assertive" aria-atomic="true">

				<div class="toast-body text-center d-flex justify-content-between align-items-center">
					<h6 class="my-2">
						<svg xmlns="http://www.w3.org/2000/svg" width="22.415" height="22.026" viewBox="0 0 22.415 22.026">
							<g id="Icon_feather-check-circle" data-name="Icon feather-check-circle" transform="translate(-1.998 -1.979)">
								<path id="Path_646" data-name="Path 646" d="M23,12.076V13a10,10,0,1,1-5.93-9.139" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
								<path id="Path_647" data-name="Path 647" d="M26.5,6l-10,10.009-3-3" transform="translate(-3.5 -1.003)" fill="none" stroke="#28a745" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
							</g>
						</svg>
						<span class="ms-2"><?php echo esc_html_e( 'Error copying text' , 'elex-request-a-quote' ); ?></span>
					</h6>
					<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			</div>
		</div>
