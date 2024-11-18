<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Frontend;

use QuadLayers\IGG\Api\Rest\Endpoints\Base;
use QuadLayers\IGG\Models\Accounts as Models_Accounts;
use QuadLayers\IGG\Api\Fetch\Personal\User_Profile\Get as Api_Fetch_Personal_User_Profile;
use QuadLayers\IGG\Api\Fetch\Business\User_Profile\Get as Api_Fetch_Business_User_Profile;
use QuadLayers\IGG\Services\Cache;

class User_Profile extends Base {

	protected static $route_path = 'frontend/user-profile';

	protected $profile_cache_engine;
	protected $profile_cache_key = 'profile';

	public function callback( \WP_REST_Request $request ) {

		$account_id = $request->get_param( 'account_id' );

		// Get cache data and return it if exists.
		// Set prefix to cache.
		$profile_complete_prefix = "{$this->profile_cache_key}_{$account_id}";

		$this->profile_cache_engine = new Cache( 6, true, $profile_complete_prefix );

		// Get cached user profile data.
		$response = $this->profile_cache_engine->get( $profile_complete_prefix );

		// Check if $response has data, if it have return it.
		if ( ! QLIGG_DEVELOPER && ! empty( $response['response'] ) ) {
			return $response['response'];
		}

		$account = Models_Accounts::instance()->get( $account_id );

		// Check if exist an access_token and access_token_type related to id setted by param, if it is not return error.
		if ( ! isset( $account['access_token'], $account['access_token_type'] ) ) {
			return $this->handle_response(
				array(
					'code'    => 412,
					'message' => sprintf( esc_html__( 'Account id %s not found to fetch user profile.', 'insta-gallery' ), $account_id ),
				)
			);
		}

		$access_token = $account['access_token'];

		// Query to Api_Fetch_Personal_User_Profile if access_token_type is 'PERSONAL'.
		if ( 'PERSONAL' === $account['access_token_type'] ) {

			// Get user profile data.
			$response = ( new Api_Fetch_Personal_User_Profile() )->get_data( $access_token );

			// Check if response is an error and return it.
			if ( isset( $response['message'] ) && isset( $response['code'] ) ) {
				return $this->handle_response( $response );
			}

			// Check if response is not an error but neither a valid one.
			if ( empty( $response['id'] ) ) {

				$message = array(
					'code'    => 500,
					'message' => 'Ups something went wrong. Please try again.',
				);
				return $this->handle_response( $message );
			}

			// Update user profile data cache and return it.
			if ( ! QLIGG_DEVELOPER ) {
				$this->profile_cache_engine->update( $profile_complete_prefix, $response );
			}

			return $this->handle_response( $response );
		}
		// Query to Api_Fetch_Business_User_Profile.
		// Get user profile data.
		$response = ( new Api_Fetch_Business_User_Profile() )->get_data( $access_token, $account_id );

		// Check if response is an error and return it.
		if ( isset( $response['message'], $response['code'] ) ) {
			return $this->handle_response( $response );
		}

		// Update user profile data cache and return it.
		if ( ! QLIGG_DEVELOPER ) {
			$this->profile_cache_engine->update( $profile_complete_prefix, $response );
		}

		return $this->handle_response( $response );
	}

	public static function get_rest_args() {
		return array(
			'account_id' => array(
				'required'          => true,
				'sanitize_callback' => function ( $account_id ) {
					return sanitize_text_field( $account_id );
				},
				'validate_callback' => function ( $account_id ) {
					return is_numeric( $account_id );
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}

	public function get_rest_permission() {
		return true;
	}
}
