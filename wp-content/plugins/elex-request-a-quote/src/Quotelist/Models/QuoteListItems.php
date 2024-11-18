<?php

namespace Elex\RequestAQuote\Quotelist\Models;

class QuoteListItems {
	public $subtotal = 0;
	public $tax      = 0;
	public $total    = 0;
	public $quote_list;
	public $quote_list_id;


	public $data = array(
		'id'          => 0,
		'items'       => array(
			array(
				'product_id'   => 0,
				'title'        => '',
				'quantity'     => 0,
				'item_total'   => 0,
				'item_cost'    => 0,
				'image_url'    => '',
				'sku'          => '',
				'variation_id' => 0,
				'product_link' => '',
				'type'         => '',
				'child'        => '',
			),
				
		),
		'sub_total'   => 0,
		'tax'         => 0,
		'total'       => 0,
		'wc_currency' => null,
		'currency_position'=>'left'
	);


	public function __construct( $quote_list_data, $quote_list_id ) {
		$this->quote_list    = $quote_list_data;
		$this->quote_list_id = $quote_list_id;
	}

	public function get_list() {

		$this->data['items']             = self::get_items( $this->quote_list );
		$this->data['wc_currency']       = self::get_wc_currency();
		$this->data['id']                = $this->quote_list_id;
		$this->data['currency_position'] = self::get_currency_position();

		return $this->data;
	}

	public function get_currency_position() {
		return get_option( 'woocommerce_currency_pos' );
	}


	public function get_wc_currency() {
		return get_woocommerce_currency_symbol();
	}

