<form method="POST">
	<?php wp_nonce_field( 'save_settings', 'req_settings_nonce' ); ?>
	<div class="p-2 pe-4">
		<div class="p-3">
			<div class="row">

				<div class="col-12">

					<!-- Show "Update List" Button -->
					<div class="row align-items-center mb-3">
						<div class="col-lg-4 col-md-6">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e( 'Show "Update List" Button', 'elex-request-a-quote' ); ?></h6>
								<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
								title="<?php esc_html_e('Turn On/Off if you want to show/hide update list button on the quote list page.', 'elex-request-a-quote'); ?>"
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
						<div class="col-lg-4 col-md-6">
							<label class="elex-switch-btn">
								<input id="update_list_button" onchange="" type="checkbox" name="update_list_button" value="" <?php echo ( isset( $settings['update_list_button'] ) && ( true === $settings['update_list_button'] ) ) ? 'checked' : ''; ?>>
								<div class="elex-switch-icon round"></div>
							</label>
						</div>
					</div>

					<!-- Show "Clear List" Button -->
					<div class="row mb-3 align-items-center">
						<div class="col-lg-4 col-md-6">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0"><?php esc_html_e( 'Show "Clear List" Button', 'elex-request-a-quote' ); ?></h6>
								<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
								title="<?php esc_html_e('Turn On/Off if you want to show/hide the clear list button on the quote list page.', 'elex-request-a-quote'); ?>"
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
						<div class="col-lg-4 col-md-6">
							<label class="elex-switch-btn">

								<input id="clear_list_button" onchange="" type="checkbox" name="clear_list_button" value="" <?php echo ( isset( $settings['clear_list'] ) && ( true === $settings['clear_list'] ) ) ? 'checked' : ''; ?>>
								<div class="elex-switch-icon round"></div>
							</label>
						</div>
					</div>

					<!-- Show "Add more Items" Button -->
					<div class="row align-items-center elex-rqust-quote-check-sec ">
						<div class="col-12 ">
							<div class="row mb-3">
								<div class="col-lg-4 col-md-6">
									<div class="d-flex justify-content-between align-items-center">
										<h6 class="mb-0"><?php esc_html_e( 'Add more Items Button', 'elex-request-a-quote' ); ?></h6>

										<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
										title="<?php esc_html_e('Turn On/Off if you want to show/hide add more items button on the quote list page.', 'elex-request-a-quote'); ?>"
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
								<div class="col-lg-4 col-md-6">
									<label class="elex-switch-btn ">
										<input class="elex-rqust-quote-check-sec-input-add-more-items" id="add_more_items" onchange="" type="checkbox" name="add_more_items" value="" <?php echo esc_html_e( isset( $settings['add_more_items_button'] ) && ( true === $settings['add_more_items_button'] ) ? 'checked' : '' ); ?>>
										<div class="elex-switch-icon round "></div>
									</label>
								</div>
							</div>


							<div class="elex-rqust-quote-check-content-add-more-items">
								<!--"Add More Items" Button label -->
								<div class="row align-items-center">
									<div class="col-lg-4 col-md-6 mb-3 ">
										<div class="d-flex justify-content-between align-items-center">
											<h6 class="mb-0"><?php esc_html_e( 'Add more Items Button Label', 'elex-request-a-quote' ); ?></h6>
											<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
											title="<?php esc_html_e("Enter the label text for 'Add more items' button on the quote list page.", 'elex-request-a-quote'); ?>"
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
									<div class="col-lg-4 col-md-6 mb-3 ">
										<div class="form-group ">
											<input name="add_more_item_btn_label" type="text " value="<?php esc_html_e( isset( $settings['add_more_items_button_label'] ) && ! empty( $settings['add_more_items_button_label'] ) ? $settings['add_more_items_button_label'] : 'Add More items' ); ?> " class="form-control" placeholder="Add More items">
										</div>
									</div>
								</div>


								<!--"Add More Items" Button Redirect page -->
								<div class="row align-items-center">
									<div class="col-lg-4 col-md-6 mb-3 ">
										<div class="d-flex justify-content-between align-items-center">
											<h6 class="mb-0"><?php esc_html_e( 'Add More Items Button Redirect Page', 'elex-request-a-quote' ); ?></h6>
											<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 
											title="<?php esc_html_e('Select the page where you want the customer to be redirected to upon clicking the add more items button' , 'elex-request-a-quote'); ?>"
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
									<div class="col-lg-4 col-md-6 mb-3 ">
										<div class="form-group ">
											<select name="add_more_item_btn_redirection" id="add_more_item_btn_redirection" class="form-select">
											<?php foreach ( $settings['pages'] as $url => $page_name ) : ?>
												<option value="<?php echo esc_url( $url ); ?>" <?php echo  esc_html_e( ( ! empty( $settings['add_more_items_button_redirection'] ) && ( $url === $settings['add_more_items_button_redirection'] ) ? 'selected' :  '' ) ); ?>><?php echo esc_html_e( $page_name ); ?></option>
											<?php endforeach; ?>
										</select>
										</div>
									</div>
								</div>
							</div>
								<!-- Send Reminder Email in Every -->
								<div class="row mb-3 align-items-center">
											<div class="col-lg-4 col-md-6">
												<div class="d-flex justify-content-between align-items-center">
													<h6 class="mb-0">
													<?php esc_html_e( 'If there is No Activity on Quote by Customer, Send Reminder Email in Every', 'elex-request-a-quote' ); ?>
													<span class = "elex_raq_go_premium_color">
															<?php 
															esc_html_e( '[Premium]', 'elex-request-a-quote' );
															?>
														</span>
													</h6>
													<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
													title="<?php esc_html_e('Set the number of days for sending a reminder email to the customer. Leave blank to skip automatic reminders.', 'elex-request-a-quote'); ?>"
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
													<input 
													disabled
													name="" 
													value=""
													type="number" class="form-control border-0" placeholder="" 
														aria-describedby="basic-addon2">
													<div class="input-group-append ">
														<span class="input-group-text px-4 border-0 rounded-0 rounded-end"><?php esc_html_e( 'Days', 'elex-request-a-quote' ); ?></span>
													</div>
													<input name="" 
													disabled
													value=""
													type="number" class="form-control border-0" placeholder="" 
														aria-describedby="basic-addon2">
													<div class="input-group-append ">
													<span class="input-group-text px-4 border-0 rounded-0 rounded-end"><?php esc_html_e( 'Trials', 'elex-request-a-quote' ); ?></span>
												</div>
												</div>
											</div>
										</div>

										<!-- Allow_customer to send counter propasal -->
										<div class="row align-items-center mb-3">
											<div class="col-lg-4 col-md-6">
												<div class="d-flex justify-content-between align-items-center">
													<h6 class="mb-0"><?php esc_html_e( 'Allow Customer to Send Counter Proposal', 'elex-request-a-quote' ); ?>
													<span class = "elex_raq_go_premium_color">
															<?php 
															esc_html_e( '[Premium]', 'elex-request-a-quote' );
															?>
														</span>
													</h6>
													<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
													title="<?php esc_html_e('Turn on if you want to allow customer to send Counter Proposal.', 'elex-request-a-quote'); ?>"
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
											<div class="col-lg-4 col-md-6">
												<label class="elex-switch-btn">
													<input  disabled id="counter_proposal" onchange="" type="checkbox" name="" value=""  >
													<div class="elex-switch-icon round"></div>
												</label>
											</div>
										</div>
									
										<!-- Allow_customer to download pdf -->
										<div class="row align-items-center mb-3">
											<div class="col-lg-4 col-md-6">
												<div class="d-flex justify-content-between align-items-center">
													<h6 class="mb-0"><?php esc_html_e( 'Allow Customer to Download PDF', 'elex-request-a-quote' ); ?>
													<span class = "elex_raq_go_premium_color">
															<?php 
															esc_html_e( '[Premium]', 'elex-request-a-quote' );
															?>
														</span>
												
												</h6>
													<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top"
													title="<?php esc_html_e('Turn on if you want to allow customer to download PDF.', 'elex-request-a-quote'); ?>"
													
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
											<div class="col-lg-4 col-md-6">
												<label class="elex-switch-btn">
													<input id="download_pdf" onchange="" type="checkbox" name="" value="" disabled>
													<div class="elex-switch-icon round"></div>
												</label>
											</div>
										</div>

							<button name="submit" type="submit" class="btn btn-primary"><?php esc_html_e( 'Save Changes', 'elex-request-a-quote' ); ?></button>

						</div>
					</div>
				</div>
			</div>

		</div>

	</div>
</form>
