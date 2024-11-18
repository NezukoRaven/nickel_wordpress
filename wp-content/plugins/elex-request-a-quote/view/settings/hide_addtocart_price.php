<form method="POST">

	<?php wp_nonce_field('save_settings', 'req_settings_nonce');
	global $wp_roles;
	$all_roles                 = $wp_roles->role_names;
	$all_roles['unregistered'] = 'Unregistered';

	?>
	<div class="p-2 pe-4 hide_cart">

		<div class=" p-3 ">

		<!-- Hide Price -->
		<div class="row align-items-center mb-3 ">
				<div class="col-12 ">
					<div class="row ">
						<div class=" col-lg-4 col-md-6 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e('Hide Price', 'elex-request-a-quote'); ?></h6>
								<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
								title="<?php esc_html_e('Enabling the Hide Price will automatically enable the Hide Add to Cart button on the Shop page and the Product Page.', 'elex-request-a-quote'); ?>"
								>
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
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
									<input type="checkbox" name="hide_add_to_cart[hide_price]" value="" <?php echo ( isset($settings['hide_add_to_cart']['hide_price']) && ( true === $settings['hide_add_to_cart']['hide_price'] ) ) ? 'checked' : ''; ?> class="hide_price">
									<div class="elex-switch-icon round "></div>
								</label>
								<div class="text-secondary ">
									<small><?php esc_html_e('Turn on to hide price in the Product page, Shop Page and Quote List Page.', 'elex-request-a-quote'); ?></small>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<!-- Hide Add to Cart Button on Shop Page -->
			<div class="row align-items-center mb-3 ">
				<div class="col-12 ">
					<div class="row ">
						<div class=" col-lg-4 col-md-6 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e('Hide Add to Cart Button on Shop Page', 'elex-request-a-quote'); ?></h6>
							</div>
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">

								<!-- toggle switch -->
								<label class="elex-switch-btn ">
									<input type="checkbox" name="hide_add_to_cart[button_on_shop_page]" value="" <?php echo ( isset($settings['hide_add_to_cart']['button_on_shop_page']) && ( true === $settings['hide_add_to_cart']['button_on_shop_page'] ) ) ? 'checked' : ''; ?> class="hide_add_cart_button_on_shop">
									<div class="elex-switch-icon round "></div>
								</label>

								<div class="text-secondary ">
									<small><?php esc_html_e('Turn on to hide the add to cart button on the shop page.', 'elex-request-a-quote'); ?></small>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<!-- Hide Add to Cart Button on Shop Page -->
			<div class="row align-items-center mb-3 ">
				<div class="col-12 ">
					<div class="row ">
						<div class=" col-lg-4 col-md-6 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e('Hide Add to Cart Button on Product Page', 'elex-request-a-quote'); ?></h6>
							</div>
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
									<input type="checkbox" name="hide_add_to_cart[button_on_product_page]" value="" <?php echo ( isset($settings['hide_add_to_cart']['button_on_product_page']) && ( true === $settings['hide_add_to_cart']['button_on_product_page'] ) ) ? 'checked' : ''; ?> class="hide_add_cart_button_on_product">
									<div class="elex-switch-icon round "></div>
								</label>
								<div class="text-secondary ">
									<small><?php esc_html_e('Turn on to hide the add to cart button on the product page.', 'elex-request-a-quote'); ?></small>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			


			<!--Include Products-->
			<div class="row align-items-center  hide_cart_include_products elex-rqust-quote-check-sec">
				<div class="col-12 ">
					<div class="row mb-3">
						<div class=" col-lg-4 col-md-6 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e('Include Products', 'elex-request-a-quote'); ?>
								<span class = "elex_raq_go_premium_color">
									<?php
									esc_html_e('[Premium]', 'elex-request-a-quote');
									?>
								</span></h6>

							</div>
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
									<input disabled id="" type="checkbox" name="" disabled value="" class="">
									<div class="elex-switch-icon round "></div>
								</label>
								<div class="text-secondary ">
									<small><?php esc_html_e('Turn on & Select the Products for which the above settings should not be applied', 'elex-request-a-quote'); ?></small>
								</div>
							</div>

						</div>
					</div>

					
				</div>
			</div>





			<!-- Exclude Product -->
			<div class="row align-items-center  hide_cart_exclude_products elex-rqust-quote-check-sec">
				<div class="col-12 ">
					<div class="row mb-3">
						<div class=" col-lg-4 col-md-6 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e('Exclude Products', 'elex-request-a-quote'); ?>
								<span class = "elex_raq_go_premium_color">
									<?php
									esc_html_e('[Premium]', 'elex-request-a-quote');
									?>
								</span></h6>

							</div>
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
									<input disabled id="hide_add_cart_exclude" type="checkbox" name="hide_add_to_cart[exclude_products][enabled]" <?php echo ( isset($settings['hide_add_to_cart']['exclude_products']['enabled']) && ( true === $settings['hide_add_to_cart']['exclude_products']['enabled'] ) ) ? 'checked' : ''; ?> value="" class="elex-rqust-quote-check-sec-input-exclude-product">
									<div class="elex-switch-icon round "></div>
								</label>
								<div class="text-secondary ">
									<small><?php esc_html_e('Turn on & Select the Products for which the above settings should not be applied', 'elex-request-a-quote'); ?></small>
								</div>
							</div>

						</div>
					</div>

					<div class="elex-rqust-quote-check-content-hide-cart">
						<!-- exclude product by category -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e('Exclude Products By Category', 'elex-request-a-quote'); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
									title="<?php esc_html_e('Select the product categories that you want to exclude from the above applied settings', 'elex-request-a-quote'); ?>"
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
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">
										<select name="hide_add_to_cart[exclude_products][by_category][]" style="width:100% !important" class="products_by_cat hidecart_exclude_prod_by_cat form-select border-2 border-secondary " multiple="true">
											<?php
											foreach ($exclude_products_by_category as $product) {
												?>
													<option value="<?php esc_html_e($product['id']); ?>" selected><?php echo esc_html_e($product['name']); ?>
													</option>
												<?php
											}
											?>

										</select>


									</div>
								</div>


							</div>
						</div>

						<!-- exclude product by Name -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e('Exclude Products By Name', 'elex-request-a-quote'); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
									title="<?php esc_html_e('Select the product names that you want to exclude from the above applied settings', 'elex-request-a-quote'); ?>"
									
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
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">

										<select name="hide_add_to_cart[exclude_products][by_name][]" style="width:100% !important" class="products_by_name hidecart_exclude_prod_by_name form-select border-2 border-secondary " multiple="true">
											<?php
											foreach ($hide_cart_exclude_products_by_name as $product) {
												?>
													<option value="<?php echo esc_html_e($product['id']); ?>" selected><?php echo esc_html_e($product['name']); ?>
													</option>
												<?php
											}
											?>

										</select>

									</div>
								</div>


							</div>
						</div>

						<!-- exclude product by tags -->
						<div class="row mb-3 align-items-center">
							<div class=" col-lg-4 col-md-6 ">
								<div class="d-flex justify-content-between align-items-center gap-2">
									<h6 class="mb-0"><?php esc_html_e('Exclude Products By Tags', 'elex-request-a-quote'); ?></h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
									title="<?php esc_html_e('Select the product tags that you want to exclude from the above applied settings', 'elex-request-a-quote'); ?>"
									
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
							<div class=" col-lg-8 col-md-6 ">
								<div class="row">
									<div class="col-xl-6 col-lg-9 col-md-12">

										<select name="hide_add_to_cart[exclude_products][by_tag][]" style="width:100% !important" class="products_by_tag hidecart_exclude_prod_by_tag form-select border-2 border-secondary " multiple="true">
											<?php

											foreach ($exclude_products_by_tag as $product) {
												?>
													<option value="<?php echo esc_html_e($product['id']); ?>" selected><?php echo esc_html_e($product['name']); ?>
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

			<!-- Role Based Filter -->
			<div class="row align-items-center elex-rqust-quote-check-sec ">
				<div class="col-12 ">
					<div class="row mb-3">
						<div class=" col-lg-4 col-md-6 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e('Role Based Filter', 'elex-request-a-quote'); ?>
								<span class = "elex_raq_go_premium_color">
									<?php
									esc_html_e('[Premium]', 'elex-request-a-quote');
									?>
								</span>
							</h6>
							</div>
						</div>
						<div class=" col-lg-8 col-md-6 ">
							<div class="">
								<label class="elex-switch-btn ">
								<input id="" type="checkbox"
										name="hide_add_to_cart[exclude_roles][enabled]" disabled  value="" class="">
									<div class="elex-switch-icon round "></div>
								</label>
								<div class="text-secondary ">
									<small>
										<?php
										esc_html_e(
											'Select the roles for which the above settings should be
                                        applied/not.',
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





			
			<button name="submit" type="submit" class=" hidecart_setting_save_chages btn btn-primary "><?php esc_html_e('Save Changes', 'elex-request-a-quote'); ?></button>

		</div>
	</div>
</form>
