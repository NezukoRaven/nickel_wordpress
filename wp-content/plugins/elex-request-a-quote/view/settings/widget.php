<form method="POST">
	<?php wp_nonce_field( 'save_settings', 'req_settings_nonce' ); ?>
	<div class=" p-3 ">

		<!-- show/hide widget icon settings-->
		<div class="row align-items-center elex-rqust-quote-check-sec ">
			<div class="col-12 ">
				<div class="row mb-3">
					<div class=" col-lg-4 col-md-6 ">
						<div class="d-flex justify-content-between align-items-center">
							<h6 class="mb-0"><?php esc_html_e( 'Show Quote List Icon', 'elex-request-a-quote' ); ?></h6>

						</div>
					</div>
					<div class=" col-lg-8 col-md-6 ">
						<div class="d-flex gap-3 align-items-center">
							<label class="elex-switch-btn ">
								<input name="show_widget_icon" type="checkbox" <?php echo esc_html_e( isset( $settings['show_widget_icon'] ) && true === $settings['show_widget_icon'] ? 'checked' : '' ); ?> type="checkbox" id="show_widget" class="elex-rqust-quote-check-sec-input-widget">
								<div class="elex-switch-icon round "></div>
							</label>
						</div>
					</div>
				</div>


				<div class="elex-rqust-quote-check-content-widget">
					<!-- widget color on hover -->
					<div class="row align-items-center">
						<div class="col-lg-4 col-md-6 mb-3 ">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e( 'Quote List Color On Hover', 'elex-request-a-quote' ); ?></h6>
								<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
								title="<?php esc_html_e('Select the color of the widget when a customer hovers over it.', 'elex-request-a-quote'); ?>"
								>

									<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
										<g id="tt" transform="translate(-384 -226)">
											<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)" fill="#f5f5f5" stroke="#000" stroke-width="1">
												<circle cx="13" cy="13" r="13" stroke="none"></circle>
												<circle cx="13" cy="13" r="12.5" fill="none"></circle>
											</g>
											<text id="_" data-name="?" transform="translate(392 247)" font-size="20" font-family="Roboto-Bold, Roboto" font-weight="700">
												<tspan x="0" y="0">?</tspan>
											</text>
										</g>
									</svg>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 mb-3 ">
							<div class="d-flex gap-2 ">
								<input id="widget_hex" name="widget_color" type="text " value="<?php esc_html_e( $settings['widget_color'] ); ?>" class="form-control ">
								<input id="widget_button_color" value="<?php esc_html_e( $settings['widget_color'] ); ?>" type="color" class="fs-5 form-control form-control-color p-0 border-0">
							</div>
						</div>
					</div>
					<!-- Quote List Icon Position -->
					<div class="row align-items-center mb-3">
						<div class="col-lg-4 col-md-6">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e( 'Quote List Icon Type', 'elex-request-a-quote' ); ?></h6>
								<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
								title="<?php esc_html_e('You may face compatibility issues with specific themes when Fixed icon type is chosen.', 'elex-request-a-quote'); ?>"
								>
									<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
										<g id="tt" transform="translate(-384 -226)">
											<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)" fill="#f5f5f5" stroke="#000" stroke-width="1">
												<circle cx="13" cy="13" r="13" stroke="none"></circle>
												<circle cx="13" cy="13" r="12.5" fill="none"></circle>
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
							<select name="quote_list_icon_position" class="form-select">
								 <option value="float" <?php echo isset( $settings['quote_list_icon_position'] ) &&  'float' === $settings['quote_list_icon_position'] ? 'selected' : ''; ?>><?php esc_html_e( 'Float', 'elex-request-a-quote' ); ?></option>
								<option value="fixed" <?php echo isset( $settings['quote_list_icon_position'] ) &&  'fixed'  === $settings['quote_list_icon_position'] ? 'selected' : ''; ?>><?php esc_html_e( 'Fixed', 'elex-request-a-quote' ); ?></option>
								
							</select>
						</div>
					</div>
					<!-- Show Button Label Next to Icon -->
					<div class="row align-items-center ">
						<div class="col-12 ">
							<div class="row mb-3 elex-button-label-quote-icon">
								<div class="col-lg-4 col-md-6">
									<div class="d-flex justify-content-between align-items-center">
										<h6 class="mb-0"><?php esc_html_e( 'Show Button Label Next to Icon', 'elex-request-a-quote' ); ?></h6>
										<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="Turn it on or off if you want to display or hide the widget label next to the icon.">

											<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
												<g id="tt" transform="translate(-384 -226)">
													<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)" fill="#f5f5f5" stroke="#000" stroke-width="1">
														<circle cx="13" cy="13" r="13" stroke="none">
														</circle>
														<circle cx="13" cy="13" r="12.5" fill="none">
														</circle>
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
										<input type="checkbox" name="show_button_label" <?php echo esc_html_e( isset( $settings['show_button_label'] ) && true === $settings['show_button_label'] ? 'checked' : '' ); ?> class="elex-rqust-quote-widget-button-label-check">
										<div class="elex-switch-icon round"></div>
									</label>
								</div>
							</div>


							<div class="elex-rqust-quote-widget-button-label-content">
								<!-- Button Label -->
								<div class="row align-items-center mb-3 elex-button-label-quote-icon">
									<div class="col-lg-4 col-md-6  ">
										<div class="d-flex justify-content-between align-items-center">
											<h6 class="mb-0"><?php esc_html_e( 'Button Label', 'elex-request-a-quote' ); ?></h6>
											<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
											title="<?php esc_html_e('Please enter the text that you would like to display as a button label.', 'elex-request-a-quote'); ?>"
											>

												<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
													<g id="tt" transform="translate(-384 -226)">
														<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)" fill="#f5f5f5" stroke="#000" stroke-width="1">
															<circle cx="13" cy="13" r="13" stroke="none">
															</circle>
															<circle cx="13" cy="13" r="12.5" fill="none">
															</circle>
														</g>
														<text id="_" data-name="?" transform="translate(392 247)" font-size="20" font-family="Roboto-Bold, Roboto" font-weight="700">
															<tspan x="0" y="0">?</tspan>
														</text>
													</g>
												</svg>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-6 mb-3 ">
										<input  name="button_label" type="text " value="<?php echo esc_html_e( isset( $settings['button_label'] ) ? $settings['button_label'] : 'Quote List' ); ?>" class="form-control " placeholder="Quote List">
									</div>

								</div>
							</div>
						</div>

					</div>



					<!-- Show List Pop Up on Hover -->
					<div class="row align-items-center mb-3">
						<div class="col-lg-4 col-md-6">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e( 'Show List Pop Up on Hover', 'elex-request-a-quote' ); ?></h6>
								<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
								title="<?php esc_html_e('Turn it on or off if you want to display or hide the list of products in the quote list by hovering over the quote list widget.', 'elex-request-a-quote'); ?>"
								
								>

									<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
										<g id="tt" transform="translate(-384 -226)">
											<g id="Ellipse_1" data-name="Ellipse 1" transform="translate(384 226)" fill="#f5f5f5" stroke="#000" stroke-width="1">
												<circle cx="13" cy="13" r="13" stroke="none"></circle>
												<circle cx="13" cy="13" r="12.5" fill="none"></circle>
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
								<input id="show_list_popup_on_hover" type="checkbox" name="show_list_popup_on_hover" type="checkbox" <?php echo esc_html_e( isset( $settings['show_list_popup_on_hover'] ) && true === $settings['show_list_popup_on_hover'] ? 'checked' : '' ); ?>>
								<div class="elex-switch-icon round"></div>
							</label>
						</div>
					</div>

				</div>

			</div>
		</div>
		<button type="submit" name="submit" class="btn btn-primary "><?php esc_html_e( 'Save Changes', 'elex-request-a-quote' ); ?></button>
	</div>
</form>
