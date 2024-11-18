<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Accounts;

use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base;
use QuadLayers\IGG\Models\Accounts as Models_Accounts;

/**
 * Api_Rest_Accounts_Get Class
 */
class Get extends Base {

	protected static $route_path = 'accounts';

	public function callback( \WP_REST_Request $request ) {

		try {

			$account_id = trim( $request->get_param( 'id' ) );

			if ( ! $account_id ) {

				$response = Models_Accounts::instance()->get_all() ?? array();

				return $this->handle_response( $response );
			}

			$response = Models_Accounts::instance()->get( $account_id );

			if ( ! $response ) {
				throw new \Exception( sprintf( esc_html__( 'Account %s not found.', 'insta-gallery' ), $account_id ), 412 );
			}

			return $this->handle_response( $response );

		} catch ( \Exception $e ) {
			$response = array(
				'code'    => $e->getCode(),
				'message' => $e->getMessage(),
			);
			return $this->handle_response( $response );
		}
	}

	public static function get_rest_args() {
		return array(
			'id' => array(
				'required'          => false,
				'validate_callback' => function ( $param, $request, $key ) {
					return is_numeric( $param );
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}
}
