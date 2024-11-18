	<table
		style="width: 80%;max-width: 700px; min-width: 350px; margin: 0 auto; background-color: #fff; padding: 20px;">
		<tbody>
		   
			<tr>
				<td style="padding: 0 0 30px 0">
					<table style="width: 100%;">
						<tr>
							<td style="padding-right:25px;width: 50%;">
								<table style="width: 100%; ">
									<tr>
										<td style="text-align:center">
											<img style="width: 200px;" src="<?php echo  esc_url( $settings['company_logo'] ); ?>" alt="">
										</td>
									</tr>
								</table>
							</td>

							<td style="padding-left:25px;width: 50%;">
								<table style="width: 100%;">
									<tr >
										<td style="border-bottom: 1px solid #c1c1c1; margin-bottom: 10px;">
											<h2 style="margin-bottom: 5px ; font-size:24px "><?php esc_html_e( 'Quote #213' , 'elex-request-a-quote' ); ?></h2>
										</td>
									</tr>
									<tr>
										<td>
											<h5 style="margin:5px 0 5px 0; font-size:18px "><?php esc_html_e( $settings['sent_to_customer']['quote_requested_email_template']['heading'] ); ?></h5>
										</td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Issued By: Max Smith' , 'elex-request-a-quote' ); ?></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Issued Date: 02-March-2022' , 'elex-request-a-quote' ); ?></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Expiry Date: 04-March-2022' , 'elex-request-a-quote' ); ?></td>
									</tr>
									<tr style=" margin-bottom: 10px;">
										<td style="border-bottom: 1px solid #c1c1c1;">
											<h4 style="margin:10px 0 5px 0; "><?php esc_html_e( 'Customer Details' , 'elex-request-a-quote' ); ?></h4>
										</td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Will Miller' , 'elex-request-a-quote' ); ?></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Kelly.elliot@gmail.com' , 'elex-request-a-quote' ); ?></td>
									</tr>
									<tr>
										<td><?php esc_html_e( '(190)081-0336' , 'elex-request-a-quote' ); ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td style="width: 100%; padding-bottom: 10px;">
					<table style="width: 100%;text-align: start; border-collapse: collapse;;border-bottom: 1px solid #c1c1c1;">
						<thead>
							<th style="text-align: start; width: calc(100% - 300px);border-bottom: 1px solid #c1c1c1">
								Products</th>
							<th style="text-align: start; width: 100px;border-bottom: 1px solid #c1c1c1">Price</th>
							<th style="text-align: start; width: 100px; border-bottom: 1px solid #c1c1c1">Qty.</th>
							<th style="text-align: start; width: 100px;border-bottom: 1px solid #c1c1c1">Subtotal</th>
						</thead>
						<tbody>
							<tr>
								<td >
									<table>
										<tr>
											<td>
												<img src="https://dunnes.btxmedia.com/pws/client/images/catalogue/products/7611510/zoom/7611510_charcoal.jpg"
													style="width:50px;height:50px;object-fit: cover;float: left; margin-right: 10px;"
													alt="">
											</td>
											<td>
											<p style="margin: 5px 0"><?php esc_html_e( 'Product Name' , 'elex-request-a-quote' ); ?></p>
												<p style="margin: 5px 0"><?php esc_html_e( 'SKU:s123432' , 'elex-request-a-quote' ); ?></p>
											</td>
										</tr>
									</table>
								</td>
								<td>$10</td>
								<td>2</td>
								<td>$20</td>
							</tr>
							<tr>
								<td>
									<table>
										<tr>
											<td>
												<img src="https://dunnes.btxmedia.com/pws/client/images/catalogue/products/7611510/zoom/7611510_charcoal.jpg"
													style="width:50px;height:50px;object-fit: cover;float: left; margin-right: 10px;"
													alt="">
											</td>
											<td>
											<p style="margin: 5px 0"><?php esc_html_e( 'Product Name' , 'elex-request-a-quote' ); ?></p>
												<p style="margin: 5px 0"><?php esc_html_e( 'SKU:s123432' , 'elex-request-a-quote' ); ?></p>
											</td>
										</tr>
									</table>
								</td>
								<td>$10</td>
								<td>2</td>
								<td>$20</td>
							</tr>

						</tbody>
					</table>
				</td>
			</tr>

			<tr>
				<td style="padding: 5px 0;">
					<table style="margin-left: auto;">
						<tr>
							<td style="width: 100px;"><?php esc_html_e( 'Subtotal' , 'elex-request-a-quote' ); ?></td>
							<td style="width: 100px;">$40</td>
						</tr>
						<tr>
							<td style="width: 100px;"><?php esc_html_e( 'Fees' , 'elex-request-a-quote' ); ?></td>
							<td style="width: 100px;">$4</td>
						</tr>
						<tr>
							<td style="width: 100px;color: #10518D;"><?php esc_html_e( 'Total' , 'elex-request-a-quote' ); ?></td>
							<td style="width: 100px;color: #10518D;">$44</td>
						</tr>
					</table>
				</td>
			</tr>


			<tr>
				<td style="padding: 20px 0 30px 0;text-align: end;">
					
					<button
						style="padding: 15px; width: 170px;margin: 0 5px; border-radius: 6px; color:#fff; background-color: #10518D; border: 1px solid #10518D;box-shadow: 3px 3px 16px #10518D80;"><?php esc_html_e( 'Accept & Pay' , 'elex-request-a-quote' ); ?></button>
				</td>
			</tr>

			<tr>
				<td>
					<h6 style="font-size: 16px; margin: 10px 0; font-weight: 600;"><?php esc_html_e( 'Terms & Conditions' , 'elex-request-a-quote' ); ?></h6>
					<p style="font-size: 16px; margin: 10px 0;font-weight: 500;">
					<?php 
					esc_html_e( 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt
						ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos.' , 'elex-request-a-quote' ); 
					?>
					</p>
				</td>
			</tr>

			<tr>
				<td>
					<h6 style="font-size: 16px; margin: 0px 0 10px 0; font-weight: 500;color: #c1c1c1;text-align: center;"><?php esc_html_e( 'Powered by ELEX Request a Quote' , 'elex-request-a-quote' ); ?></h6>
				</td>
			</tr>
		</tbody>
	</table>
