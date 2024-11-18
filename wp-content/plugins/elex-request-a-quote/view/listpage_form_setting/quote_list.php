<form method="POST">
	<?php wp_nonce_field( 'save_settings', 'req_settings_nonce' ); ?>
	<div class="p-3">
		<div class="row">
			<div class="col-12">
				<!-- Select "Quote List" Page -->
				<div class="row mb-3">
					<div class="col-lg-4 col-md-6 ">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Select "Quote List" Page', 'elex-request-a-quote' ); ?></h6>
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
							title="<?php esc_html_e('Select the WooCommerce page where you want to display the quote list.', 'elex-request-a-quote'); ?>"
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
					<div class="col-lg-4 col-md-6 mb-3">
						<div class="form-group">
							<!-- <input type="text" class="form-control"> -->
							<select name="selected_page"  id="selected_page" class="form-select  chosen-select">
								<?php foreach ( $settings['pages'] as $url => $page_name ) : ?>
									<option value="<?php echo esc_url( $url ); ?>" <?php echo  esc_html_e( ( ! empty( $settings['selected_page'] ) && ( $url === $settings['selected_page'] ) ? 'selected' : '' ) ); ?>><?php echo esc_html_e( $page_name ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>

				<!-- Quote List Page Title -->
				<div class="row ">
					<div class="col-lg-4 col-md-6 ">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Quote List Page Title', 'elex-request-a-quote' ); ?></h6>
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
							title="<?php esc_html_e('Enter the title on the quote list page.', 'elex-request-a-quote'); ?>"
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
					<div class="col-lg-4 col-md-6 mb-3">
						<div class="form-group">
							<input name="page_title" value="<?php esc_attr_e( ( isset( $settings['title'] ) && ! empty( $settings['title'] ) ) ? $settings['title'] : __( 'Quote List' , 'elex-request-a-quote' ) ); ?>" type="text" class="form-control">
						</div>
					</div>
				</div>

				<!-- "Quote List" Page Layout -->
				<div class="row mb-3">
					<div class="col-lg-4 col-md-6 ">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( '"Quote List" Page Layout', 'elex-request-a-quote' ); ?></h6>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 ">
						<div class="form-group mb-3 d-flex gap-3 align-items-center">
							<input value="product_on_left_form_on_right" <?php echo esc_html_e( ! empty( $settings['layout']['product_on_left_form_on_right'] ) ? 'checked' : '' ); ?> type="radio" class="" name="layout_choice">
							<div class="d-flex gap-2 align-items-center">

									<svg xmlns="http://www.w3.org/2000/svg" width="50" height="54" viewBox="0 0 50 54">
										<g id="verticle_icon" data-name="verticle icon" transform="translate(-490 -472)">
											<g id="Rectangle_12" data-name="Rectangle 12" transform="translate(490 472)" fill="#fff" stroke="#bdbdbd" stroke-width="1">
												<path d="M8,0H18a5,5,0,0,1,5,5V49a5,5,0,0,1-5,5H8a8,8,0,0,1-8-8V8A8,8,0,0,1,8,0Z" stroke="none" />
												<path d="M8,.5H18A4.5,4.5,0,0,1,22.5,5V49A4.5,4.5,0,0,1,18,53.5H8A7.5,7.5,0,0,1,.5,46V8A7.5,7.5,0,0,1,8,.5Z" fill="none" />
											</g>
											<g id="Rectangle_1020" data-name="Rectangle 1020" transform="translate(517 472)" fill="#fff" stroke="#bdbdbd" stroke-width="1">
												<path d="M5,0H15a8,8,0,0,1,8,8V46a8,8,0,0,1-8,8H5a5,5,0,0,1-5-5V5A5,5,0,0,1,5,0Z" stroke="none" />
												<path d="M5,.5H15A7.5,7.5,0,0,1,22.5,8V46A7.5,7.5,0,0,1,15,53.5H5A4.5,4.5,0,0,1,.5,49V5A4.5,4.5,0,0,1,5,.5Z" fill="none" />
											</g>
										</g>
									</svg>
								<h6 class="mb-0"><?php esc_html_e( 'Product list on the left & form on the right', 'elex-request-a-quote' ); ?></h6>
							</div>
						</div>
						<div class="form-group mb-3 d-flex gap-3 align-items-center">
							<input value="product_on_top_form_on_bottom" <?php echo esc_html_e( ! empty( $settings['layout']['product_on_top_form_on_bottom'] ) ? 'checked' : '' ); ?> type="radio" class="" name="layout_choice">
							<div class="d-flex gap-2 align-items-center">

									<svg xmlns="http://www.w3.org/2000/svg" width="54" height="50" viewBox="0 0 54 50">
										<g id="Horizontal_icon" data-name="Horizontal icon" transform="translate(595 -430) rotate(90)">
											<g id="Rectangle_1021" data-name="Rectangle 1021" transform="translate(430 541)" fill="#fff" stroke="#bdbdbd" stroke-width="1">
												<path d="M8,0H18a5,5,0,0,1,5,5V49a5,5,0,0,1-5,5H8a8,8,0,0,1-8-8V8A8,8,0,0,1,8,0Z" stroke="none" />
												<path d="M8,.5H18A4.5,4.5,0,0,1,22.5,5V49A4.5,4.5,0,0,1,18,53.5H8A7.5,7.5,0,0,1,.5,46V8A7.5,7.5,0,0,1,8,.5Z" fill="none" />
											</g>
											<g id="Rectangle_1022" data-name="Rectangle 1022" transform="translate(457 541)" fill="#fff" stroke="#bdbdbd" stroke-width="1">
												<path d="M5,0H15a8,8,0,0,1,8,8V46a8,8,0,0,1-8,8H5a5,5,0,0,1-5-5V5A5,5,0,0,1,5,0Z" stroke="none" />
												<path d="M5,.5H15A7.5,7.5,0,0,1,22.5,8V46A7.5,7.5,0,0,1,15,53.5H5A4.5,4.5,0,0,1,.5,49V5A4.5,4.5,0,0,1,5,.5Z" fill="none" />
											</g>
										</g>
									</svg>
								<h6 class="mb-0"><?php esc_html_e( 'Product list on the top & form on the bottom', 'elex-request-a-quote' ); ?></h6>
							</div>
						</div>
					</div>
				</div>


				<!-- Choose What to Show If List is Emptye -->
				<div class="row mb-3">
					<div class="col-lg-4 col-md-6 ">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Choose What to Show If List is Empty', 'elex-request-a-quote' ); ?>
							<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
						</h6>

						</div>
					</div>
					<div class="col-lg-4 col-md-6 ">
						<div class=" mb-3 d-flex gap-3 align-items-center">
							<input disabled name="illustration" type="checkbox" checked class="">
							<div>
								<h6 class="mb-0"><?php esc_html_e( 'Illustration', 'elex-request-a-quote' ); ?></h6>
							</div>
						</div>

						<div class=" mb-3 d-flex gap-3 ">
							<input disabled type="checkbox" name="empty_text_is_enabled" checked class="">
							<div class="flex-fill">
								<h6 class="mb-1"><?php esc_html_e( 'Empty Text', 'elex-request-a-quote' ); ?></h6>
								<input readonly  disabled name="empty_text_value" type="text" value="<?php echo esc_html_e( $settings['show_if_list_empty']['empty_text']['text'] ); ?>" class="form-control" placeholder="Your Quote List is Empty!">
							</div>
						</div>

						<div class=" mb-3 d-flex gap-3 align-items-center">
							<input  disabled type="checkbox" name="go_to_shop_page_button" checked class="">
							<div class="flex-fill">
								<h6 class="mb-0"><?php esc_html_e( '"Go to Shop Page" button"', 'elex-request-a-quote' ); ?></h6>
							</div>
						</div>

						<div class=" mb-3 d-flex gap-3 align-items-center">
							<input disabled  type="checkbox" name="quote_request_form"  class="">

							<div class="flex-fill">
								<h6 class="mb-0"><?php esc_html_e( 'Quote a Request Form', 'elex-request-a-quote' ); ?></h6>
							</div>
						</div>

					</div>
				</div>



				<!-- Contents to Show in Product Table -->
				<div class="row mb-3">
					<div class="col-lg-4 col-md-6 ">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Contents to Show in Product Table', 'elex-request-a-quote' ); ?>
							<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
						</h6>

						</div>
					</div>
					<div class="col-lg-4 col-md-6 ">
						<div class=" mb-3 d-flex gap-3 align-items-center">
							<input  disabled type="checkbox" name="product_image" checked class="">

							<h6 class="mb-0"><?php esc_html_e( 'Product Image', 'elex-request-a-quote' ); ?></h6>
						</div>

						<div class=" mb-3 d-flex gap-3 align-items-center">
							<input disabled type="checkbox" name="product_price" checked class="">
							<h6 class="mb-0"><?php esc_html_e( 'Product Price', 'elex-request-a-quote' ); ?></h6>
						</div>


						<div class=" mb-3 d-flex gap-3 align-items-center">
							<input disabled type="checkbox" name="quantity" checked class="">
							<h6 class="mb-0"><?php esc_html_e( 'Quantity', 'elex-request-a-quote' ); ?></h6>
						</div>

						<div class=" mb-3 d-flex gap-3 align-items-center">
							<input disabled type="checkbox" name="each_product_subtotal" checked  class="">
							<h6 class="mb-0"><?php esc_html_e( 'Each Product Subtotal', 'elex-request-a-quote' ); ?></h6>
						</div>

						<div class=" mb-3 d-flex gap-3 align-items-center">
							<input disabled type="checkbox" name="product_sku"  class="">
							<h6 class="mb-0"><?php esc_html_e( 'Product SKU', 'elex-request-a-quote' ); ?></h6>
						</div>

						<div class=" mb-3 d-flex gap-3 align-items-center">
							<input disabled type="checkbox" name="taxes"  class="">
							<h6 class="mb-0"><?php esc_html_e( 'Taxes', 'elex-request-a-quote' ); ?></h6>
						</div>

					</div>
				</div>

					<!-- Preffered Price Toggle -->
					<div class="row mb-3 align-items-center">
					<div class="col-lg-4 col-md-6">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Accept Preferred Price from Customer', 'elex-request-a-quote' ); ?>
							<span class = "elex_raq_go_premium_color">
									<?php 
									esc_html_e( '[Premium]', 'elex-request-a-quote' );
									?>
								</span>
							</h6>
							<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
							title="<?php esc_html_e('Toggle On to accept the Preferred Price from Customer. ', 'elex-request-a-quote'); ?>">
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
							<input type="checkbox" onchange="" class="custom_price_enabled" name=""
								disabled >
							<div class="elex-switch-icon round"></div>
						</label>
					</div>
				</div>


				<!-- show powered by Elex Request a quote -->
				<div class="row align-items-center mb-3">
					<div class="col-lg-4 col-md-6">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Show "Powered by ELEX Request a Quote"', 'elex-request-a-quote' ); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php esc_html_e('Turn On or Off if you want to show or hide the ELEXtensions watermark on the quote list page.', 'elex-request-a-quote'); ?>">

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
					<div class="col-lg-4 col-md-6">
						<label class="elex-switch-btn">
							<input type="checkbox" name="show_prowered_by" <?php echo esc_html_e( ! empty( $settings['show_prowered_by'] ) && true === $settings['show_prowered_by'] ? 'checked' : '' ); ?>>
							<div class="elex-switch-icon round"></div>
						</label>
					</div>
				</div>
				<button name="submit" type="submit" class="btn btn-primary "><?php esc_html_e( 'Save Changes', 'elex-request-a-quote' ); ?></button>
			</div>
		</div>

	</div>
</form>
