<form method="POST">
   <?php wp_nonce_field( 'save_settings', 'req_settings_nonce' ); ?>
   <div class="elex-rqst-quote-wrap">
   <!-- content -->
   <div class="elex-rqst-quote-content d-flex">
	  <!-- main content -->
	  <div class="elex-rqst-quote-main">
		 <div class="p-3">
			<div class="row">
			   <div class="tab-content" id="pills-tabContent">
				  <div class="tab-pane fade show active"
					 id="elex-raq-customize-add-to-quote-btn-tab-content" role="tabpanel"
					 aria-labelledby="elex-raq-customize-add-to-quote-btn-tab">
					 <div class="row">
						<div class="col-12">
						
							  <!-- Clear List -->
							  <div class="row align-items-center mb-3">
							  <div class="col-lg-4 col-md-6">
								 <div class="d-flex justify-content-between align-items-center">
									<h6 class="mb-0"><?php esc_html_e('Clear List Button Label', 'elex-request-a-quote'); ?>
									
								</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 	title="<?php esc_html_e("Enter custom label text for 'Clear List' Button", 'elex-request-a-quote'); ?>">
									   <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
										  viewBox="0 0 26 26">
										  <g id="tt" transform="translate(-384 -226)">
											 <g id="Ellipse_1" data-name="Ellipse 1"
												transform="translate(384 226)" fill="#f5f5f5"
												stroke="#000" stroke-width="1">
												<circle cx="13" cy="13" r="13" stroke="none" />
												<circle cx="13" cy="13" r="12.5" fill="none" />
											 </g>
											 <text id="_" data-name="?"
												transform="translate(392 247)" font-size="20"
												font-family="Roboto-Bold, Roboto" font-weight="700">
												<tspan x="0" y="0">?</tspan>
											 </text>
										  </g>
									   </svg>
									</div>
								 </div>
							  </div>
							  <div class="col-lg-4 col-md-6">
								 <input disabled placeholder="Clear List" value="Clear List" type="text" name="clear_list_label" id="clear_list_label" class=" clear_list_label form-control">
							  </div>
						   </div>
						
						   <!-- send Request  -->
						   <div class="row align-items-center mb-3">
							  <div class="col-lg-4 col-md-6">
								 <div class="d-flex justify-content-between align-items-center">
									<h6 class="mb-0"><?php esc_html_e('Send Request Button Label', 'elex-request-a-quote'); ?>
									   
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 	title="<?php esc_html_e("Enter custom label text for 'Send Request' Button", 'elex-request-a-quote'); ?>">
									   <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
										  viewBox="0 0 26 26">
										  <g id="tt" transform="translate(-384 -226)">
											 <g id="Ellipse_1" data-name="Ellipse 1"
												transform="translate(384 226)" fill="#f5f5f5"
												stroke="#000" stroke-width="1">
												<circle cx="13" cy="13" r="13" stroke="none" />
												<circle cx="13" cy="13" r="12.5" fill="none" />
											 </g>
											 <text id="_" data-name="?"
												transform="translate(392 247)" font-size="20"
												font-family="Roboto-Bold, Roboto" font-weight="700">
												<tspan x="0" y="0">?</tspan>
											 </text>
										  </g>
									   </svg>
									</div>
								 </div>
							  </div>
							  <div class="col-lg-4 col-md-6">
								 <input disabled placeholder="Send Request" value="Send Request" type="text" name="send_request_label" id="send_request_label" class=" send_request_label form-control">
							  </div>
						   </div>
						   <!-- Update List -->
						   <div class="row align-items-center mb-3">
							  <div class="col-lg-4 col-md-6">
								 <div class="d-flex justify-content-between align-items-center">
									<h6 class="mb-0"><?php esc_html_e('Update List Button Label', 'elex-request-a-quote'); ?>
									   
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 	title="<?php esc_html_e("Enter custom label text for 'Update List' Button", 'elex-request-a-quote'); ?>">
									   <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
										  viewBox="0 0 26 26">
										  <g id="tt" transform="translate(-384 -226)">
											 <g id="Ellipse_1" data-name="Ellipse 1"
												transform="translate(384 226)" fill="#f5f5f5"
												stroke="#000" stroke-width="1">
												<circle cx="13" cy="13" r="13" stroke="none" />
												<circle cx="13" cy="13" r="12.5" fill="none" />
											 </g>
											 <text id="_" data-name="?"
												transform="translate(392 247)" font-size="20"
												font-family="Roboto-Bold, Roboto" font-weight="700">
												<tspan x="0" y="0">?</tspan>
											 </text>
										  </g>
									   </svg>
									</div>
								 </div>
							  </div>
							  <div class="col-lg-4 col-md-6">
								 <input disabled placeholder="Update List" value="Update List" type="text" name="update_list_label" id="update_list_label" class=" update_list_label form-control">
							  </div>
						   </div>
							  <!-- View Quote List -->
							  <div class="row align-items-center mb-3">
							  <div class="col-lg-4 col-md-6">
								 <div class="d-flex justify-content-between align-items-center">
									<h6 class="mb-0"><?php esc_html_e('View Quote List Button Label', 'elex-request-a-quote'); ?>
									   
									</h6>
									<div type="button" class="" data-bs-toggle="tooltip" data-bs-placement="top" 	title="<?php esc_html_e("Enter custom label text for 'View Quote List' Button", 'elex-request-a-quote'); ?>">
									   <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
										  viewBox="0 0 26 26">
										  <g id="tt" transform="translate(-384 -226)">
											 <g id="Ellipse_1" data-name="Ellipse 1"
												transform="translate(384 226)" fill="#f5f5f5"
												stroke="#000" stroke-width="1">
												<circle cx="13" cy="13" r="13" stroke="none" />
												<circle cx="13" cy="13" r="12.5" fill="none" />
											 </g>
											 <text id="_" data-name="?"
												transform="translate(392 247)" font-size="20"
												font-family="Roboto-Bold, Roboto" font-weight="700">
												<tspan x="0" y="0">?</tspan>
											 </text>
										  </g>
									   </svg>
									</div>
								 </div>
							  </div>
							  <div class="col-lg-4 col-md-6">
								 <input placeholder="View Quote List" disabled value="View Quote List" type="text" name="view_quote_list_label" id="view_quote_list_label" class=" view_quote_list_label form-control">
							  </div>
						   </div>
						 
						   <!-- Button colour -->
						   <div class="fs-5 fw-bold mb-4"><?php esc_html_e('Button Color', 'elex-request-a-quote'); ?>
						   </div>
						   <div class="row mb-3">
							  <div class="col-md-3">
								 <div class="h-6 fw-bold mb-2"><?php esc_html_e('Border', 'elex-request-a-quote'); ?></div>
								 <div class="d-flex gap-2 elex-raq-custom-color-picker-div">
									<input disabled type="color"  name="quote_button_border_color" id="quote_button_border_color"
									   value="#10518D"
									   pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   class="form-control form-control-color p-0 fs-4 elex-raq-colorpicker">
									<input disabled name="quote_button_border_hex" id="quote_button_border_hex" value="#10518d" placeholder="#10518d" type="text" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   class="form-control elex-raq-hexcolor">
								 </div>
							  </div>
							  <div class="col-md-3">
								 <div class="h-6 fw-bold mb-2"><?php esc_html_e('Background', 'elex-request-a-quote'); ?></div>
								 <div class="d-flex gap-2 elex-raq-custom-color-picker-div">
									<input disabled name="quote_button_color" type="color" id="quote_button_color"  value="#10518D"
									   pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   class="form-control form-control-color p-0 fs-4 elex-raq-colorpicker">
									<input disabled name="quote_button_hex" type="text" id="quote_button_hex"  value="#10518d"  placeholder="#10518d" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   class="form-control elex-raq-hexcolor">
								 </div>
							  </div>
							  <div class="col-md-3">
								 <div class="h-6 fw-bold mb-2"><?php esc_html_e('Text', 'elex-request-a-quote'); ?></div>
								 <div class="d-flex gap-2 elex-raq-custom-color-picker-div">
									<input disabled name="quote_button_text_color"  type="color"  id="quote_button_text_color" value="#FFFFFF"
									   pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   class="form-control form-control-color p-0 fs-4 elex-raq-colorpicker">
									<input disabled placeholder="#FFFFFF"  name="quote_button_text_hex"  type="text"  id="quote_button_text_hex" value="#FFFFFF" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   class="form-control elex-raq-hexcolor">
								 </div>
							  </div>
							  <div class="col-md-3">
								 <div class="h-6 fw-bold mb-2"><?php esc_html_e('Preview', 'elex-request-a-quote'); ?></div>
								 <button disabled  id="" class="preview_add_to_quote btn btn-outline-primary"><?php esc_html_e('Button Label', 'elex-request-a-quote'); ?></button>
							  </div>
						   </div>
						   <div class="row mb-3">
							  <div class="col-md-3">
								 <div class="h-6 fw-bold mb-2 text-secondary"><?php esc_html_e('On hover', 'elex-request-a-quote'); ?></div>
								 <div class="d-flex gap-2 elex-raq-custom-color-picker-div">
									<input disabled name="border_hover_color" type="color" id="border_hover_color" value="#0c4071"
									   pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   class="form-control form-control-color p-0 fs-4 elex-raq-colorpicker">
									<input disabled name="border_hover_hex" type="text" id="border_hover_hex" value="#0c4071" 
									   pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   placeholder="#0c4071"
									   class="form-control elex-raq-hexcolor">
								 </div>
							  </div>
							  <div class="col-md-3">
								 <div class="h-6 fw-bold mb-2 text-secondary"><?php esc_html_e('On hover', 'elex-request-a-quote'); ?></div>
								 <div class="d-flex gap-2 elex-raq-custom-color-picker-div">
									<input disabled name="background_hover_color" type="color" id="background_hover_color"  value="#0c4071"
									   pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   class="form-control form-control-color p-0 fs-4 elex-raq-colorpicker">
									<input disabled name="background_hover_hex" type="text"   id="background_hover_hex"  value="#0c4071"
									   pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   placeholder="#0c4071"
									   class="form-control elex-raq-hexcolor">
								 </div>
							  </div>
							  <div class="col-md-3">
								 <div class="h-6 fw-bold mb-2 text-secondary"><?php esc_html_e('On hover', 'elex-request-a-quote'); ?></div>
								 <div class="d-flex gap-2 elex-raq-custom-color-picker-div">
									<input disabled name="text_hover_color" type="color" id="text_hover_color" value="#FFFFFF"
									   pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   class="form-control form-control-color p-0 fs-4 elex-raq-colorpicker">
									<input  name="text_hover_hex" id="text_hover_hex" type="text"
									   value="#FFFFFF" 
									   pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"
									   placeholder="#FFFFFF"
									   disabled
									   class="form-control elex-raq-hexcolor">
								 </div>
							  </div>
							  <div class="col-md-3 d-flex align-items-end">
								 <button disabled class="preview_add_to_quote btn btn-outline-primary"><?php esc_html_e('Button Label', 'elex-request-a-quote'); ?></button>
							  </div>
						   </div>
						</div>
					 </div>
				  </div>
			   </div>
			</div>
		 </div>
	  </div>
   </div>
</form>
