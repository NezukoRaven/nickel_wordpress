<style>
	.box14 {
		width: 100%;
		margin-top: 2px;
		min-height: 310px;
		margin-right: 400px;
		padding: 10px;
		z-index: 1;
		right: 0px;
		float: left;
		background: -webkit-gradient(linear, 0% 20%, 0% 92%, from(#fff), to(#f3f3f3), color-stop(.1, #fff));
		border: 1px solid #ccc;
		-webkit-border-radius: 60px 5px;
		-webkit-box-shadow: 0px 0px 35px rgba(0, 0, 0, 0.1) inset;
	}

	.box14 h3 {
		text-align: center;
		margin: 2px;
	}

	.box14 p {
		text-align: center;
		margin: 2px;
		border-width: 1px;
		border-style: solid;
		padding: 5px;
		border-color: rgb(204, 204, 204);
	}

	.box14 span {
		background: #fff;
		padding: 5px;
		display: block;
		box-shadow: green 0px 3px inset;
		margin-top: 10px;
	}

	.box14 img {
		margin-top: 5px;
	}

	.table-box-main {
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
		transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
	}

	.table-box-main:hover {
		box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
	}

	span ul li {
		margin: 4px;
	}

	.marketing_logos {
		width: 300px;
		height: 300px;
		border-radius: 10px;
	}

	.marketing_redirect_links {
		padding: 0px 2px !important;
		background-color: #fcb800;
		height: 52px;
		font-weight: 600 !important;
		font-size: 18px !important;
		min-width: 210px;
	}
</style>
<div class="elex-ab-cart-wrap">

	<!-- content -->
	<div class="elex-ab-cart-content">


		<!-- main content -->
		<div class="elex-ab-cart-main">
			<div class="eacmcm p-3 pt-0">


				<div class="p-3">

					<div class="box14 table-box-main">

						<center style="margin-top: 20px;">
							<div class="panel panel-default" style="margin: 20px;">
								<div class="panel-body">
									<div class="row">
										<div class="d-flex align-items-center justify-content-center elex-license-like-img-container col-md-5">
											<a target="_blank" href="https://elextensions.com/plugin/woocommerce-request-a-quote-plugin/?utm_source=plugin-settings-related&utm_medium=wp-admin&utm_campaign=in-prod-ads">
												<img src="<?php echo esc_html( ELEX_RAQ_IMAGES . 'request_a_quote.png' ); ?>" class="marketing_logos">
											</a>
											<br />
										</div>
										<div class="col-md-5">
											<ul style="list-style-type:disc;">
												<p><?php esc_html_e( 'Note: Basic version supports only few features.', 'elex-request-a-quote' ); ?></p>
												<p style="color:red;"><strong><?php esc_html_e( 'Your business is precious! Go Premium to get below features.', 'elex-request-a-quote' ); ?></strong></p>
												<p style="text-align:left"><?php esc_html_e( ' -  Product-based exclusion for Request a Quote.', 'elex-request-a-quote' ); ?>
													<br>
													<?php esc_html_e( ' - User role-based exclusion for Request a Quote.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Disable Request a Quote option for unregistered users.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Exclude Request a Quote based on the product stocks.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( "- Product-based exclusion from the 'Hide Add to Cart and Price' option.", 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( "- User role-based exclusion from the 'Hide Add to Cart and Price' option.", 'elex-request-a-quote' ); ?>
													<br>
													<?php esc_html_e( '- Quote list customization option for an empty quote list page.', 'elex-request-a-quote' ); ?>
													<br>
													<?php esc_html_e( '- Product table customization option for the quote list page.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Quote Expiry  feature as per the number of days set by the Admin.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( "- Accepted Payment Gateway feature to accept the payment method of Shop owner's choice.", 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Option to change the "Add to Quote " Button Position Inline with the "Add to Cart" Button.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Quote Button on Cart Page.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Option to set the Add to Quote Button Behaviour to redirect to Quote List Page.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Predefined Templates for the Emails.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Send Automatic Reminder emails to the customer as per the number of days set by the admin,when there is no activity from the customer.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Allow Customer to Send Counter Proposal from their My Account Page.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Allow Customer to download Quote PDF from their My Account Page.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Accept the Preferred Price/Percentage from the Customer during Quote Submission.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Attach PDF or image file to Quote Approved/Rejected/Negotiation mails from the Order Page.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- AutoComplete Quote Form for Registered User.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- Create WordPress User when the Guest User submits the Quote Request.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- reCAPTCHA verification for the Quote Submission.', 'elex-request-a-quote' ); ?><br>
													<?php esc_html_e( '- All Buttons Customization including Color and Label', 'elex-request-a-quote' ); ?><br>


													
													
													


													
												</p>
											</ul>
											<center> <a href="https://elextensions.com/knowledge-base/how-to-set-up-elex-woocommerce-request-a-quote-plugin/" target="_blank" class="button button-primary"><?php esc_html_e( 'Documentation', 'elex-request-a-quote' ); ?></a></center>
										</div>
									</div>
								</div>
							</div>
						</center>
						<!-- </div> -->

						<h6 class="mb-4"><b><?php esc_html_e( 'You May Also like', 'elex-request-a-quote' ); ?></b></h6>
						<div class="row">
							<div class="col-md-3 col-6">
								<div class="elex-license-like-img-container w-100  mb-3">
									<a target="_blank" href="https://elextensions.com/plugin/wsdesk-wordpress-support-desk-plugin/?utm_source=plugin-settings-related&utm_medium=wp-admin&utm_campaign=in-prod-ads" class="elex-license-like-img-container w-100 d-flex">
										<img src="<?php echo esc_url( ELEX_RAQ_IMAGES . 'wsdesk.png' ); ?>" alt="" class="w-100">
									</a>
								</div>
							</div>
							<div class="col-md-3 col-6">
								<div class="elex-license-like-img-container w-100 mb-3">
									<a target="_blank" href="https://elextensions.com/plugin/woocommerce-google-product-feed-plugin/?utm_source=plugin-settings-related&utm_medium=wp-admin&utm_campaign=in-prod-ads" class="elex-license-like-img-container w-100 d-flex">
										<img src="<?php echo esc_url( ELEX_RAQ_IMAGES . 'google_feed.png' ); ?>" alt="" class="w-100">
									</a>
								</div>
							</div>
							<div class="col-md-3 col-6">
								<div class="elex-license-like-img-container w-100  mb-3">
									<a target="_blank" href="https://elextensions.com/plugin/woocommerce-catalog-mode-wholesale-role-based-pricing/?utm_source=plugin-settings-related&utm_medium=wp-admin&utm_campaign=in-prod-ads" class="elex-license-like-img-container w-100 d-flex">
										<img src="<?php echo esc_url( ELEX_RAQ_IMAGES . 'catalog_mode.png' ); ?>" alt="" class="w-100">
									</a>
								</div>
							</div>
							<div class="col-md-3 col-6">
								<div class="elex-license-like-img-container w-100  mb-3">
									<a target="_blank" href="https://elextensions.com/plugin/wordpress-embed-youtube-video-gallery/?utm_source=plugin-settings-related&utm_medium=wp-admin&utm_campaign=in-prod-ads" class="elex-license-like-img-container w-100 d-flex">
										<img src="<?php echo esc_url( ELEX_RAQ_IMAGES . 'youtube.png' ); ?>" alt="" class="w-100">
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