	public function get_selected_attributes( $quote_list_id, $product_id ) {
		$stord_attribute_data = get_option( 'elex_variation_attributes', array() );

		if (isset($stord_attribute_data[$quote_list_id][$product_id])) {

		 $data = $stord_attribute_data[$quote_list_id][$product_id];

		}

		if (empty($data)) {
			return array();
		}
		$attributeValues = array();

		foreach ($data as $item) {
			if (isset($item['attribute_value'])) {
				$attributeValues[] = $item['attribute_value'];
			}
		}
	
		return implode(', ', $attributeValues);


	}
	public function get_items( $quote_list_data ) {
	  
		$items    = array();
		$subtotal = 0;
		$tax      = 0;

		foreach ( $quote_list_data  as $product_data ) {
			
			$product = wc_get_product( $product_data->product_id );
			if (empty($product)) {
				continue;
			}
			$image_id    = $product->get_image_id();
			$image_array = wp_get_attachment_image_src( $image_id, 'thumbnail' );
			$image_url   = isset( $image_array[0] ) ? $image_array[0] : ''; 
			
			if ( 'variable' === $product->get_type() ) {

				$variation           = new \WC_Product_Variation( $product_data->variation_id );
				$image_id            = $variation->get_image_id();
				$image_array         = wp_get_attachment_image_src( $image_id, 'thumbnail' );
				$image_url           = isset( $image_array[0] ) ? $image_array[0] : $image_url;
				$selected_attributes = $variation->get_attributes();
				$product_name        = $variation->get_name();
				$product_name        = count($selected_attributes) > 2 ? $product_name . ' ' . implode(',', $variation->get_variation_attributes()): $product_name;

			}
			$item_price     = ( 'variable' === $product->get_type() ) ? $variation->get_price() : $product->get_price();
			$subtotal      += (int) $product_data->quantity * (float) $item_price;
			$price_excl_tax = wc_get_price_excluding_tax( $product ); // price without VAT
			$price_incl_tax = wc_get_price_including_tax( $product );  // price with VAT
			$tax           += (int) ( $product_data->quantity ) * ( ( $price_incl_tax - $price_excl_tax ) ); // VAT amount
			$total          = '' !== $item_price ? (int) $product_data->quantity * $item_price : 0;
			array_push(
				$items,
				array(
					'product_id'   => $product_data->product_id,
					'title'        => ( 'variable' === $product->get_type() ) ? wp_strip_all_tags( $product_name ) : $product->get_title(),
					'item_cost'    => ( '' !== $item_price && 0 !== $item_price &&  '0' !== $item_price ) ? number_format( $item_price , 2) :0,
					'image_url'    => $image_url,
					'quantity'     => $product_data->quantity, 
					'sku'          => $product->get_sku(),
					'item_total'   => ( '' !== $total && 0 !== $total &&  '0' !== $total ) ? number_format( $total , 2) :0,
					'variation_id' => $product_data->variation_id,
					'product_link' => wp_kses_post( get_permalink( $product_data->product_id ) ), 
					'type'         => $product->get_type(),
					'child'        => false,
				) 
			);


			if ( $product->get_type() === 'composite') {

				$data_from_front_end = get_option('elex_composite_data', array());

				if (!array_key_exists($this->quote_list_id, $data_from_front_end)) {
					continue;
				}

				$data_from_front_end = $data_from_front_end[$this->quote_list_id];

				$combinedArray = [];
				foreach ($data_from_front_end as $array) {
					foreach ($array as $item) {

						$pid = $item['pid'];


						if (( !isset($combinedArray[$pid]) )) {

							$prod = \wc_get_product($pid);
							$type = $prod->get_type();
							if ('simple' != $type && empty($item['variation_id'])) {
								continue;
							}
							$combinedArray[$pid] = [
								'component_id' => $item['component_id'],
								'attribute_name' => [],
								'attribute_value' => [],

								'pid' => $item['pid'],
								'variation_id' => $item['variation_id'],

							];
						}

						if (!empty($item['attribute_name'])) {
							$combinedArray[$pid]['attribute_name'][]  = $item['attribute_name'];
							$combinedArray[$pid]['attribute_value'][] = $item['attribute_value'];

						}
					}
				}


				$finalArray   = array_values($combinedArray);
				$resultString = '';
				foreach ($finalArray as $myarray) {
					if (empty($myarray['variation_id'])) {
						continue;
					}

					$attributePairs = [];
					for ($i = 0; $i < count($myarray['attribute_name']); $i++) {
						$attributePairs[] = $myarray['attribute_name'][$i] . ': ' . $myarray['attribute_value'][$i];
					}

					$resultString = implode(', ', $attributePairs);

				}
				foreach ($product->get_components() as $key => $val) {
					$component_data = $val->get_data();

					$composite_default   = wc_get_product($component_data['default_id']);
					$component_id        = $component_data['component_id'];
					$selected_attributes = [];
					foreach ($finalArray as $variation_val) {
						if (!isset($pid)) {
							continue;
						}
						$pro  = wc_get_product($variation_val['pid']);
						$type = $pro->get_type();
						if ($component_id === $variation_val['component_id'] && 'simple' !== $type) {
							$variation_id = $variation_val['variation_id'];
							$obj          = array(
								'attribute_name' => $variation_val['attribute_name'],
								'attribute_value' => $variation_val['attribute_value'],
							);
							array_push($selected_attributes, $obj);

							$product_variation = new \WC_Product_Variation($variation_id);
							$product_name      = $product_variation->get_formatted_name();
							$image_id          = $product_variation->get_image_id();
							$image_array       = wp_get_attachment_image_src($image_id, 'thumbnail');
							$image_url         = isset($image_array[0]) ? $image_array[0] : '';

							if (isset($selected_attributes) && !empty($selected_attributes)) {
								$count = 0;
								foreach ($selected_attributes as $k => $v) {
									if ('' == $v && $count > 0) {
										$product_name = $product_name . ',' . ucfirst(wc_attribute_label($k)) . ':' . ucfirst($selected_attributes[$k]);
									} elseif ('' == $v && 0 === $count) {
										$product_name = $product_name . ucfirst(wc_attribute_label($k)) . ':' . ucfirst($selected_attributes[$k]) . ',';
									}
									$count++;
								}
							}
							array_push(
								$items,
								array(
									'product_id' => $component_data['default_id'],
									'title' => $component_data['title'] . ':' . wp_strip_all_tags($product_name) . '' . $resultString,
									'item_cost' => '',
									'image_url' => $image_url,
									'quantity' => $product_data->quantity,
									'sku' => $product->get_sku(),
									'item_total' => '',
									'variation_id' => $variation_id,
									'product_link' => wp_kses_post(get_permalink($component_data['default_id'])),
									'type' => $product->get_type(),
									'child' => true,
								)
							);
						} elseif ($component_id === $variation_val['component_id'] && 'simple' === $type) {

							array_push(
								$items,
								array(
									'product_id' => $variation_val['pid'],
									'title' => $component_data['title'] . ':' . $pro->get_title(),
									'item_cost' => '',
									'image_url' => $image_url,
									'quantity' => $product_data->quantity,
									'sku' => $product->get_sku(),
									'item_total' => '',
									'variation_id' => '',
									'product_link' => wp_kses_post(get_permalink($component_data['default_id'])),
									'type' => $product->get_type(),
									'child' => true,
								)
							);
						}

					}

				}
			}        
		}
		$this->data['sub_total'] =( '' !== $subtotal && 0 !== $subtotal &&  '0' !== $subtotal ) ? number_format( $subtotal , 2) :0;
		$this->data['tax']       = ( '' !== $tax && 0 !== $tax &&  '0' !== $tax ) ? number_format( $tax , 2) :0;
		$total                   = wc_prices_include_tax() ?  $subtotal : ( $subtotal + $tax );
		$this->data['total']     = ( '' !== $total && 0 !== $total &&  '0' !== $total ) ? number_format( $total , 2) :0;
		



		return $items;

	}

}
