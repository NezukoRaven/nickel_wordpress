<?php

namespace QuadLayers\IGG\Services;

use QuadLayers\IGG\Api\Fetch\Business\Access_Token\Refresh as Api_Fetch_Business_Refresh_Access_Token;
use QuadLayers\IGG\Api\Fetch\Personal\Access_Token\Refresh as Api_Fetch_Personal_Refresh_Access_Token;

/**
 * Models_Feeds Class
 */
class Accounts {

	/**
	 * Set the max attemps to renew access_token to prevents API abuse.
	 *
	 * @var integer
	 */
	protected static $access_token_max_renew_attemps = 3;

	/**
	 * Function to get account access_token type
	 *
	 * @param array $account
	 * @return string
	 */
	protected function get_token_type( $account ) {
		if ( substr( $account['access_token'], 0, 2 ) === 'IG' ) {
			return 'PERSONAL';
		}
		return 'BUSINESS';
	}

	/**
	 * Function to clean token
	 *
	 * @param string $maybe_dirty
	 * @return string
	 */
	protected function clean_token( $maybe_dirty ) {
		if ( substr_count( $maybe_dirty, '.' ) < 3 ) {
			return str_replace( '634hgdf83hjdj2', '', $maybe_dirty );
		}

		$parts     = explode( '.', trim( $maybe_dirty ) );
		$last_part = $parts[2] . $parts[3];
		$cleaned   = $parts[0] . '.' . base64_decode( $parts[1] ) . '.' . base64_decode( $last_part );

		return $cleaned;
	}

	/**
	 * Function to renew access token
	 *
	 * @param string  $access_token Account access_token.
	 * @param integer $access_token_renew_attempts Account access_token renew attemps.
	 * @return array
	 */
	public function get_renewed_access_token( $access_token, $access_token_renew_attempts = 0 ) {

		if ( substr( $access_token, 0, 2 ) === 'IG' ) {
			return ( new Api_Fetch_Personal_Refresh_Access_Token() )->get_data( $access_token, $access_token_renew_attempts );
		}

		return ( new Api_Fetch_Business_Refresh_Access_Token() )->get_data( $access_token, $access_token_renew_attempts );
	}

	/**
	 * Function to increase access_token_renew_attempts
	 *
	 * @param array $account Account to increase access_token_renew_attempts property.
	 * @return void
	 */
	protected function access_token_renew_attemps_increase( $account ) {
		$account['access_token_renew_attempts'] = intval( $account['access_token_renew_attempts'] ) + 1;
		$this->update( $account['id'], $account );
	}

	/**
	 * Function to calculate expiration date based on current time and expires_in property
	 *
	 * @param int $expires_in Time lapse to expire.
	 * @return int
	 */
	public function calculate_expiration_date( $expires_in ) {
		return strtotime( current_time( 'mysql' ) ) + $expires_in - 1;
	}

	/**
	 * Function to validate account's access_token
	 *
	 * @param array $account Account to validate access_token.
	 * @return array|false
	 */
	public function is_access_token_renewed( $account ) {

		$is_access_token_about_to_expire = $this->is_access_token_expired( $account );

		/**
		 * Check if account is about to expire
		 */
		if ( ! $is_access_token_about_to_expire ) {
			return true;
		}

		if ( $this->access_token_renew_attemps_exceded( $account ) ) {
			return false;
		}

		$response = $this->get_renewed_access_token( $account['access_token'], $account['access_token_renew_attempts'] );

		/**
		 * Validate response
		 */
		if ( isset( $response['error'] ) || ! isset( $response['expires_in'] ) || ! isset( $response['access_token'] ) ) {
			$this->access_token_renew_attemps_increase( $account );
			return false;
		}

		if ( $account['access_token_expiration_date'] >= $this->calculate_expiration_date( $response['expires_in'] ) ) {
			return false;
		}

		$account['access_token_renew_attempts']  = 0;
		$account['access_token']                 = $response['access_token'];
		$account['access_token_expiration_date'] = $this->calculate_expiration_date( $response['expires_in'] );
		$account                                 = $this->update( $account['id'], $account );

		if ( $account ) {
			return true;
		}

		return false;
	}

	/**
	 * Function to check if account access_token is expired
	 *
	 * @param array $account Account to check it's access_token expiration.
	 * @return boolean
	 */
	protected function is_access_token_expired( $account ) {

		if ( ( $account['access_token_expiration_date'] - strtotime( current_time( 'mysql' ) ) ) < DAY_IN_SECONDS * 5 ) {
			return true;
		}

		return false;
	}

	/**
	 * Function to check if access_token_renew_attempts property exceded access_token_max_renew_attemps
	 *
	 * @param array $account Account to check if access_token_renew_attempts property is exceded.
	 * @return boolean
	 */
	protected function access_token_renew_attemps_exceded( $account ) {
		if ( intval( $account['access_token_renew_attempts'] ) > self::$access_token_max_renew_attemps ) {
			return true;
		}
		return false;
	}
}
